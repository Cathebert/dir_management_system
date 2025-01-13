<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Organization;
use App\Models\District;
use App\Models\Location;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      $this->authorize('adminViewAny', Service::class);
      /*   $organizations = Service::query()
                            ->with('organization')
                            ->with('district')
                            ->with('beneficiary');

        dd($organizations);
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
        } */
       //national level
       if(auth()->user()->organisation_id==0 && auth()->user()->organization_id==0){

 $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
                ->orderBy('s.id','DESC');

  if (request()->query('sort')) {
     $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
;

            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            //dd(request()->query('sort'));
           $service->orderBy($attribute, $sort_order);
        }
           if (request()->has('search')) {
            $service->where('s.name', 'Like', '%'.request()->input('search').'%');
        }
       $organizations =  $service->paginate(config('admin.paginate.per_page'))
       ->onEachSide(config('admin.paginate.each_side'));
    }
/**
 * select data based on the district or organization;
 *
 */
   if(auth()->user()->organization_id===NULL){

 $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
               ->where('s.district_id',auth()->user()->district_id)
                ->orderBy('s.id','DESC');

  if (request()->query('sort')) {
     $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
;

            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            //dd(request()->query('sort'));
           $service->orderBy($attribute, $sort_order);
        }
           if (request()->has('search')) {
            $service->where('s.name', 'Like', '%'.request()->input('search').'%');
        }
       $organizations =  $service->paginate(config('admin.paginate.per_page'))
       ->onEachSide(config('admin.paginate.each_side'));



    }

   if(auth()->user()->organization_id!=NULL){
 $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
               ->where('s.district_id',auth()->user()->district_id)
               ->where('s.organization_id',auth()->user()->organization_id)
                ->orderBy('s.id','DESC');

  if (request()->query('sort')) {
     $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')

                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','o.id as organization_id','d.name as district','sc.name as charge','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
;

            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            //dd(request()->query('sort'));
           $service->orderBy($attribute, $sort_order);
        }
           if (request()->has('search')) {
            $service->where('s.name', 'Like', '%'.request()->input('search').'%');
        }
       $organizations =  $service->paginate(config('admin.paginate.per_page'))
       ->onEachSide(config('admin.paginate.each_side'));



   }
        return Inertia::render('Admin/Service/Index', [
            'items' =>collect( $organizations),
            'default_logo'=>asset('logo/location_img.png'),
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('media create'),
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
       $this->authorize('adminCreate', Service::class);
       //national level user
 if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
      $organizations = Organization::select('id','name')->where('id','<>',0)->get();
      //dd($organizations);
 }
 //district level user
 if(auth()->user()->organization_id===NULL){
     $organizations = Organization::select('id','name')
     ->where('id','<>',0)
    ->where('district_id',auth()->user()->district_id)
     ->get();
 }

 //location level user
  if(auth()->user()->organization_id!=NULL){
     $organizations = Organization::select('id','name')
     ->where('id','<>',0)
    ->where('district_id',auth()->user()->district_id)

     ->get();
 }

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

        //dd($typeOptions);
        $types=DB::table('service_sectors')->get();
        $scope=DB::table('service_scopes')->get();
        $beneficies=DB::table('beneficiaries')->select('id','name')->get();
        $charges=DB::table('service_charges')->get();
        $number=array('<100','100-500','501-1000','>1000');
        $locations=array('1','2-3','4-5','More than 5');
        return Inertia::render('Admin/Service/Create', [
            'organizations' =>$organizations,
            'districts'=>$names,
            'types'=>$types,
            'scopes'=>$scope,
            'beneficies'=>$beneficies,
            'numbers'=>$number,
            'locations'=>$locations,
            'charges'=>$charges,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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
if(!empty($request->ta)){
    $tas=DB::table('district_traditionals')->where('id',$request->ta)->select('name')->first();
    $specific_location=$tas->name.'-'.$request->specific_area;
   }
   else{
    $specific_location=$request->specific_area;
   }
$is_available=DB::table('year_organization_district')->where('district_id',$request->district)->where('organization_id',$request->organization)->first();

$service=new Service();
        $service->name=$request->name;
        $service->description=$request->description;
        $service->organization_id=$request->organization;
        $service->district_id=$request->district;
        $service->areas=$request->ta;
        $service->areas= $specific_location;
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
         return redirect()->route('admin.service.index')
            ->with('message', __('Service created successfully.'));
       } catch (Exception $e) {
        DB::rollback();
        //throw $th;
   return redirect()->route('admin.service.index')
            ->with('error', __('Failed creating service.'));
       }
        //dd($request->coordinates[0]['lat']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

       // $service = Service::findOrFail($id);
        $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')
                ->join('service_charges as sc','s.service_charge_id','=','sc.id')

                ->select(
                    's.id as id',
                    's.name as service_name',
                    's.description as service_description',
                    'o.id as organization_id',
                    'o.organization_type_id	 as type',
                    'o.name as organization',
                    'o.phone as phone',
                    'o.address as address',
                    'd.name as district',
                    'sc.name as charge',
                    's.start_date',
                    's.end_date',
                    's.areas as areas',
                    's.number_of_beneficiary',
                    's.created_at',
                    's.updated_at'
                    )
                ->where('s.id',$id)
                ->first();
$organization_sector=DB::table('service_sectors as s')
                    ->join('organization_service_sector as oss','s.id','=','oss.sector_id')
                    ->where('oss.organization_id',$service->organization_id)
                    ->get();
$organization_type=DB::table('organization_types')
                    ->where('id',$service->type)->first();

$beneficiary=DB::table('beneficiaries')
        ->leftjoin('service_beneficiaries','beneficiaries.id','=','service_beneficiaries.beneficiary_id')
       ->where('service_beneficiaries.service_id',$id)
       ->select('beneficiaries.id','beneficiaries.name')
       ->get(); //dd($beneficiary);
$other_beneficiary=DB::table('type_other')->where('service_id',$id)->select('name')->first();

if($service==null){
    $service = Service::findOrFail($id);
}

$coordinates=Location::where('service_id',$id)->select('latitude','longitude')->get();
        $this->authorize('adminView',  $service);

        return Inertia::render('Admin/Service/Show', [
            'service' => $service,
            'coordinates'=>$coordinates,
            'beneficiaries'=>$beneficiary,
            'organization_sector'=>$organization_sector,
            'organization_type'=>$organization_type,
            'other_beneficiary'=>$other_beneficiary,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
           $service= Service::findOrFail($id);
            if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
           $organizations= Organization::select('id','name')->where('id','<>',0)->get();
            }
            if(auth()->user()->organization_id===NULL){
   $organizations = Organization::select('id','name')
     ->where('id','<>',0)
    ->where('district_id',auth()->user()->district_id)
     ->get();
            }

    if(auth()->user()->organization_id!=NULL){
    $organizations = Organization::select('id','name')
     ->where('id','<>',0)
    ->where('district_id',auth()->user()->district_id)
     ->get();
            }

            $organization= Organization::where('id',$service->organization_id)->select('id','name')->first();
        $this->authorize('adminUpdate', $service);
        $coordinates=Location::where('service_id',$id)->select('latitude','longitude')->get();
         if(auth()->user()->district_id===0 && auth()->user()->organization_id===0){
        $districts=DB::table('districts')->where('id','<>',0)->get();
         }

    if(auth()->user()->organization_id===NULL){
     $districts=District::select('id','name')
->where('id','<>',0)
->where('id',auth()->user()->district_id)
->get();
         }

 if(auth()->user()->organization_id!=NULL){
 $districts=District::select('id','name')
->where('id','<>',0)
->where('id',auth()->user()->district_id)
->get();
         }

        $selected=District::where('id',$service->district_id)->first();
        $tas=DB::table('district_traditionals')->where('district_id',$service->district_id)->get();
        $ta=DB::table('district_traditionals')->where('id',$service->areas)->first();
        //dd($typeOptions);

        $beneficies=DB::table('beneficiaries')->get();
        $beneficiary_ids=DB::table('service_beneficiaries')->where('service_id',$id)->pluck('beneficiary_id')->toArray();
       $beneficiary_selected=DB::table('beneficiaries')->whereIn('id', $beneficiary_ids)->select('id','name')->get();
        //dd($beneficiary_ids);
       $charges= DB::table('service_charges')->get();
        $charge_selected= DB::table('service_charges')->where('id',$service->service_charge_id)->select('id','name')->first();
        $number=array('<100','100-500','501-1000','>1000');
        $locations=array('1','2-3','4-5','More than 5');
        return Inertia::render('Admin/Service/Edit', [
            'service' => $service,
            'organizations' =>$organizations,
            'organization'=>$organization,
            'districts'=>$districts,
            'tas'=>$tas,
            'charges'=>$charges,
            'charge_selected'=>$charge_selected,
            'district'=>$selected,

            'beneficiaries'=>$beneficies,
            'beneficiary_selected'=>$beneficiary_selected,
            'numbers'=>$number,
            'locations'=>$locations,
            'coordinates'=>$coordinates,

        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //dd($request->all());
        $request->validate([
        'name' => 'required',
        'end'   => 'required',
        'organization'=>'required',
        'district'=>'required',
         'number'=> 'required',
         ]);

if(!empty($request->ta)){
    $tas=DB::table('district_traditionals')->where('id',$request->ta)->select('name')->first();
    $specific_location=$tas->name.'-'.$request->specific_area;
   }
   else{
    $specific_location=$request->specific_area;
   }
//dd($specific_location);
    $service->update([
    'name'=>$request->name,
    'description'=>$request->description,
    'organization_id'=>$request->organization,
    'end_date'=>$request->end,
    'district_id'=>$request->district,
    'areas'=>$specific_location,
    'service_charge_id'=>$request->charge,
    'number_of_beneficiary'=>$request->number,

       ]);
$service_id=$service->id;

 /**
         * delete and update beneficiaries
         */
        if(!empty($request->beneficiary) &&count($request->beneficiary)>0){
            DB::table('service_beneficiaries')->where('service_id',$service_id)->delete();
            foreach ($request->beneficiary as $key => $value) {
                DB::table('service_beneficiaries')->insert([
                    'service_id'=>$service_id,
                    'beneficiary_id'=>$value['id'],
                    'created_at'=>now(),
                ]);
            }
        }


             if(count($request->coordinates)>0){
        Location::where('service_id',$service_id)->delete();
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
  $this->authorize('adminUpdate',  $service);
return redirect()->route('admin.service.index')
            ->with('message', __('Service updated successfully.'));

    }
/**
 * get beneficiary type for the service
 */
public function getBeneficiaryType($id){
    $beneficiary=DB::table('beneficiaries')
        ->leftjoin('service_beneficiaries','beneficiaries.id','=','service_beneficiaries.beneficiary_id')
       ->where('service_beneficiaries.service_id',$id)
       ->select('beneficiaries.id','beneficiaries.name')
       ->get();
    return response()->json($beneficiary);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $this->authorize('adminDelete', $service);
        $service->delete();

        return redirect()->route('admin.service.index')
            ->with('message', __('Service deleted successfully.'));
    }
}
