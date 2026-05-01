<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('studentAttendance', 'Api\\StudentAttendanceApiController');
Route::apiResource('teacherAttendance', 'Api\\TeacherAttendanceApiController');
Route::apiResource('studentLeaveRequest', 'Api\\StudentLeaveRequestApiController');
Route::apiResource('teacherLeaveRequest', 'Api\\TeacherLeaveRequestApiController');
Route::apiResource('holiday', 'Api\\HolidayApiController');
