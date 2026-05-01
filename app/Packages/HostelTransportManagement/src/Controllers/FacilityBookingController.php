<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\FacilityBooking;
use App\Packages\HostelTransportManagement\Models\FacilityManagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityBookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = FacilityBooking::with('facility');

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $items      = $query->orderByDesc('booking_date')->paginate(15)->withQueryString();
        $facilities = FacilityManagement::orderBy('facility_name')->get();

        return view('hostel-transport::facility-booking.index', compact('items', 'facilities'));
    }

    public function create(): View
    {
        $facilities = FacilityManagement::orderBy('facility_name')->get();

        return view('hostel-transport::facility-booking.create', compact('facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'   => 'required|integer',
            'facility_id'  => 'required|integer|exists:facility_management,facility_id',
            'booking_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
        ]);

        FacilityBooking::create($validated);

        return redirect()->route('facility-bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function show(FacilityBooking $facilityBooking): View
    {
        $facilityBooking->load('facility');

        return view('hostel-transport::facility-booking.show', compact('facilityBooking'));
    }

    public function edit(FacilityBooking $facilityBooking): View
    {
        $facilities = FacilityManagement::orderBy('facility_name')->get();

        return view('hostel-transport::facility-booking.edit', compact('facilityBooking', 'facilities'));
    }

    public function update(Request $request, FacilityBooking $facilityBooking): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'   => 'required|integer',
            'facility_id'  => 'required|integer|exists:facility_management,facility_id',
            'booking_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
        ]);

        $facilityBooking->update($validated);

        return redirect()->route('facility-bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(FacilityBooking $facilityBooking): RedirectResponse
    {
        $facilityBooking->delete();

        return redirect()->route('facility-bookings.index')
            ->with('success', 'Booking deleted.');
    }
}
