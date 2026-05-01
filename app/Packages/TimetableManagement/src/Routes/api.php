<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('room', 'Api\\RoomApiController');
Route::apiResource('day', 'Api\\DayApiController');
Route::apiResource('period', 'Api\\PeriodApiController');
Route::apiResource('timetable', 'Api\\TimetableApiController');
Route::apiResource('substituteAssignment', 'Api\\SubstituteAssignmentApiController');
Route::apiResource('specialEvent', 'Api\\SpecialEventApiController');
