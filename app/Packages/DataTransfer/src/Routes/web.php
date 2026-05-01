<?php

use Illuminate\Support\Facades\Route;
use App\Packages\DataTransfer\Http\Controllers\DataTransferController;

Route::middleware(['web', 'auth'])->prefix('data-transfer')->name('data-transfer.')->group(function () {
    Route::get('/', [DataTransferController::class, 'index'])->name('index');

    Route::post('/import', [DataTransferController::class, 'storeImport'])->name('import');
    Route::post('/export', [DataTransferController::class, 'storeExport'])->name('export');

    Route::get('/download/{trackId}', [DataTransferController::class, 'download'])->name('download');
    Route::get('/sample/{entityType}', [DataTransferController::class, 'sampleCsv'])->name('sample');
    Route::get('/status/{trackId}', [DataTransferController::class, 'trackStatus'])->name('status');
});
