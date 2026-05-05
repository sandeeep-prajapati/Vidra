<?php

use App\Packages\Pro\TestBundle\Controllers\Controllers\TestBundleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('test-bundle')->group(function () {
        Route::get('/', [TestBundleController::class, 'index'])
            ->middleware('permission:view_test-bundle')
            ->name('test-bundle.index');

        Route::post('/', [TestBundleController::class, 'store'])
            ->middleware('permission:create_test-bundle_item')
            ->name('test-bundle.store');

        Route::get('/{id}/edit', [TestBundleController::class, 'edit'])
            ->middleware('permission:edit_test-bundle_item')
            ->name('test-bundle.edit');

        Route::put('/{id}', [TestBundleController::class, 'update'])
            ->middleware('permission:edit_test-bundle_item')
            ->name('test-bundle.update');

        Route::delete('/{id}', [TestBundleController::class, 'destroy'])
            ->middleware('permission:delete_test-bundle_item')
            ->name('test-bundle.destroy');
    });
});