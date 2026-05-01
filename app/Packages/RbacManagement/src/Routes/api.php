<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('roles', 'Api\\RoleApiController');
Route::put('roles/{role}/sync-permissions', 'Api\\RoleApiController@syncPermissions')->name('api.roles.sync-permissions');

Route::apiResource('permissions', 'Api\\PermissionApiController');

Route::get('role-permissions', 'Api\\RolePermissionApiController@index');
Route::get('role-permissions/{role}', 'Api\\RolePermissionApiController@show');
Route::put('role-permissions/{role}', 'Api\\RolePermissionApiController@update');

Route::get('user-roles', 'Api\\UserRoleApiController@index');
Route::get('user-roles/{user}', 'Api\\UserRoleApiController@show');
Route::put('user-roles/{user}', 'Api\\UserRoleApiController@update');

Route::get('user-permissions/{user}', 'Api\\UserPermissionApiController@show');
Route::put('user-permissions/{user}', 'Api\\UserPermissionApiController@update');
