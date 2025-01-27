<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use App\Models\District;
use BalajiDharma\LaravelAdminCore\Actions\User\UserCreateAction;
use BalajiDharma\LaravelAdminCore\Actions\User\UserUpdateAction;
use BalajiDharma\LaravelAdminCore\Data\User\UserCreateData;
use BalajiDharma\LaravelAdminCore\Data\User\UserUpdateData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $this->authorize('adminViewAny', User::class);
         if(auth()->user()->organisation_id==0 && auth()->user()->organization_id==0){
        $users = (new User)->newQuery();

        if (request()->has('search')) {
            $users->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $users->orderBy($attribute, $sort_order);
        } else {
            $users->latest();
        }

        $users = $users->paginate(config('admin.paginate.per_page'))
                    ->onEachSide(config('admin.paginate.each_side'))
                    ->appends(request()->query());
    }

    //district level

    if(auth()->user()->organization_id===NULL){
        $users = (new User)->newQuery();

        if (request()->has('search')) {
            $users->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $users->orderBy($attribute, $sort_order);
        } else {
            $users->latest();
        }

        $users = $users->where('district_id',auth()->user()->district_id)->paginate(config('admin.paginate.per_page'))
                    ->onEachSide(config('admin.paginate.each_side'))
                    ->appends(request()->query());
    }

      if(auth()->user()->organization_id!=NULL){
        $users = (new User)->newQuery();

        if (request()->has('search')) {
            $users->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $users->orderBy($attribute, $sort_order);
        } else {
            $users->latest();
        }

        $users = $users->where([['district_id','=',auth()->user()->district_id],['organization_id','=',auth()->user()->organization_id]])->paginate(config('admin.paginate.per_page'))
                    ->onEachSide(config('admin.paginate.each_side'))
                    ->appends(request()->query());
    }
        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('user create'),
                'edit' => Auth::user()->can('user edit'),
                'delete' => Auth::user()->can('user delete'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('adminCreate', User::class);
        $roles = Role::all()->pluck('name', 'name');
$districts=District::get();
$organizations=Organization::get();
        return Inertia::render('Admin/User/Create', [
            'roles' => $roles,
            'organizations'=>$organizations,
            'districts'=>$districts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateData $data, UserCreateAction $userCreateAction)
    {

        $this->authorize('adminCreate', User::class);

        $userCreateAction->handle($data);

        return redirect()->route('admin.user.index')
            ->with('message', __('User created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        $this->authorize('adminView', $user);
        $roles = Role::all()->pluck('name', 'id');
        $userHasRoles = array_column(json_decode($user->roles, true), 'name');

        return Inertia::render('Admin/User/Show', [
            'user' => $user,
            'roles' => $roles,
            'userHasRoles' => $userHasRoles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(User $user)
    {
        $this->authorize('adminUpdate', $user);
        $roles = Role::all()->pluck('name', 'name');
        $userHasRoles = array_column(json_decode($user->roles, true), 'name');

        return Inertia::render('Admin/User/Edit', [
            'user' => $user,
            'roles' => $roles,
            'userHasRoles' => $userHasRoles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateData $data, User $user, UserUpdateAction $userUpdateAction)
    {
        $this->authorize('adminUpdate', $user);

        $userUpdateAction->handle($data, $user);

        return redirect()->route('admin.user.index')
            ->with('message', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('adminDelete', $user);
        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('message', __('User deleted successfully'));
    }

    /**
     * Show the user a form to change their personal information & password.
     *
     * @return \Inertia\Response
     */
    public function accountInfo()
    {
        $user = \Auth::user();

        return Inertia::render('Admin/User/AccountInfo', [
            'user' => $user,
        ]);
    }

    /**
     * Save the modified personal information for a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accountInfoStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.\Auth::user()->id],
        ]);

        $user = \Auth::user()->update($request->except(['_token']));

        if ($user) {
            $message = 'Account updated successfully.';
        } else {
            $message = 'Error while saving. Please try again.';
        }

        return redirect()->route('admin.account.info')->with('message', __($message));
    }

    /**
     * Save the new password for a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'old_password' => ['required'],
            'new_password' => ['required', Rules\Password::defaults()],
            'confirm_password' => ['required', 'same:new_password', Rules\Password::defaults()],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($validator->failed()) {
                return;
            }
            if (! Hash::check($request->input('old_password'), \Auth::user()->password)) {
                $validator->errors()->add(
                    'old_password', __('Old password is incorrect.')
                );
            }
        });

        $validator->validate();

        $user = \Auth::user()->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        if ($user) {
            $message = 'Password updated successfully.';
        } else {
            $message = 'Error while saving. Please try again.';
        }

        return redirect()->route('admin.account.info')->with('message', __($message));
    }
}
