<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\StudentLeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentLeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentLeaveRequest::with(['student', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $items = $query->orderByDesc('applied_on')->paginate(20)->withQueryString();

        return view('attendance-management::student-leave-request.index', compact('items'));
    }

    public function create(): View
    {
        return view('attendance-management::student-leave-request.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'  => 'required|integer',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
            'applied_on'  => 'required|date',
            'approved_by' => 'nullable|integer',
        ]);

        StudentLeaveRequest::create($validated);

        return redirect()->route('studentLeaveRequest.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show(StudentLeaveRequest $studentLeaveRequest): View
    {
        $studentLeaveRequest->load(['student', 'approvedBy']);

        return view('attendance-management::student-leave-request.show', compact('studentLeaveRequest'));
    }

    public function edit(StudentLeaveRequest $studentLeaveRequest): View
    {
        return view('attendance-management::student-leave-request.edit', compact('studentLeaveRequest'));
    }

    public function update(Request $request, StudentLeaveRequest $studentLeaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'  => 'required|integer',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
            'applied_on'  => 'required|date',
            'approved_by' => 'nullable|integer',
        ]);

        $studentLeaveRequest->update($validated);

        return redirect()->route('studentLeaveRequest.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(StudentLeaveRequest $studentLeaveRequest): RedirectResponse
    {
        $studentLeaveRequest->delete();

        return redirect()->route('studentLeaveRequest.index')
            ->with('success', 'Leave request deleted.');
    }
}
