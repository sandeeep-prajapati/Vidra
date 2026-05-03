<?php

use App\Packages\ContactBundle\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
});
