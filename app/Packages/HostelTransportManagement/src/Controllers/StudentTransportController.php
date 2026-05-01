<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\StudentTransport;
use App\Packages\HostelTransportManagement\Models\Transportation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentTransportController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentTransport::with('transportation');

        if ($request->filled('transport_id')) {
            $query->where('transport_id', $request->transport_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('leave_date');
            } else {
                $query->whereNotNull('leave_date');
            }
        }

        $items      = $query->orderByDesc('assigned_date')->paginate(15)->withQueryString();
        $transports = Transportation::orderBy('transport_name')->get();

        return view('hostel-transport::student-transport.index', compact('items', 'transports'));
    }

    public function create(): View
    {
        $transports = Transportation::orderBy('transport_name')->get();

        return view('hostel-transport::student-transport.create', compact('transports'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'      => 'required|integer',
            'transport_id'    => 'required|integer|exists:transportation,transport_id',
            'pickup_location' => 'nullable|string|max:255',
            'drop_location'   => 'nullable|string|max:255',
            'assigned_date'   => 'required|date',
            'leave_date'      => 'nullable|date|after_or_equal:assigned_date',
        ]);

        StudentTransport::create($validated);

        return redirect()->route('student-transport.index')
            ->with('success', 'Student assigned to transport successfully.');
    }

    public function show(StudentTransport $studentTransport): View
    {
        $studentTransport->load('transportation');

        return view('hostel-transport::student-transport.show', compact('studentTransport'));
    }

    public function edit(StudentTransport $studentTransport): View
    {
        $transports = Transportation::orderBy('transport_name')->get();

        return view('hostel-transport::student-transport.edit', compact('studentTransport', 'transports'));
    }

    public function update(Request $request, StudentTransport $studentTransport): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'      => 'required|integer',
            'transport_id'    => 'required|integer|exists:transportation,transport_id',
            'pickup_location' => 'nullable|string|max:255',
            'drop_location'   => 'nullable|string|max:255',
            'assigned_date'   => 'required|date',
            'leave_date'      => 'nullable|date|after_or_equal:assigned_date',
        ]);

        $studentTransport->update($validated);

        return redirect()->route('student-transport.index')
            ->with('success', 'Transport assignment updated successfully.');
    }

    public function destroy(StudentTransport $studentTransport): RedirectResponse
    {
        $studentTransport->delete();

        return redirect()->route('student-transport.index')
            ->with('success', 'Transport assignment removed.');
    }
}
