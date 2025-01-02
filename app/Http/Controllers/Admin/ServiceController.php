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
 $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')
                ->join('service_sectors as ss','s.service_sector_id','=','ss.id')
                ->join('service_charges as sc','s.service_charge_id','=','sc.id')
                ->join('service_scopes as scp','s.service_scope','=','scp.id')
                ->join('beneficiaries as b','s.beneficiary_id','=','b.id')
                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','d.name as district','ss.name as service_type','sc.name as charge','scp.name as service_scope','b.name as beneficiary','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
                ->orderBy('s.id','DESC');

  if (request()->query('sort')) {
     $service=DB::table('services as s')
                ->join('organizations as o','s.organization_id','=','o.id')
                ->join('districts as d','s.district_id','=','d.id')
                ->join('service_sectors as ss','s.service_sector_id','=','ss.id')
                ->join('service_charges as sc','s.service_charge_id','=','sc.id')
                ->join('service_scopes as scp','s.service_scope','=','scp.id')
                ->join('beneficiaries as b','s.beneficiary_id','=','b.id')
                ->select('s.id as id','s.name as service_name','s.description','o.name as organization_name','d.name as district','ss.name as service_type','sc.name as charge','scp.name as service_scope','b.name as beneficiary','s.start_date','s.end_date','s.areas','s.number_of_beneficiary','s.created_at','s.updated_at')
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

      $organizations = Organization::select('id','name')->get();

$districts=array();
$names=District::select('id','name')->get();
foreach($names as $district){
    $districts[]=$district->name;
}
        //dd($typeOptions);
        $types=DB::table('service_sectors')->get();
        $scope=DB::table('service_scopes')->get();
        $beneficies=DB::table('beneficiaries')->get();
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


    ]);
       try {
DB::beginTransaction();

       $service=new Service();
        $service->name=$request->name;
        $service->description=$request->description;
       $service->organization_id=$request->organization;
       $service->district_id=$request->district;
        $service->beneficiary_id=$request->beneficiary;
        $service->service_sector_id=$request->type;
       $service->service_scope=$request->scope;
       $service->areas=$request->ta;
       $service->service_charge_id=$request->charge;
       $service->start_date=$request->start;
       $service->end_date=$request->end;
       $service->number_of_beneficiary=$request->number;
       $service->created_at=now();
       $service->updated_at=NULL;

       $service->save();
       $service_id=$service->id;
      // dd($request->coordinates[1]['lng']);
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
                ->join('service_sectors as ss','s.service_sector_id','=','ss.id')
                ->join('service_charges as sc','s.service_charge_id','=','sc.id')
                ->join('service_scopes as scp','s.service_scope','=','scp.id')
                ->join('beneficiaries as b','s.beneficiary_id','=','b.id')
                ->join('district_traditionals as dt','s.areas','=','dt.id')
                ->select('s.id as id','s.name as service_name','s.description','o.name as organization','d.name as district','ss.name as type','sc.name as charge','scp.name as scope','b.name as beneficiary','s.start_date','s.end_date','dt.name as areas','s.number_of_beneficiary','s.created_at','s.updated_at')
                ->where('s.id',$id)
                ->first();

if($service==null){
    $service = Service::findOrFail($id);
}
$coordinates=Location::where('service_id',$id)->select('latitude','longitude')->get();
        $this->authorize('adminView',  $service);

        return Inertia::render('Admin/Service/Show', [
            'service' => $service,
            'coordinates'=>$coordinates,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
           $service= Service::findOrFail($id);
           $organizations= Organization::select('id','name')->get();
            $organization= Organization::where('id',$service->organization_id)->select('id','name')->first();
        $this->authorize('adminUpdate', $service);
$coordinates=Location::where('service_id',$id)->select('latitude','longitude')->get();
$districts=DB::table('districts')->get();
$selected=District::where('id',$service->district_id)->first();
$tas=DB::table('district_traditionals')->where('district_id',$service->district_id)->get();
$ta=DB::table('district_traditionals')->where('id',$service->areas)->first();
        //dd($typeOptions);
   $types=DB::table('service_sectors')->get();
   $selected_type=DB::table('service_sectors')->where('id',$service->service_sector_id)->select('id','name')->first();
        $scope=DB::table('service_scopes')->get();
        $scope_selected=DB::table('service_scopes')->where('id',$service->service_scope)->select('id','name')->first();
        $beneficies=DB::table('beneficiaries')->get();
        $beneficiary_selected=DB::table('beneficiaries')->where('id',$service->beneficiary_id)->select('id','name')->first();
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
            'types'=>$types,
            'selected_type'=>$selected_type,
            'scopes'=>$scope,
            'scope_selected'=>$scope_selected,
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

    $service->update([
    'name'=>$request->name,
    'description'=>$request->description,
    'organization_id'=>$request->organization,
    'end_date'=>$request->end,
    'district_id'=>$request->district,
    'beneficiary_id'=>$request->beneficiary,
    'service_sector_id'=>$request->type,
    'areas'=>$request->ta,
    'service_charge_id'=>$request->charge,
    'service_scope'=>$request->scope,
    'number_of_beneficiary'=>$request->number,

       ]);
$service_id=$service->id;
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
