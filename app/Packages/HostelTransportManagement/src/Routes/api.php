<?php

use App\Packages\HostelTransportManagement\Controllers\Api\FacilityApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\FacilityBookingApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\HostelAllocationApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\HostelApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\HostelRoomApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\TransportAllocationApiController;
use App\Packages\HostelTransportManagement\Controllers\Api\TransportVehicleApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('hostels', HostelApiController::class);
Route::apiResource('hostel-rooms', HostelRoomApiController::class)->parameters(['hostel-rooms' => 'room']);
Route::apiResource('student-hostels', HostelAllocationApiController::class)->parameters(['student-hostels' => 'studentHostel']);
Route::apiResource('transportation', TransportVehicleApiController::class);
Route::apiResource('student-transport', TransportAllocationApiController::class)->parameters(['student-transport' => 'studentTransport']);
Route::apiResource('facilities', FacilityApiController::class)->parameters(['facilities' => 'facility']);
Route::apiResource('facility-bookings', FacilityBookingApiController::class)->parameters(['facility-bookings' => 'facilityBooking']);
