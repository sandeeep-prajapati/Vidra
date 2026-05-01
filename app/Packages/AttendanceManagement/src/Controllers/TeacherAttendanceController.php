<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\TeacherAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeacherAttendance::with('staff');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderByDesc('date')->paginate(20)->withQueryString();

        return view('attendance-management::teacher-attendance.index', compact('items'));
    }

    public function create(): View
    {
        return view('attendance-management::teacher-attendance.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'date'     => 'required|date',
            'status'   => 'required|in:Present,Absent,Leave',
            'remarks'  => 'nullable|string',
        ]);

        TeacherAttendance::create($validated);

        return redirect()->route('teacherAttendance.index')
            ->with('success', 'Teacher attendance recorded successfully.');
    }

    public function show(TeacherAttendance $teacherAttendance): View
    {
        $teacherAttendance->load('staff');

        return view('attendance-management::teacher-attendance.show', compact('teacherAttendance'));
    }

    public function edit(TeacherAttendance $teacherAttendance): View
    {
        return view('attendance-management::teacher-attendance.edit', compact('teacherAttendance'));
    }

    public function update(Request $request, TeacherAttendance $teacherAttendance): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'date'     => 'required|date',
            'status'   => 'required|in:Present,Absent,Leave',
            'remarks'  => 'nullable|string',
        ]);

        $teacherAttendance->update($validated);

        return redirect()->route('teacherAttendance.index')
            ->with('success', 'Teacher attendance updated successfully.');
    }

    public function destroy(TeacherAttendance $teacherAttendance): RedirectResponse
    {
        $teacherAttendance->delete();

        return redirect()->route('teacherAttendance.index')
            ->with('success', 'Teacher attendance record deleted.');
    }
}
