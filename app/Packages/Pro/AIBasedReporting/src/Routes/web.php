<?php

use App\Packages\Pro\AIBasedReporting\Controllers\Controllers\AIBasedReportingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('a-i-based-reporting')->group(function () {
        Route::get('/', [AIBasedReportingController::class, 'index'])
            ->middleware('permission:view_a-i-based-reporting')
            ->name('a-i-based-reporting.index');

        Route::post('/', [AIBasedReportingController::class, 'store'])
            ->middleware('permission:create_a-i-based-reporting_item')
            ->name('a-i-based-reporting.store');

        Route::get('/{id}/edit', [AIBasedReportingController::class, 'edit'])
            ->middleware('permission:edit_a-i-based-reporting_item')
            ->name('a-i-based-reporting.edit');

        Route::put('/{id}', [AIBasedReportingController::class, 'update'])
            ->middleware('permission:edit_a-i-based-reporting_item')
            ->name('a-i-based-reporting.update');

        Route::delete('/{id}', [AIBasedReportingController::class, 'destroy'])
            ->middleware('permission:delete_a-i-based-reporting_item')
            ->name('a-i-based-reporting.destroy');
    });
});