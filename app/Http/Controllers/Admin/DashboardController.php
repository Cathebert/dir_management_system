<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\Organization;
use App\Models\District;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index(){


  if(auth()->user()->organisation_id==0 && auth()->user()->organization_id==0){
$service_count=Service::count();
 $org=Organization::count();
$users=User::count();
$beneficiary_count=Service::selectRaw('sum(number_of_beneficiary) as beneficiaries')->get();

    }
     if(auth()->user()->organization_id===NULL){

$service_count=Service::where('district_id',auth()->user()->district_id)->count();
 $org=Organization::where('district_id',auth()->user()->district_id)->count();
$users=User::where('district_id',auth()->user()->district_id)->count();
$beneficiary_count=Service::selectRaw('sum(number_of_beneficiary) as beneficiaries')->where('district_id',auth()->user()->district_id)->get();
    }

      if(auth()->user()->organization_id!=NULL){
 $org=Organization::where('district_id',auth()->user()->district_id)->count();
$service_count=Service::where('district_id',auth()->user()->district_id)
->where('organization_id',auth()->user()->organization_id)
->count();
$users=User::where('district_id',auth()->user()->district_id)
->where('organization_id',auth()->user()->organization_id)
->count();
$beneficiary_count=Service::selectRaw('sum(number_of_beneficiary) as beneficiaries')->where('organization_id',auth()->user()->organization_id)->get();

    }

   // $this->authorize('adminViewAny', Organization::class);
    if(auth()->user()->organisation_id==0 && auth()->user()->organization_id==0){
    $organizations = (new  Organization)->newQuery();

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
    }
    if(auth()->user()->organization_id===NULL){

$organizations = (new  Organization)->newQuery();

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

       $organizations =  $organizations->where('district_id',auth()->user()->district_id)->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));
    }

    if(auth()->user()->organization_id!=NULL){
$organizations = (new  Organization)->newQuery();

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

       $organizations =  $organizations->where('district_id',auth()->user()->district_id)->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));


    }
    $districts=District::where('id','<>',0)->get();
        return Inertia::render('Admin/Dashboard', [
            'items' =>collect( $organizations),
            'organization_count'=>$org,
            'districts'=>$districts,
            'service_count'=>$service_count,
            'users'=>$beneficiary_count[0]->beneficiaries,
            'default_logo'=>asset('logo/logo.png'),
            'path'=>asset('logo'),
            'beneficiary_sum'=>$beneficiary_count,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('media create'),
                'edit' => Auth::user()->can('media edit'),
                'delete' => Auth::user()->can('media delete'),
            ],
        ]);
    }

    public function getDashboardData($id){
        $service_count=Service::where('district_id',$id)->count();

      return([
        'service_count'=>$service_count,
      ]);
    }
}
