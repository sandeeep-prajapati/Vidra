<?php

use App\Packages\BundleInstaller\Controllers\BundleInstallerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/bundle-installer', [BundleInstallerController::class, 'index'])->name('bundle-installer.index');
    Route::post('/bundle-installer/upload', [BundleInstallerController::class, 'upload'])->name('bundle-installer.upload');
    Route::post('/bundle-installer/install/{bundle}', [BundleInstallerController::class, 'install'])->name('bundle-installer.install');
    Route::delete('/bundle-installer/{bundle}', [BundleInstallerController::class, 'destroy'])->name('bundle-installer.destroy');
});
