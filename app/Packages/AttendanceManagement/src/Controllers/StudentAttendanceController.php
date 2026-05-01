<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\StudentAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

class StudentAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentAttendance::with(['student', 'batch', 'markedBy']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $items = $query->orderByDesc('date')->paginate(20)->withQueryString();

        return view('attendance-management::student-attendance.index', compact('items'));
    }

    public function create(): View
    {
        return view('attendance-management::student-attendance.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
            'batch_id'   => 'nullable|integer',
            'remarks'    => 'nullable|string',
            'marked_by'  => 'nullable|integer',
        ]);

        $attendance = StudentAttendance::create($validated);

        Event::dispatch('webhook.attendance.marked', [
            'id'         => $attendance->attendance_id,
            'type'       => 'student',
            'student_id' => $attendance->student_id,
            'date'       => $attendance->date,
            'status'     => $attendance->status,
        ]);

        return redirect()->route('studentAttendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function show(StudentAttendance $studentAttendance): View
    {
        $studentAttendance->load(['student', 'batch', 'markedBy']);

        return view('attendance-management::student-attendance.show', compact('studentAttendance'));
    }

    public function edit(StudentAttendance $studentAttendance): View
    {
        return view('attendance-management::student-attendance.edit', compact('studentAttendance'));
    }

    public function update(Request $request, StudentAttendance $studentAttendance): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
            'batch_id'   => 'nullable|integer',
            'remarks'    => 'nullable|string',
            'marked_by'  => 'nullable|integer',
        ]);

        $studentAttendance->update($validated);

        return redirect()->route('studentAttendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(StudentAttendance $studentAttendance): RedirectResponse
    {
        $studentAttendance->delete();

        return redirect()->route('studentAttendance.index')
            ->with('success', 'Attendance record deleted.');
    }
}
