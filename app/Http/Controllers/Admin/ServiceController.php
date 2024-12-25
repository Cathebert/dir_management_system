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
        $organizations = (new Service)->newQuery();

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
        $types=array('Health','Education','Social Protection','Nutrition','Water, Sanitation, and Hygiene (WASH)','Economic Empowerment','Financial Inclusion','Other (Specify)');
        $scope=array('Preventive','Curative','Supportive','Advocacy','Capacity Building','Other(Specify)');
        $beneficies=array('Children','Women','Elderly','Disabled','Youth','Other(Specify)');
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

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


         $request->validate([
        'name' => 'required',
        'organization'=>'required'


    ]);
       try {
DB::beginTransaction();
       $org=Organization::where('name',$request->organization)->select('id')->first();
       $service=new Service();
       $service->organization_id=$org->id;
       $service->district=$request->district;
       $service->organization_name=$request->organization;
       $service->name=$request->name;
       $service->service_type=$request->type;
       $service->service_scope=$request->scope;
       $service->type_of_beneficiary=$request->beneficiary;
       $service->number_of_beneficiary=$request->number;
       $service->unique_services=$request->unique;
        $service->number_service_location=$request->location;
       $service->challenges_faced=$request->challenges;

       $service->save();
       $service_id=$service->id;
      // dd($request->coordinates[1]['lng']);
if(count($request->coordinates)>0){
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

        $service = Service::findOrFail($id);
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
           $organization= Service::findOrFail($id);
        $this->authorize('adminUpdate', $organization);
$coordinates=Location::where('service_id',$id)->select('latitude','longitude')->get();

    $types=array('Health','Education','Social Protection','Nutrition','Water, Sanitation, and Hygiene (WASH)','Economic Empowerment','Financial Inclusion','Other (Specify)');
        $scope=array('Preventive','Curative','Supportive','Advocacy','Capacity Building','Other(Specify)');
        $beneficies=array('Children','Women','Elderly','Disabled','Youth','Other(Specify)');
        $number=array('<100','100-500','501-1000','>1000');
        $locations=array('1','2-3','4-5','More than 5');
        return Inertia::render('Admin/Service/Edit', [
            'organization' => $organization,
             'types'=>$types,
            'scopes'=>$scope,
            'beneficies'=>$beneficies,
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
    'service_type'=>$request->type,
    'service_scope'=>$request->scope,
    'type_of_beneficiary'=>$request->beneficiary,
    'number_of_beneficiary'=>$request->number,
    'unique_services'=>$request->unique,
    'number_service_location'=>$request->location,
    'challenges_faced'=>$request->challenges,
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
