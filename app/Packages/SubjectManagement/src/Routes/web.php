<?php

use Illuminate\Support\Facades\Route;

// create routes must be registered before /{id} wildcard routes to prevent
// Laravel matching /create as a model ID parameter
Route::middleware('permission:create-subjects')->group(function () {
    Route::get('subjects/create', 'SubjectController@create')->name('subjects.create');
    Route::post('subjects', 'SubjectController@store')->name('subjects.store');
    Route::get('class-subjects/create', 'ClassSubjectController@create')->name('class-subjects.create');
    Route::post('class-subjects', 'ClassSubjectController@store')->name('class-subjects.store');
    Route::get('curriculums/create', 'CurriculumController@create')->name('curriculums.create');
    Route::post('curriculums', 'CurriculumController@store')->name('curriculums.store');
    Route::get('lesson-plans/create', 'LessonPlanController@create')->name('lesson-plans.create');
    Route::post('lesson-plans', 'LessonPlanController@store')->name('lesson-plans.store');
    Route::get('textbooks/create', 'TextbookController@create')->name('textbooks.create');
    Route::post('textbooks', 'TextbookController@store')->name('textbooks.store');
    Route::get('teacher-subject-mappings/create', 'TeacherSubjectMappingController@create')->name('teacher-subject-mappings.create');
    Route::post('teacher-subject-mappings', 'TeacherSubjectMappingController@store')->name('teacher-subject-mappings.store');
});

Route::middleware('permission:view-subjects')->group(function () {
    Route::get('subjects', 'SubjectController@index')->name('subjects.index');
    Route::get('subjects/{subject}', 'SubjectController@show')->name('subjects.show');
    Route::get('class-subjects', 'ClassSubjectController@index')->name('class-subjects.index');
    Route::get('class-subjects/{class_subject}', 'ClassSubjectController@show')->name('class-subjects.show');
    Route::get('curriculums', 'CurriculumController@index')->name('curriculums.index');
    Route::get('curriculums/{curriculum}', 'CurriculumController@show')->name('curriculums.show');
    Route::get('lesson-plans', 'LessonPlanController@index')->name('lesson-plans.index');
    Route::get('lesson-plans/{lesson_plan}', 'LessonPlanController@show')->name('lesson-plans.show');
    Route::get('textbooks', 'TextbookController@index')->name('textbooks.index');
    Route::get('textbooks/{textbook}', 'TextbookController@show')->name('textbooks.show');
    Route::get('teacher-subject-mappings', 'TeacherSubjectMappingController@index')->name('teacher-subject-mappings.index');
    Route::get('teacher-subject-mappings/{teacher_subject_mapping}', 'TeacherSubjectMappingController@show')->name('teacher-subject-mappings.show');
});

Route::middleware('permission:edit-subjects')->group(function () {
    Route::get('subjects/{subject}/edit', 'SubjectController@edit')->name('subjects.edit');
    Route::put('subjects/{subject}', 'SubjectController@update')->name('subjects.update');
    Route::patch('subjects/{subject}', 'SubjectController@update');
    Route::get('class-subjects/{class_subject}/edit', 'ClassSubjectController@edit')->name('class-subjects.edit');
    Route::put('class-subjects/{class_subject}', 'ClassSubjectController@update')->name('class-subjects.update');
    Route::patch('class-subjects/{class_subject}', 'ClassSubjectController@update');
    Route::get('curriculums/{curriculum}/edit', 'CurriculumController@edit')->name('curriculums.edit');
    Route::put('curriculums/{curriculum}', 'CurriculumController@update')->name('curriculums.update');
    Route::patch('curriculums/{curriculum}', 'CurriculumController@update');
    Route::get('lesson-plans/{lesson_plan}/edit', 'LessonPlanController@edit')->name('lesson-plans.edit');
    Route::put('lesson-plans/{lesson_plan}', 'LessonPlanController@update')->name('lesson-plans.update');
    Route::patch('lesson-plans/{lesson_plan}', 'LessonPlanController@update');
    Route::get('textbooks/{textbook}/edit', 'TextbookController@edit')->name('textbooks.edit');
    Route::put('textbooks/{textbook}', 'TextbookController@update')->name('textbooks.update');
    Route::patch('textbooks/{textbook}', 'TextbookController@update');
    Route::get('teacher-subject-mappings/{teacher_subject_mapping}/edit', 'TeacherSubjectMappingController@edit')->name('teacher-subject-mappings.edit');
    Route::put('teacher-subject-mappings/{teacher_subject_mapping}', 'TeacherSubjectMappingController@update')->name('teacher-subject-mappings.update');
    Route::patch('teacher-subject-mappings/{teacher_subject_mapping}', 'TeacherSubjectMappingController@update');
});

Route::middleware('permission:delete-subjects')->group(function () {
    Route::delete('subjects/{subject}', 'SubjectController@destroy')->name('subjects.destroy');
    Route::delete('class-subjects/{class_subject}', 'ClassSubjectController@destroy')->name('class-subjects.destroy');
    Route::delete('curriculums/{curriculum}', 'CurriculumController@destroy')->name('curriculums.destroy');
    Route::delete('lesson-plans/{lesson_plan}', 'LessonPlanController@destroy')->name('lesson-plans.destroy');
    Route::delete('textbooks/{textbook}', 'TextbookController@destroy')->name('textbooks.destroy');
    Route::delete('teacher-subject-mappings/{teacher_subject_mapping}', 'TeacherSubjectMappingController@destroy')->name('teacher-subject-mappings.destroy');
});
