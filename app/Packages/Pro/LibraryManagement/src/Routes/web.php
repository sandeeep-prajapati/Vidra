<?php

use App\Packages\Pro\LibraryManagement\Controllers\Controllers\LibraryManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('library-management')->group(function () {
        Route::get('/', [LibraryManagementController::class, 'index'])
            ->middleware('permission:view_library-management')
            ->name('library-management.index');

        Route::post('/', [LibraryManagementController::class, 'store'])
            ->middleware('permission:create_library-management_item')
            ->name('library-management.store');

        Route::get('/{id}/edit', [LibraryManagementController::class, 'edit'])
            ->middleware('permission:edit_library-management_item')
            ->name('library-management.edit');

        Route::put('/{id}', [LibraryManagementController::class, 'update'])
            ->middleware('permission:edit_library-management_item')
            ->name('library-management.update');

        Route::delete('/{id}', [LibraryManagementController::class, 'destroy'])
            ->middleware('permission:delete_library-management_item')
            ->name('library-management.destroy');
    });
});