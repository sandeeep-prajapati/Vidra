<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-timetable')->group(function () {
    Route::get('room', 'RoomController@index')->name('room.index');
    Route::get('room/{room}', 'RoomController@show')->name('room.show');
    Route::get('day', 'DayController@index')->name('day.index');
    Route::get('day/{day}', 'DayController@show')->name('day.show');
    Route::get('period', 'PeriodController@index')->name('period.index');
    Route::get('period/{period}', 'PeriodController@show')->name('period.show');
    Route::get('timetable', 'TimetableController@index')->name('timetable.index');
    Route::get('timetable/{timetable}', 'TimetableController@show')->name('timetable.show');
    Route::get('substituteAssignment', 'SubstituteAssignmentController@index')->name('substituteAssignment.index');
    Route::get('substituteAssignment/{substituteAssignment}', 'SubstituteAssignmentController@show')->name('substituteAssignment.show');
    Route::get('specialEvent', 'SpecialEventController@index')->name('specialEvent.index');
    Route::get('specialEvent/{specialEvent}', 'SpecialEventController@show')->name('specialEvent.show');
});

Route::middleware('permission:create-timetable')->group(function () {
    Route::get('room/create', 'RoomController@create')->name('room.create');
    Route::post('room', 'RoomController@store')->name('room.store');
    Route::get('day/create', 'DayController@create')->name('day.create');
    Route::post('day', 'DayController@store')->name('day.store');
    Route::get('period/create', 'PeriodController@create')->name('period.create');
    Route::post('period', 'PeriodController@store')->name('period.store');
    Route::get('timetable/create', 'TimetableController@create')->name('timetable.create');
    Route::post('timetable', 'TimetableController@store')->name('timetable.store');
    Route::get('substituteAssignment/create', 'SubstituteAssignmentController@create')->name('substituteAssignment.create');
    Route::post('substituteAssignment', 'SubstituteAssignmentController@store')->name('substituteAssignment.store');
    Route::get('specialEvent/create', 'SpecialEventController@create')->name('specialEvent.create');
    Route::post('specialEvent', 'SpecialEventController@store')->name('specialEvent.store');
});

Route::middleware('permission:edit-timetable')->group(function () {
    Route::get('room/{room}/edit', 'RoomController@edit')->name('room.edit');
    Route::put('room/{room}', 'RoomController@update')->name('room.update');
    Route::patch('room/{room}', 'RoomController@update');
    Route::get('day/{day}/edit', 'DayController@edit')->name('day.edit');
    Route::put('day/{day}', 'DayController@update')->name('day.update');
    Route::patch('day/{day}', 'DayController@update');
    Route::get('period/{period}/edit', 'PeriodController@edit')->name('period.edit');
    Route::put('period/{period}', 'PeriodController@update')->name('period.update');
    Route::patch('period/{period}', 'PeriodController@update');
    Route::get('timetable/{timetable}/edit', 'TimetableController@edit')->name('timetable.edit');
    Route::put('timetable/{timetable}', 'TimetableController@update')->name('timetable.update');
    Route::patch('timetable/{timetable}', 'TimetableController@update');
    Route::get('substituteAssignment/{substituteAssignment}/edit', 'SubstituteAssignmentController@edit')->name('substituteAssignment.edit');
    Route::put('substituteAssignment/{substituteAssignment}', 'SubstituteAssignmentController@update')->name('substituteAssignment.update');
    Route::patch('substituteAssignment/{substituteAssignment}', 'SubstituteAssignmentController@update');
    Route::get('specialEvent/{specialEvent}/edit', 'SpecialEventController@edit')->name('specialEvent.edit');
    Route::put('specialEvent/{specialEvent}', 'SpecialEventController@update')->name('specialEvent.update');
    Route::patch('specialEvent/{specialEvent}', 'SpecialEventController@update');
});

Route::middleware('permission:delete-timetable')->group(function () {
    Route::delete('room/{room}', 'RoomController@destroy')->name('room.destroy');
    Route::delete('day/{day}', 'DayController@destroy')->name('day.destroy');
    Route::delete('period/{period}', 'PeriodController@destroy')->name('period.destroy');
    Route::delete('timetable/{timetable}', 'TimetableController@destroy')->name('timetable.destroy');
    Route::delete('substituteAssignment/{substituteAssignment}', 'SubstituteAssignmentController@destroy')->name('substituteAssignment.destroy');
    Route::delete('specialEvent/{specialEvent}', 'SpecialEventController@destroy')->name('specialEvent.destroy');
});
