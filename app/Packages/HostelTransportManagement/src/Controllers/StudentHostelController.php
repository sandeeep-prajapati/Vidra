<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Hostel;
use App\Packages\HostelTransportManagement\Models\Room;
use App\Packages\HostelTransportManagement\Models\StudentHostel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentHostelController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentHostel::with(['hostel', 'room']);

        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('checkout_date');
            } else {
                $query->whereNotNull('checkout_date');
            }
        }

        $items   = $query->orderByDesc('assigned_date')->paginate(15)->withQueryString();
        $hostels = Hostel::orderBy('hostel_name')->get();

        return view('hostel-transport::student-hostel.index', compact('items', 'hostels'));
    }

    public function create(): View
    {
        $hostels = Hostel::orderBy('hostel_name')->get();
        $rooms   = Room::with('hostel')->orderBy('room_number')->get();

        return view('hostel-transport::student-hostel.create', compact('hostels', 'rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'    => 'required|integer',
            'hostel_id'     => 'required|integer|exists:hostels,hostel_id',
            'room_id'       => 'required|integer|exists:hostel_rooms,room_id',
            'assigned_date' => 'required|date',
            'checkout_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        StudentHostel::create($validated);

        return redirect()->route('student-hostels.index')
            ->with('success', 'Student assigned to hostel successfully.');
    }

    public function show(StudentHostel $studentHostel): View
    {
        $studentHostel->load(['hostel', 'room']);

        return view('hostel-transport::student-hostel.show', compact('studentHostel'));
    }

    public function edit(StudentHostel $studentHostel): View
    {
        $hostels = Hostel::orderBy('hostel_name')->get();
        $rooms   = Room::with('hostel')->orderBy('room_number')->get();

        return view('hostel-transport::student-hostel.edit', compact('studentHostel', 'hostels', 'rooms'));
    }

    public function update(Request $request, StudentHostel $studentHostel): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'    => 'required|integer',
            'hostel_id'     => 'required|integer|exists:hostels,hostel_id',
            'room_id'       => 'required|integer|exists:hostel_rooms,room_id',
            'assigned_date' => 'required|date',
            'checkout_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        $studentHostel->update($validated);

        return redirect()->route('student-hostels.index')
            ->with('success', 'Hostel assignment updated successfully.');
    }

    public function destroy(StudentHostel $studentHostel): RedirectResponse
    {
        $studentHostel->delete();

        return redirect()->route('student-hostels.index')
            ->with('success', 'Hostel assignment removed.');
    }
}
