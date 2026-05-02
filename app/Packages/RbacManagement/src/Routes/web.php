<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-rbac')->group(function () {
    Route::get('roles', 'RoleController@index')->name('roles.index');
    Route::get('roles/{role}', 'RoleController@show')->name('roles.show')->whereNumber('role');
    Route::get('permissions', 'PermissionController@index')->name('permissions.index');
    Route::get('permissions/{permission}', 'PermissionController@show')->name('permissions.show')->whereNumber('permission');
    Route::get('role-permissions', 'RolePermissionController@index')->name('role-permissions.index');
    Route::get('role-permissions/{role}/edit', 'RolePermissionController@edit')->name('role-permissions.edit');
    Route::get('user-roles', 'UserRoleController@index')->name('user-roles.index');
    Route::get('user-roles/{user}/edit', 'UserRoleController@edit')->name('user-roles.edit');
    Route::get('user-permissions/{user}/edit', 'UserPermissionController@edit')->name('user-permissions.edit');
});

Route::middleware('permission:create-rbac')->group(function () {
    Route::get('roles/create', 'RoleController@create')->name('roles.create');
    Route::post('roles', 'RoleController@store')->name('roles.store');
    Route::get('permissions/create', 'PermissionController@create')->name('permissions.create');
    Route::post('permissions', 'PermissionController@store')->name('permissions.store');
});

Route::middleware('permission:edit-rbac')->group(function () {
    Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
    Route::put('roles/{role}', 'RoleController@update')->name('roles.update');
    Route::patch('roles/{role}', 'RoleController@update');
    Route::post('roles/{role}/sync-permissions', 'RoleController@syncPermissions')->name('roles.sync-permissions');
    Route::get('permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit');
    Route::put('permissions/{permission}', 'PermissionController@update')->name('permissions.update');
    Route::patch('permissions/{permission}', 'PermissionController@update');
    Route::put('role-permissions/{role}', 'RolePermissionController@update')->name('role-permissions.update');
    Route::put('user-roles/{user}', 'UserRoleController@update')->name('user-roles.update');
    Route::put('user-permissions/{user}', 'UserPermissionController@update')->name('user-permissions.update');
});

Route::middleware('permission:delete-rbac')->group(function () {
    Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy');
    Route::delete('permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy');
});
