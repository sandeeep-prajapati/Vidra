<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\FacilityBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class FacilityBookingApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FacilityBooking::with('facility')->orderByDesc('booking_date')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id'   => 'required|integer',
            'facility_id'  => 'required|integer|exists:facility_management,facility_id',
            'booking_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
        ]);

        $item = FacilityBooking::create($validated);

        return response()->json($item, 201);
    }

    public function show(FacilityBooking $facilityBooking): JsonResponse
    {
        $facilityBooking->load('facility');

        return response()->json($facilityBooking);
    }

    public function update(Request $request, FacilityBooking $facilityBooking): JsonResponse
    {
        $validated = $request->validate([
            'student_id'   => 'sometimes|integer',
            'facility_id'  => 'sometimes|integer|exists:facility_management,facility_id',
            'booking_date' => 'sometimes|date',
            'start_time'   => 'sometimes|date_format:H:i',
            'end_time'     => 'sometimes|date_format:H:i',
        ]);

        $facilityBooking->update($validated);

        return response()->json($facilityBooking);
    }

    public function destroy(FacilityBooking $facilityBooking): JsonResponse
    {
        $facilityBooking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
