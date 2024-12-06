<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BalajiDharma\LaravelAdminCore\Data\Media\MediaCreateData;
use BalajiDharma\LaravelAdminCore\Data\Media\MediaUpdateData;
use BalajiDharma\LaravelAdminCore\Data\Media\MediaData;
use Plank\Mediable\Media;
use Illuminate\Support\Facades\Auth;
use App\Models\District;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $this->authorize('adminViewAny', District::class);
        $mediaItems = (new District)->newQuery();

        if (request()->has('search')) {
            $mediaItems->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $mediaItems->orderBy($attribute, $sort_order);
        } else {
            $mediaItems->latest();
        }

        $mediaItems = $mediaItems->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));


        return Inertia::render('Admin/District/Index', [
            'items' =>collect($mediaItems),
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
        $this->authorize('adminCreate', Media::class);
        $typeOptions = media_type_as_options();

        return Inertia::render('Admin/District/Create', [
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
$district=new District();
$district->name=$request->name;
$district->save();

        return redirect()->route('admin.district.index')
            ->with('message', __('District created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $media = District::findOrFail($id);

        $this->authorize('adminView', $media);

        return Inertia::render('Admin/District/Show', [
            'district' =>$media,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $district= District::findOrFail($id);
        $this->authorize('adminUpdate', $district);


        return Inertia::render('Admin/District/Edit', [
            'district' => $district,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  District $district)
    {
        $name=$request->name;
       $district->update([
'name'=>$name,

      ]);
        $this->authorize('adminUpdate', $district);


        return redirect()->route('admin.district.index')
            ->with('message', __('District updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}