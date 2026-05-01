<?php

use App\Packages\ClassManagement\Controllers\Api\AcademicYearApiController;
use App\Packages\ClassManagement\Controllers\Api\BatchApiController;
use App\Packages\ClassManagement\Controllers\Api\ClassApiController;
use App\Packages\ClassManagement\Controllers\Api\SectionApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('classes', ClassApiController::class)->parameters(['classes' => 'schoolClass']);
Route::apiResource('sections', SectionApiController::class);
Route::apiResource('batches', BatchApiController::class);
Route::apiResource('academic-years', AcademicYearApiController::class);
