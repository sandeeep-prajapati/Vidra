<?php

use Illuminate\Support\Facades\Route;

// create routes must be registered before /{id} wildcard routes
Route::middleware('permission:create-attendance')->group(function () {
    Route::get('studentAttendance/create', 'StudentAttendanceController@create')->name('studentAttendance.create');
    Route::post('studentAttendance', 'StudentAttendanceController@store')->name('studentAttendance.store');
    Route::get('teacherAttendance/create', 'TeacherAttendanceController@create')->name('teacherAttendance.create');
    Route::post('teacherAttendance', 'TeacherAttendanceController@store')->name('teacherAttendance.store');
    Route::get('studentLeaveRequest/create', 'StudentLeaveRequestController@create')->name('studentLeaveRequest.create');
    Route::post('studentLeaveRequest', 'StudentLeaveRequestController@store')->name('studentLeaveRequest.store');
    Route::get('teacherLeaveRequest/create', 'TeacherLeaveRequestController@create')->name('teacherLeaveRequest.create');
    Route::post('teacherLeaveRequest', 'TeacherLeaveRequestController@store')->name('teacherLeaveRequest.store');
    Route::get('holiday/create', 'HolidayController@create')->name('holiday.create');
    Route::post('holiday', 'HolidayController@store')->name('holiday.store');
});

Route::middleware('permission:view-attendance')->group(function () {
    Route::get('attendance-overview', 'AttendanceDashboardController@index')->name('attendance.overview');
    Route::get('studentAttendance', 'StudentAttendanceController@index')->name('studentAttendance.index');
    Route::get('studentAttendance/{studentAttendance}', 'StudentAttendanceController@show')->name('studentAttendance.show');
    Route::get('teacherAttendance', 'TeacherAttendanceController@index')->name('teacherAttendance.index');
    Route::get('teacherAttendance/{teacherAttendance}', 'TeacherAttendanceController@show')->name('teacherAttendance.show');
    Route::get('studentLeaveRequest', 'StudentLeaveRequestController@index')->name('studentLeaveRequest.index');
    Route::get('studentLeaveRequest/{studentLeaveRequest}', 'StudentLeaveRequestController@show')->name('studentLeaveRequest.show');
    Route::get('teacherLeaveRequest', 'TeacherLeaveRequestController@index')->name('teacherLeaveRequest.index');
    Route::get('teacherLeaveRequest/{teacherLeaveRequest}', 'TeacherLeaveRequestController@show')->name('teacherLeaveRequest.show');
    Route::get('holiday', 'HolidayController@index')->name('holiday.index');
    Route::get('holiday/{holiday}', 'HolidayController@show')->name('holiday.show');
});

Route::middleware('permission:edit-attendance')->group(function () {
    Route::get('studentAttendance/{studentAttendance}/edit', 'StudentAttendanceController@edit')->name('studentAttendance.edit');
    Route::put('studentAttendance/{studentAttendance}', 'StudentAttendanceController@update')->name('studentAttendance.update');
    Route::patch('studentAttendance/{studentAttendance}', 'StudentAttendanceController@update');
    Route::get('teacherAttendance/{teacherAttendance}/edit', 'TeacherAttendanceController@edit')->name('teacherAttendance.edit');
    Route::put('teacherAttendance/{teacherAttendance}', 'TeacherAttendanceController@update')->name('teacherAttendance.update');
    Route::patch('teacherAttendance/{teacherAttendance}', 'TeacherAttendanceController@update');
    Route::get('studentLeaveRequest/{studentLeaveRequest}/edit', 'StudentLeaveRequestController@edit')->name('studentLeaveRequest.edit');
    Route::put('studentLeaveRequest/{studentLeaveRequest}', 'StudentLeaveRequestController@update')->name('studentLeaveRequest.update');
    Route::patch('studentLeaveRequest/{studentLeaveRequest}', 'StudentLeaveRequestController@update');
    Route::get('teacherLeaveRequest/{teacherLeaveRequest}/edit', 'TeacherLeaveRequestController@edit')->name('teacherLeaveRequest.edit');
    Route::put('teacherLeaveRequest/{teacherLeaveRequest}', 'TeacherLeaveRequestController@update')->name('teacherLeaveRequest.update');
    Route::patch('teacherLeaveRequest/{teacherLeaveRequest}', 'TeacherLeaveRequestController@update');
    Route::get('holiday/{holiday}/edit', 'HolidayController@edit')->name('holiday.edit');
    Route::put('holiday/{holiday}', 'HolidayController@update')->name('holiday.update');
    Route::patch('holiday/{holiday}', 'HolidayController@update');
});

Route::middleware('permission:delete-attendance')->group(function () {
    Route::delete('studentAttendance/{studentAttendance}', 'StudentAttendanceController@destroy')->name('studentAttendance.destroy');
    Route::delete('teacherAttendance/{teacherAttendance}', 'TeacherAttendanceController@destroy')->name('teacherAttendance.destroy');
    Route::delete('studentLeaveRequest/{studentLeaveRequest}', 'StudentLeaveRequestController@destroy')->name('studentLeaveRequest.destroy');
    Route::delete('teacherLeaveRequest/{teacherLeaveRequest}', 'TeacherLeaveRequestController@destroy')->name('teacherLeaveRequest.destroy');
    Route::delete('holiday/{holiday}', 'HolidayController@destroy')->name('holiday.destroy');
});
