<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('message', 'Api\\MessageApiController');
Route::apiResource('messageRecipient', 'Api\\MessageRecipientApiController');
Route::apiResource('circular', 'Api\\CircularApiController');
Route::apiResource('notificationSetting', 'Api\\NotificationSettingApiController');
