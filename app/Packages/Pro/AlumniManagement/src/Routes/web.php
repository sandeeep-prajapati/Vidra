<?php

use App\Packages\Pro\AlumniManagement\Controllers\AlumniController;
use App\Packages\Pro\AlumniManagement\Controllers\EventController;
use App\Packages\Pro\AlumniManagement\Controllers\DonationController;
use App\Packages\Pro\AlumniManagement\Controllers\MentorshipController;
use App\Packages\Pro\AlumniManagement\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('alumni')->group(function () {

    // Setup wizard
    Route::get('/setup', [SetupController::class, 'showSetup'])->name('alumni.setup');
    Route::post('/setup', [SetupController::class, 'runSetup'])->name('alumni.setup.run');

    Route::middleware('permission:view_alumni-management')->group(function () {
        // Directory
        Route::get('/directory', [AlumniController::class, 'directory'])->name('alumni.directory');

        // Alumni profiles
        Route::get('/', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/{alumni}', [AlumniController::class, 'show'])->name('alumni.show');

        // Events
        Route::get('/events', [EventController::class, 'index'])->name('alumni.events.index');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('alumni.events.show');
        Route::get('/events/{event}/registrations', [EventController::class, 'registrations'])->name('alumni.events.registrations');
        Route::post('/events/{event}/register', [EventController::class, 'register'])->name('alumni.events.register');

        // Mentorship viewing
        Route::get('/mentorship', [MentorshipController::class, 'index'])->name('alumni.mentorship.index');
        Route::get('/mentorship/{mentorship}', [MentorshipController::class, 'show'])->name('alumni.mentorship.show');
    });

    Route::middleware('permission:create_alumni-management_item')->group(function () {
        Route::get('/create', [AlumniController::class, 'create'])->name('alumni.create');
        Route::post('/', [AlumniController::class, 'store'])->name('alumni.store');
        Route::get('/events/create', [EventController::class, 'create'])->name('alumni.events.create');
        Route::post('/events', [EventController::class, 'store'])->name('alumni.events.store');
    });

    Route::middleware('permission:edit_alumni-management_item')->group(function () {
        Route::get('/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
        Route::put('/{alumni}', [AlumniController::class, 'update'])->name('alumni.update');
    });

    Route::middleware('permission:delete_alumni-management_item')->group(function () {
        Route::delete('/{alumni}', [AlumniController::class, 'destroy'])->name('alumni.destroy');
    });

    Route::middleware('permission:manage_alumni_donations')->group(function () {
        Route::get('/donations', [DonationController::class, 'index'])->name('alumni.donations.index');
        Route::get('/donations/create', [DonationController::class, 'create'])->name('alumni.donations.create');
        Route::post('/donations', [DonationController::class, 'store'])->name('alumni.donations.store');
        Route::post('/donations/{donation}/confirm', [DonationController::class, 'confirm'])->name('alumni.donations.confirm');
    });

    Route::middleware('permission:manage_alumni_mentorship')->group(function () {
        Route::get('/mentorship/create', [MentorshipController::class, 'create'])->name('alumni.mentorship.create');
        Route::post('/mentorship', [MentorshipController::class, 'store'])->name('alumni.mentorship.store');
        Route::post('/mentorship/{mentorship}/complete', [MentorshipController::class, 'complete'])->name('alumni.mentorship.complete');
    });
});
