<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\District;
use App\Models\OrganizationType;
use App\Models\Service;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


         $this->authorize('adminViewAny', Organization::class);

           if(auth()->user()->organisation_id==0 && auth()->user()->organization_id==0){
        $organizations = (new Organization)->newQuery();
  if (request()->has('district_id')) {

            if(request()->input('district_id')!=null){
            $organizations->where('district_id', request()->input('district_id'));
  }
  else{
    $organizations->latest();
  }
        }
        if (request()->has('search')) {
            $organizations->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $organizations->orderBy($attribute, $sort_order);
        } else {
            $organizations->latest();
        }

       $organizations =  $organizations->where('id','<>',0)->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));
    }
//district
    if(auth()->user()->organization_id===NULL){

   $organizations = (new Organization)->newQuery();

        if (request()->has('sort')) {
            dd(request()->input('sort'));
            $organizations->where('district_id', '=', request()->input('sort'));
        }
        if (request()->has('search')) {
            $organizations->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $organizations->orderBy($attribute, $sort_order);
        } else {
            $organizations->latest();
        }

       $organizations =  $organizations->where('id','<>',0)->where('district_id',auth()->user()->district_id)->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));

    }


    //org level

    if(auth()->user()->organization_id!=NULL){
   $organizations = (new Organization)->newQuery();

        if (request()->has('search')) {
            $organizations->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $organizations->orderBy($attribute, $sort_order);
        } else {
            $organizations->latest();
        }

       $organizations =  $organizations->where('id','<>',0)->where([['district_id','=',auth()->user()->district_id],['id',auth()->user()->organization_id]])->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));


    }
  $districts=District::where('id','<>',0)->get();
if(auth()->user()->district_id===0 && auth()->user()->organization_id==0 ){
        $add=true;
        $search=false;
        $filter=true;
    }
    else{
        $add=false;
        $search=true;
        $filter=false;
    }
        return Inertia::render('Admin/Organisation/Index', [
            'items' =>collect( $organizations),
            'default_logo'=>asset('logo/logo.png'),
            'path'=>asset('logo'),
            'districts'=>$districts,
            'filters' => request()->all('search'),
            'can' => [
                'search'=>$search,
                'filter'=>$filter,
                'create' =>$add,
                'edit' => Auth::user()->can('media edit'),
                'delete' => Auth::user()->can('media delete'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('adminCreate', Media::class);
        //NATIONAL LEVEL
 if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
$names=District::select('id','name')->where('id','<>',0)->get();
 }
//DISTRICT LEVEL
  if(auth()->user()->organization_id===NULL){
$names=District::select('id','name')
->where('id','<>',0)
->where('id',auth()->user()->district_id)
->get();
 }
//ORGANISATION LEVEL
   if(auth()->user()->organization_id!=NULL){
$names=District::select('id','name')
->where('id','<>',0)
->where('id',auth()->user()->district_id)
->get();
 }
        $org_type= OrganizationType::get();
        $org_sector=DB::table('service_sectors')->select('id','name')->get();
        return Inertia::render('Admin/Organisation/Create', [
            'typeOptions' => $org_type,
            'sectors' => $org_sector,
            'districts'=>$names
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //$this->authorize('adminCreate', Organization::class);
         $request->validate([
        'name' => 'required',
        'sector' => 'required',
        'district'=>'required',


    ]);
  //dd($request);
    $logo='logo.png';
try{
  if($request->hasFile('file')){
$filename = time() . '.' . $request->file->extension();
$logo=$filename;
$request->file->move(public_path()."/logo/", $filename);
    }
$organization=new Organization();
$organization->organization_type_id=$request->type;
$organization->name=$request->name;

$organization->description=$request->description;
$organization->district_id=$request->district;
$organization->phone=$request->phone;
$organization->address=$request->address;
$organization->url=$request->url;
$organization->logo=$logo;
$organization->email=$request->email;
$organization->created_at=now();
$organization->updated_at=NULL;
$organization->save();
$org_id=$organization->id;

if(count($request->sector)>0){
foreach ($request->sector as $key => $value) {
DB::table('organization_service_sector')->insert([
'organization_id'=>$org_id,
'sector_id'=>$value['id'],
'created_at'=>now(),

]);
}
}



        return redirect()->route('admin.organization.index')
            ->with('message', __('Organization created successfully.'));
}catch(\Exception $e){
return redirect()->back()->with('error', "Failed to create organization");
}
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        $this->authorize('adminView', $organization);
        $services= DB::table('services')
        ->join('service_charges','services.service_charge_id','=','service_charges.id')
        ->join('districts','services.district_id','=','districts.id')
        ->select('services.*','service_charges.name as charge_name','districts.name as district_name')
        ->where('organization_id',$organization->id)->get();
        return Inertia::render('Admin/Organisation/Show', [
            'organization' => $organization,
            'services'=>$services,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       $organization= Organization::findOrFail($id);
        $this->authorize('adminUpdate', $organization);
 $typeOptions = OrganizationType::get();
 $sectors=DB::table('service_sectors')->select('id','name')->get();
 $sector_selected=DB::table('organization_service_sector')->where('organization_id',$id)->pluck('sector_id')->toArray();
$chosen_sector=DB::table('service_sectors')->whereIn('id',$sector_selected)->select('id','name')->get();

$selected=OrganizationType::where('id',$organization->organization_type_id)->first();
        return Inertia::render('Admin/Organisation/Edit', [
            'organization' => $organization,
             'typeOptions' => $typeOptions,
               'default_logo'=>asset('logo/logo.png'),
            'path'=>asset('logo'),
             'selected'=>$selected,
             'sector_selected'=>$chosen_sector,
              'sectors'=>$sectors,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {


        $name=$request->name;
        $description=$request->description;
        $type=$request->type;
        $phone=$request->phone;
        $email=$request->email;
        $url=$request->url;
        $address=$request->address;
        $logo=$request->file;
          $organization=Organization::where('id',$id)->first();
        if($request->hasFile('file')){

$filename = time() . '.' . $request->file->extension();
 $imageUnlink = public_path() .''. $organization->logo;
  if (file_exists($imageUnlink)) {
                   // unlink($imageUnlink);
                }

$request->file->move(public_path()."/logo/", $filename);
$logo=$filename;
        }

 Organization::where('id',$id)->update([
'name'=>$name,
'description'=>$description,
'organization_type_id'=>$type,
'phone'=>$phone,
'url'=>$url,
'logo'=>$logo,
'address'=>$address,
'email'=>$email
      ]);


if(count($request->sector)>0){
   DB::table('organization_service_sector')->where('organization_id',$id)->delete();
foreach ($request->sector as $key => $value) {
DB::table('organization_service_sector')->insert([
'organization_id'=>$id,
'sector_id'=>$value['id'],
'created_at'=>now(),

]);
}
}
        $this->authorize('adminUpdate',  $organization);


        return redirect()->route('admin.organization.index')
            ->with('message', __('Organization updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

         $organization= Organization::findOrFail($id);
        $this->authorize('adminDelete', $organization);
        $organization->delete();

        return redirect()->route('admin.organization.index')
            ->with('message', __('Organization deleted successfully.'));
    }


    public function addService($id){
       $this->authorize('adminCreate', Service::class);
       $organization=Organization::find($id);
       //national level user
 if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
      $organizations = Organization::select('id','name','district_id')->where('id',$id)->get();
      //dd($organizations);
 }
 //district level user
 if(auth()->user()->organization_id===NULL){
     $organizations = Organization::select('id','name','district_id')
    ->where('id',$id)
     ->get();
 }

 //location level user
  if(auth()->user()->organization_id!=NULL){
     $organizations = Organization::select('id','name','district_id')
     ->where('id','<>',0)
    ->where('id',$id)

     ->get();
 }

//NATIONAL LEVEL
 if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
$names=District::select('id','name')->where('id', $organization->district_id)->get();
 }
//DISTRICT LEVEL
  if(auth()->user()->organization_id===NULL){
$names=District::select('id','name')
->where('id', $organization->district_id)
->get();
 }
//ORGANISATION LEVEL
   if(auth()->user()->organization_id!=NULL){
$names=District::select('id','name')
->where('id', $organization->district_id)
->get();
 }

        //dd($typeOptions);
        $types=DB::table('service_sectors')->get();
        $scope=DB::table('service_scopes')->get();
        $beneficies=DB::table('beneficiaries')->select('id','name')->get();
        $charges=DB::table('service_charges')->get();
        $number=array('<100','100-500','501-1000','>1000');
        $locations=array('1','2-3','4-5','More than 5');
        return Inertia::render('Admin/Organisation/AddService', [
            'organizations' =>$organizations,
            'districts'=>$names,
            'types'=>$types,
            'scopes'=>$scope,
            'beneficies'=>$beneficies,
            'numbers'=>$number,
            'locations'=>$locations,
            'charges'=>$charges,
            'organization_id'=>$id,

        ]);
    }
    public function storeOrganizationService(Request $request){

           //$this->authorize('adminCreate', Service::class);
         $request->validate([
         'name' => 'required',
         'end'   => 'required',
         'start' => 'required|before:end',
         'organization'=>'required',
         'district'=>'required',
         'years_district'=> 'required|numeric|gt:0',


    ]);

       try {
DB::beginTransaction();

$is_available=DB::table('year_organization_district')->where('district_id',$request->district)->where('organization_id',$request->organization)->first();

$service=new Service();
        $service->name=$request->name;
        $service->description=$request->description;
        $service->organization_id=$request->organization;
        $service->district_id=$request->district;
        $service->areas= $request->specific_area;
        $service->service_sector_id=$request->type;
        $service->service_scope=$request->scope;

        $service->service_charge_id=$request->charge;
        $service->start_date=$request->start;
        $service->end_date=$request->end;
        $service->number_of_beneficiary=$request->number;
        $service->created_at=now();
        $service->updated_at=NULL;

        $service->save();
        $service_id=$service->id;
        /**
         * store beneficiary if not empty
         */
        if(!empty($request->beneficiary) &&count($request->beneficiary)>0){
            foreach ($request->beneficiary as $key => $value) {
                DB::table('service_beneficiaries')->insert([
                    'service_id'=>$service_id,
                    'beneficiary_id'=>$value['id'],
                    'created_at'=>now(),
                ]);
            }
        }
        //T/AS
          if(!empty($request->ta) &&count($request->ta)>0){
            foreach ($request->ta as $key => $value) {
                DB::table('service_at_locations')->insert([
                    'service_id'=>$service_id,
                    'location_id'=>$value['id'],
                    'created_at'=>now(),
                ]);
            }
        }



      // if location is not emptyc create location
if(!empty($request->coordinates) && count($request->coordinates)>0){
    for($i=0;   $i < count($request->coordinates); $i++){
    $location=new Location();
$location->service_id= $service_id;
$location->latitude=$request->coordinates[$i]['lat'];
$location->longitude=$request->coordinates[$i]['lng'];
$location->created_at=now();
$location->updated_at=NULL;
$location->save();
    }
}
if($request->other_b!=null){
    DB::table('type_other')->insert(
    [
     'service_id' => $service_id,
     'name' =>$request->other_b,
     'created_at'=>now(),
     'updated_at'=>NULL,
     ]
);

}
if($is_available==null){
    DB::table('year_organization_district')->insert(
    [
     'organization_id' => $request->organization,
     'district_id' =>$request->district,
     'years_in_districts'=>$request->years_district,
     'created_at'=>now(),
     'updated_at'=>NULL,
     ]
);
}
DB::commit();
         return redirect()->route('admin.organization.show', $request->organization)
            ->with('message', __('Service created successfully.'));
       } catch (Exception $e) {
        DB::rollback();
        //throw $th;
   return redirect()->route('admin.organization.show', $request->organization)
            ->with('error', __('Failed creating service.'));
       }

    }

    public function getDistrictId($id){
        $org=Organization::find($id);
        if($org->district_id!=NULL){
        $district=District::find($org->district_id);

        return $district->name;
    }
    else{
        return"";
    }
    }
}
