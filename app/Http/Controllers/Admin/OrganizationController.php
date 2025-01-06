<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationType;
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

       $organizations =  $organizations->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));


        return Inertia::render('Admin/Organisation/Index', [
            'items' =>collect( $organizations),
            'default_logo'=>asset('logo/logo.png'),
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
        //
        $this->authorize('adminCreate', Media::class);
        $org_type= OrganizationType::get();
        $org_sector=DB::table('service_sectors')->select('id','name')->get();
        return Inertia::render('Admin/Organisation/Create', [
            'typeOptions' => $org_type,
            'sectors' => $org_sector,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('adminCreate', Organization::class);
         $request->validate([
        'name' => 'required',
        'sector' => 'required',


    ]);

    $logo='/logo/logo.png';
try{
    if($request->hasFile('file')){
$filename = time() . '.' . $request->file->extension();
$logo='/logo/'.$filename;
$request->file->move(public_path()."/logo/", $filename);
    }
$organization=new Organization();
$organization->organization_type_id=$request->type;
$organization->name=$request->name;
$organization->description=$request->description;
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
dd($request->all());

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
                    unlink($imageUnlink);
                }

$request->file->move(public_path()."/logo/", $filename);
$logo='/logo/'.$filename;
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
}
