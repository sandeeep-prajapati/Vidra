<?php

use App\Packages\HostelTransportManagement\Controllers\FacilityBookingController;
use App\Packages\HostelTransportManagement\Controllers\FacilityManagementController;
use App\Packages\HostelTransportManagement\Controllers\HostelController;
use App\Packages\HostelTransportManagement\Controllers\RoomController;
use App\Packages\HostelTransportManagement\Controllers\StudentHostelController;
use App\Packages\HostelTransportManagement\Controllers\StudentTransportController;
use App\Packages\HostelTransportManagement\Controllers\TransportationController;
use Illuminate\Support\Facades\Route;

// Hostel routes
Route::middleware('permission:view-hostel')->group(function () {
    Route::get('hostels', [HostelController::class, 'index'])->name('hostels.index');
    Route::get('hostels/{hostel}', [HostelController::class, 'show'])->name('hostels.show');
    Route::get('hostel-rooms', [RoomController::class, 'index'])->name('hostel-rooms.index');
    Route::get('hostel-rooms/{room}', [RoomController::class, 'show'])->name('hostel-rooms.show');
    Route::get('student-hostels', [StudentHostelController::class, 'index'])->name('student-hostels.index');
    Route::get('student-hostels/{studentHostel}', [StudentHostelController::class, 'show'])->name('student-hostels.show');
    Route::get('facilities', [FacilityManagementController::class, 'index'])->name('facilities.index');
    Route::get('facilities/{facility}', [FacilityManagementController::class, 'show'])->name('facilities.show');
    Route::get('facility-bookings', [FacilityBookingController::class, 'index'])->name('facility-bookings.index');
    Route::get('facility-bookings/{facilityBooking}', [FacilityBookingController::class, 'show'])->name('facility-bookings.show');
});

Route::middleware('permission:create-hostel')->group(function () {
    Route::get('hostels/create', [HostelController::class, 'create'])->name('hostels.create');
    Route::post('hostels', [HostelController::class, 'store'])->name('hostels.store');
    Route::get('hostel-rooms/create', [RoomController::class, 'create'])->name('hostel-rooms.create');
    Route::post('hostel-rooms', [RoomController::class, 'store'])->name('hostel-rooms.store');
    Route::get('student-hostels/create', [StudentHostelController::class, 'create'])->name('student-hostels.create');
    Route::post('student-hostels', [StudentHostelController::class, 'store'])->name('student-hostels.store');
    Route::get('facilities/create', [FacilityManagementController::class, 'create'])->name('facilities.create');
    Route::post('facilities', [FacilityManagementController::class, 'store'])->name('facilities.store');
    Route::get('facility-bookings/create', [FacilityBookingController::class, 'create'])->name('facility-bookings.create');
    Route::post('facility-bookings', [FacilityBookingController::class, 'store'])->name('facility-bookings.store');
});

Route::middleware('permission:edit-hostel')->group(function () {
    Route::get('hostels/{hostel}/edit', [HostelController::class, 'edit'])->name('hostels.edit');
    Route::put('hostels/{hostel}', [HostelController::class, 'update'])->name('hostels.update');
    Route::patch('hostels/{hostel}', [HostelController::class, 'update']);
    Route::get('hostel-rooms/{room}/edit', [RoomController::class, 'edit'])->name('hostel-rooms.edit');
    Route::put('hostel-rooms/{room}', [RoomController::class, 'update'])->name('hostel-rooms.update');
    Route::patch('hostel-rooms/{room}', [RoomController::class, 'update']);
    Route::get('student-hostels/{studentHostel}/edit', [StudentHostelController::class, 'edit'])->name('student-hostels.edit');
    Route::put('student-hostels/{studentHostel}', [StudentHostelController::class, 'update'])->name('student-hostels.update');
    Route::patch('student-hostels/{studentHostel}', [StudentHostelController::class, 'update']);
    Route::get('facilities/{facility}/edit', [FacilityManagementController::class, 'edit'])->name('facilities.edit');
    Route::put('facilities/{facility}', [FacilityManagementController::class, 'update'])->name('facilities.update');
    Route::patch('facilities/{facility}', [FacilityManagementController::class, 'update']);
    Route::get('facility-bookings/{facilityBooking}/edit', [FacilityBookingController::class, 'edit'])->name('facility-bookings.edit');
    Route::put('facility-bookings/{facilityBooking}', [FacilityBookingController::class, 'update'])->name('facility-bookings.update');
    Route::patch('facility-bookings/{facilityBooking}', [FacilityBookingController::class, 'update']);
});

Route::middleware('permission:delete-hostel')->group(function () {
    Route::delete('hostels/{hostel}', [HostelController::class, 'destroy'])->name('hostels.destroy');
    Route::delete('hostel-rooms/{room}', [RoomController::class, 'destroy'])->name('hostel-rooms.destroy');
    Route::delete('student-hostels/{studentHostel}', [StudentHostelController::class, 'destroy'])->name('student-hostels.destroy');
    Route::delete('facilities/{facility}', [FacilityManagementController::class, 'destroy'])->name('facilities.destroy');
    Route::delete('facility-bookings/{facilityBooking}', [FacilityBookingController::class, 'destroy'])->name('facility-bookings.destroy');
});

// Transport routes
Route::middleware('permission:view-transport')->group(function () {
    Route::get('transportation', [TransportationController::class, 'index'])->name('transportation.index');
    Route::get('transportation/{transportation}', [TransportationController::class, 'show'])->name('transportation.show');
    Route::get('student-transport', [StudentTransportController::class, 'index'])->name('student-transport.index');
    Route::get('student-transport/{studentTransport}', [StudentTransportController::class, 'show'])->name('student-transport.show');
});

Route::middleware('permission:create-transport')->group(function () {
    Route::get('transportation/create', [TransportationController::class, 'create'])->name('transportation.create');
    Route::post('transportation', [TransportationController::class, 'store'])->name('transportation.store');
    Route::get('student-transport/create', [StudentTransportController::class, 'create'])->name('student-transport.create');
    Route::post('student-transport', [StudentTransportController::class, 'store'])->name('student-transport.store');
});

Route::middleware('permission:edit-transport')->group(function () {
    Route::get('transportation/{transportation}/edit', [TransportationController::class, 'edit'])->name('transportation.edit');
    Route::put('transportation/{transportation}', [TransportationController::class, 'update'])->name('transportation.update');
    Route::patch('transportation/{transportation}', [TransportationController::class, 'update']);
    Route::get('student-transport/{studentTransport}/edit', [StudentTransportController::class, 'edit'])->name('student-transport.edit');
    Route::put('student-transport/{studentTransport}', [StudentTransportController::class, 'update'])->name('student-transport.update');
    Route::patch('student-transport/{studentTransport}', [StudentTransportController::class, 'update']);
});

Route::middleware('permission:delete-transport')->group(function () {
    Route::delete('transportation/{transportation}', [TransportationController::class, 'destroy'])->name('transportation.destroy');
    Route::delete('student-transport/{studentTransport}', [StudentTransportController::class, 'destroy'])->name('student-transport.destroy');
});
