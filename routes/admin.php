<?php

use App\Http\Middleware\HasAccessAdmin;
use App\Http\Middleware\Admin\HandleInertiaAdminRequests;
use Inertia\Inertia;
use App\Http\Controllers\Admin\DashboardController;
Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth', HasAccessAdmin::class, HandleInertiaAdminRequests::class],
    'as' => 'admin.',
], function () {
    /*Route::get('/',[DashboardController::class,'index']
       // return Inertia::render('Admin/Dashboard');
    )->name('dashboard');*/
      Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');

    Route::resource('district', 'DistrictController');
    Route::resource('organization', 'OrganizationController');
    Route::resource('service', 'ServiceController');
    Route::resource('report', 'ReportController');

    Route::resource('permission', 'PermissionController');
    Route::resource('menu', 'MenuController')->except([
        'show',
    ]);
    Route::resource('menu.item', 'MenuItemController')->except([
        'show',
    ]);
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', 'CategoryTypeController')->except([
            'show',
        ]);
        Route::resource('type.item', 'CategoryController');
    });
    Route::resource('media', 'MediaController');
    Route::get('edit-account-info', 'UserController@accountInfo')->name('account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('account.password.store');

    //add area to district
      Route::get('addArea/{id}','DistrictController@addArea')->name('district.addArea');
      Route::post('addAreaToDistrict','DistrictController@addAreaToDistrict')->name('district.storeArea');
      Route::get('editDistrictTA/{id}','DistrictController@editDistrictTA')->name('district.ta.edit');
      Route::put('updateDistrictTA','DistrictController@updateDistrictTA')->name('district.ta.update');
      Route::get('getDistrictTA/{id?}','DistrictController@getDistrictTA')->name('tas');
      Route::get('getDistrictOrganization/{id?}','DistrictController@getDistrictOrganization')->name('getDistrictOrganization');
      Route::get('getBeneficiaryType/{id?}','ServiceController@getBeneficiaryType')->name('beneficiaryType');
      Route::get('addServiceToOrganization/{id}','OrganizationController@addService')->name('organization.addService');
      Route::post('storeOrganizationService','OrganizationController@storeOrganizationService')->name('organization.storeService');
      Route::get('getDistrictId/{id?}','OrganizationController@getDistrictId')->name('getDistrictId');
      Route::get('getDashboardData/{id?}','DashboardController@getDashboardData')->name('getDashboardData');
});
