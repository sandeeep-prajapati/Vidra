<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-communication')->group(function () {
    Route::get('message', 'MessageController@index')->name('message.index');
    Route::get('message/{message}', 'MessageController@show')->name('message.show')->whereNumber('message');
    Route::get('messageRecipient', 'MessageRecipientController@index')->name('messageRecipient.index');
    Route::get('messageRecipient/{messageRecipient}', 'MessageRecipientController@show')->name('messageRecipient.show')->whereNumber('messageRecipient');
    Route::get('circular', 'CircularController@index')->name('circular.index');
    Route::get('circular/{circular}', 'CircularController@show')->name('circular.show')->whereNumber('circular');
    Route::get('notificationSetting', 'NotificationSettingController@index')->name('notificationSetting.index');
    Route::get('notificationSetting/{notificationSetting}', 'NotificationSettingController@show')->name('notificationSetting.show')->whereNumber('notificationSetting');
});

Route::middleware('permission:create-communication')->group(function () {
    Route::get('message/create', 'MessageController@create')->name('message.create');
    Route::post('message', 'MessageController@store')->name('message.store');
    Route::get('messageRecipient/create', 'MessageRecipientController@create')->name('messageRecipient.create');
    Route::post('messageRecipient', 'MessageRecipientController@store')->name('messageRecipient.store');
    Route::get('circular/create', 'CircularController@create')->name('circular.create');
    Route::post('circular', 'CircularController@store')->name('circular.store');
    Route::get('notificationSetting/create', 'NotificationSettingController@create')->name('notificationSetting.create');
    Route::post('notificationSetting', 'NotificationSettingController@store')->name('notificationSetting.store');
});

Route::middleware('permission:edit-communication')->group(function () {
    Route::get('message/{message}/edit', 'MessageController@edit')->name('message.edit');
    Route::put('message/{message}', 'MessageController@update')->name('message.update');
    Route::patch('message/{message}', 'MessageController@update');
    Route::get('messageRecipient/{messageRecipient}/edit', 'MessageRecipientController@edit')->name('messageRecipient.edit');
    Route::put('messageRecipient/{messageRecipient}', 'MessageRecipientController@update')->name('messageRecipient.update');
    Route::patch('messageRecipient/{messageRecipient}', 'MessageRecipientController@update');
    Route::get('circular/{circular}/edit', 'CircularController@edit')->name('circular.edit');
    Route::put('circular/{circular}', 'CircularController@update')->name('circular.update');
    Route::patch('circular/{circular}', 'CircularController@update');
    Route::get('notificationSetting/{notificationSetting}/edit', 'NotificationSettingController@edit')->name('notificationSetting.edit');
    Route::put('notificationSetting/{notificationSetting}', 'NotificationSettingController@update')->name('notificationSetting.update');
    Route::patch('notificationSetting/{notificationSetting}', 'NotificationSettingController@update');
});

Route::middleware('permission:delete-communication')->group(function () {
    Route::delete('message/{message}', 'MessageController@destroy')->name('message.destroy');
    Route::delete('messageRecipient/{messageRecipient}', 'MessageRecipientController@destroy')->name('messageRecipient.destroy');
    Route::delete('circular/{circular}', 'CircularController@destroy')->name('circular.destroy');
    Route::delete('notificationSetting/{notificationSetting}', 'NotificationSettingController@destroy')->name('notificationSetting.destroy');
});
