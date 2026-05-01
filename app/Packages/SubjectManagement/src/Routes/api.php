<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('subject', 'Api\\SubjectApiController');
Route::apiResource('classSubject', 'Api\\ClassSubjectApiController');
Route::apiResource('curriculum', 'Api\\CurriculumApiController');
Route::apiResource('lessonPlan', 'Api\\LessonPlanApiController');
Route::apiResource('textbook', 'Api\\TextbookApiController');
Route::apiResource('teacherSubjectMapping', 'Api\\TeacherSubjectMappingApiController');
