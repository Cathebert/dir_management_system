<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

// admin dashboard
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::macro('resource', function (string $name, string $title, ?string $parentName = null) {
    if ($parentName) {
        Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail, $model) use ($name, $title, $parentName) {
            $trail->parent("{$parentName}.show", $model);
            $trail->push($title, route("{$name}.index", $model));
        });

        Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.index", $model);
            $trail->push('Create', route("{$name}.create", $model));
        });

        Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model, $item) use ($name) {
            $trail->parent("{$name}.index", $model, $item);
            \Log::info("{$name}.show");
            if (Route::has("{$name}.show")) {
                $trail->push($item->name ?? $model, route("{$name}.show", [$model, $item]));
            } else {
                $trail->push($item->name ?? $model);
            }
        });

        Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model, $item) use ($name) {
            $trail->parent("{$name}.show", $model, $item);
            $trail->push('Edit', route("{$name}.edit", [$model, $item]));
        });

    } else {
        Breadcrumbs::for("{$name}.index", function (BreadcrumbTrail $trail) use ($name, $title) {
            $trail->parent('admin.dashboard');
            $trail->push($title, route("{$name}.index"));
        });

        Breadcrumbs::for("{$name}.create", function (BreadcrumbTrail $trail) use ($name) {
            $trail->parent("{$name}.index");
            $trail->push('Create', route("{$name}.create"));
        });

        Breadcrumbs::for("{$name}.show", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.index");
            if (Route::has("$name.show")) {
                $trail->push($model->name ?? $model, route("{$name}.show", $model));
            } else {
                $trail->push($model->name ?? $model);
            }
        });

        Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, $model) use ($name) {
            $trail->parent("{$name}.show", $model);
            $trail->push('Edit', route("{$name}.edit", $model));
        });
    }
});

Breadcrumbs::resource('admin.permission', 'Permissions');
Breadcrumbs::resource('admin.role', 'Roles');

Breadcrumbs::resource('admin.district', 'District');

Breadcrumbs::resource('admin.organization', 'Organization');
Breadcrumbs::resource('admin.service', 'Service');
Breadcrumbs::resource('admin.report', 'Reports');
Breadcrumbs::resource('admin.user', 'Users');
Breadcrumbs::resource('admin.media', 'Media');
Breadcrumbs::resource('admin.menu', 'Menu');
Breadcrumbs::resource('admin.menu.item', 'Menu Items', 'admin.menu');
Breadcrumbs::resource('admin.category.type', 'Category Types');
Breadcrumbs::resource('admin.category.type.item', 'Items', 'admin.category.type');

// admin account Info
Breadcrumbs::for('admin.account.info', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Account Info', route('admin.account.info'));
});

    Breadcrumbs::for('admin.district.addArea', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('Add Area', route('admin.district.addArea',[$id]));
});
   Breadcrumbs::for('admin.district.ta.edit', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('Add Area', route('admin.district.ta.edit',[$id]));
});
Breadcrumbs::for('admin.district.storeArea', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Store Area', route('admin.district.storeArea'));
});
Breadcrumbs::for('admin.tas', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('Store Area', route('admin.tas',[$id]));
});
Breadcrumbs::for('admin.beneficiaryType', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('BeneficiaryType', route('admin.beneficiaryType',[$id]));
});

Breadcrumbs::for('admin.getDistrictOrganization', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('BeneficiaryType', route('admin.getDistrictOrganization',[$id]));

});
Breadcrumbs::for('admin.organization.addService', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('Service', route('admin.organization.addService',[$id]));
});
Breadcrumbs::for('admin.getDistrictId', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('DistrictName', route('admin.getDistrictId',[$id]));
});

Breadcrumbs::for('admin.getDashboardData', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('admin.dashboard');
    $trail->push('Dashboard', route('admin.getDashboardData',[$id]));
});
