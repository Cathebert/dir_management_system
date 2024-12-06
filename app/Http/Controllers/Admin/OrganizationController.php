<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
        $typeOptions = media_type_as_options();

        return Inertia::render('Admin/Organisation/Create', [
            'typeOptions' => $typeOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
        'name' => 'required',


    ]);
    $logo='/logo/logo.png';

    if($request->hasFile('file')){
$filename = time() . '.' . $request->file->extension();
$logo='/logo/'.$filename;
$request->file->move(public_path()."/logo/", $filename);
    }
$organization=new Organization();
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

        return redirect()->route('admin.organization.index')
            ->with('message', __('Organization created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       $organization= Organization::findOrFail($id);
        $this->authorize('adminUpdate', $organization);


        return Inertia::render('Admin/Organisation/Edit', [
            'organization' => $organization,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
//dd($request);

        $name=$request->name;
        $description=$request->description;
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
'phone'=>$phone,
'url'=>$url,
'logo'=>$logo,
'address'=>$address,
'email'=>$email
      ]);
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
