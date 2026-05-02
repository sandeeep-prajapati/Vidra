<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-exams')->group(function () {
    Route::get('exam', 'ExamController@index')->name('exam.index');
    Route::get('exam/{exam}', 'ExamController@show')->name('exam.show')->whereNumber('exam');
    Route::get('examSchedule', 'ExamScheduleController@index')->name('examSchedule.index');
    Route::get('examSchedule/{examSchedule}', 'ExamScheduleController@show')->name('examSchedule.show')->whereNumber('examSchedule');
    Route::get('gradingScheme', 'GradingSchemeController@index')->name('gradingScheme.index');
    Route::get('gradingScheme/{gradingScheme}', 'GradingSchemeController@show')->name('gradingScheme.show')->whereNumber('gradingScheme');
    Route::get('studentMark', 'StudentMarkController@index')->name('studentMark.index');
    Route::get('studentMark/{studentMark}', 'StudentMarkController@show')->name('studentMark.show')->whereNumber('studentMark');
    Route::get('studentReportCard', 'StudentReportCardController@index')->name('studentReportCard.index');
    Route::get('studentReportCard/{studentReportCard}', 'StudentReportCardController@show')->name('studentReportCard.show')->whereNumber('studentReportCard');
});

Route::middleware('permission:create-exams')->group(function () {
    Route::get('exam/create', 'ExamController@create')->name('exam.create');
    Route::post('exam', 'ExamController@store')->name('exam.store');
    Route::get('examSchedule/create', 'ExamScheduleController@create')->name('examSchedule.create');
    Route::post('examSchedule', 'ExamScheduleController@store')->name('examSchedule.store');
    Route::get('gradingScheme/create', 'GradingSchemeController@create')->name('gradingScheme.create');
    Route::post('gradingScheme', 'GradingSchemeController@store')->name('gradingScheme.store');
    Route::get('studentMark/create', 'StudentMarkController@create')->name('studentMark.create');
    Route::post('studentMark', 'StudentMarkController@store')->name('studentMark.store');
    Route::get('studentReportCard/create', 'StudentReportCardController@create')->name('studentReportCard.create');
    Route::post('studentReportCard', 'StudentReportCardController@store')->name('studentReportCard.store');
});

Route::middleware('permission:edit-exams')->group(function () {
    Route::get('exam/{exam}/edit', 'ExamController@edit')->name('exam.edit');
    Route::put('exam/{exam}', 'ExamController@update')->name('exam.update');
    Route::patch('exam/{exam}', 'ExamController@update');
    Route::get('examSchedule/{examSchedule}/edit', 'ExamScheduleController@edit')->name('examSchedule.edit');
    Route::put('examSchedule/{examSchedule}', 'ExamScheduleController@update')->name('examSchedule.update');
    Route::patch('examSchedule/{examSchedule}', 'ExamScheduleController@update');
    Route::get('gradingScheme/{gradingScheme}/edit', 'GradingSchemeController@edit')->name('gradingScheme.edit');
    Route::put('gradingScheme/{gradingScheme}', 'GradingSchemeController@update')->name('gradingScheme.update');
    Route::patch('gradingScheme/{gradingScheme}', 'GradingSchemeController@update');
    Route::get('studentMark/{studentMark}/edit', 'StudentMarkController@edit')->name('studentMark.edit');
    Route::put('studentMark/{studentMark}', 'StudentMarkController@update')->name('studentMark.update');
    Route::patch('studentMark/{studentMark}', 'StudentMarkController@update');
    Route::get('studentReportCard/{studentReportCard}/edit', 'StudentReportCardController@edit')->name('studentReportCard.edit');
    Route::put('studentReportCard/{studentReportCard}', 'StudentReportCardController@update')->name('studentReportCard.update');
    Route::patch('studentReportCard/{studentReportCard}', 'StudentReportCardController@update');
});

Route::middleware('permission:delete-exams')->group(function () {
    Route::delete('exam/{exam}', 'ExamController@destroy')->name('exam.destroy');
    Route::delete('examSchedule/{examSchedule}', 'ExamScheduleController@destroy')->name('examSchedule.destroy');
    Route::delete('gradingScheme/{gradingScheme}', 'GradingSchemeController@destroy')->name('gradingScheme.destroy');
    Route::delete('studentMark/{studentMark}', 'StudentMarkController@destroy')->name('studentMark.destroy');
    Route::delete('studentReportCard/{studentReportCard}', 'StudentReportCardController@destroy')->name('studentReportCard.destroy');
});
