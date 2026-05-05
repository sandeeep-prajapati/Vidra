<?php

use App\Packages\Pro\DemoBundle\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

Route::prefix('demo')->group(function () {
    Route::get('/', [DemoController::class, 'index'])->name('demo.index');
    Route::get('/features', [DemoController::class, 'features'])->name('demo.features');
});
