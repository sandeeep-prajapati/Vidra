<?php

use App\Packages\StaffManagement\Controllers\Api\StaffApiController;
use Illuminate\Support\Facades\Route;

Route::get('staff/departments', [StaffApiController::class, 'departments']);
Route::post('staff/departments', [StaffApiController::class, 'storeDepartment']);
Route::apiResource('staff', StaffApiController::class)->names('api.staff');
Route::post('staff/{staff}/attendance', [StaffApiController::class, 'storeAttendance']);
Route::post('staff/{staff}/salary', [StaffApiController::class, 'storeSalary']);
Route::post('staff/{staff}/reviews', [StaffApiController::class, 'storeReview']);
Route::post('staff/{staff}/leave-requests', [StaffApiController::class, 'storeLeaveRequest']);
