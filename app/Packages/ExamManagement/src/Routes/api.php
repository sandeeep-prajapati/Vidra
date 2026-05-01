<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('exam', 'Api\\ExamApiController');
Route::apiResource('examSchedule', 'Api\\ExamScheduleApiController');
Route::apiResource('gradingScheme', 'Api\\GradingSchemeApiController');
Route::apiResource('studentMark', 'Api\\StudentMarkApiController');
Route::apiResource('studentReportCard', 'Api\\StudentReportCardApiController');
