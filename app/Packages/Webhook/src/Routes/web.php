<?php

use App\Packages\Webhook\Controllers\WebhookLogsController;
use App\Packages\Webhook\Controllers\WebhookSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('webhook')->name('webhook.')->group(function () {

    // Settings
    Route::get('settings',         [WebhookSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings',        [WebhookSettingsController::class, 'store'])->name('settings.store');
    Route::get('settings/data',    [WebhookSettingsController::class, 'show'])->name('settings.data');

    // Logs
    Route::get('logs',             [WebhookLogsController::class, 'index'])->name('logs.index');
    Route::delete('logs/{id}',     [WebhookLogsController::class, 'destroy'])->name('logs.destroy');
    Route::post('logs/mass-delete',[WebhookLogsController::class, 'massDestroy'])->name('logs.mass_destroy');
});
