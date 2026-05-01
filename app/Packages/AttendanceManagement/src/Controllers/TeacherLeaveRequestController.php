<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\TeacherLeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherLeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeacherLeaveRequest::with(['staff', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $items = $query->orderByDesc('applied_on')->paginate(20)->withQueryString();

        return view('attendance-management::teacher-leave-request.index', compact('items'));
    }

    public function create(): View
    {
        return view('attendance-management::teacher-leave-request.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id'    => 'required|integer',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
            'applied_on'  => 'required|date',
            'approved_by' => 'nullable|integer',
        ]);

        TeacherLeaveRequest::create($validated);

        return redirect()->route('teacherLeaveRequest.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show(TeacherLeaveRequest $teacherLeaveRequest): View
    {
        $teacherLeaveRequest->load(['staff', 'approvedBy']);

        return view('attendance-management::teacher-leave-request.show', compact('teacherLeaveRequest'));
    }

    public function edit(TeacherLeaveRequest $teacherLeaveRequest): View
    {
        return view('attendance-management::teacher-leave-request.edit', compact('teacherLeaveRequest'));
    }

    public function update(Request $request, TeacherLeaveRequest $teacherLeaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id'    => 'required|integer',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
            'applied_on'  => 'required|date',
            'approved_by' => 'nullable|integer',
        ]);

        $teacherLeaveRequest->update($validated);

        return redirect()->route('teacherLeaveRequest.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(TeacherLeaveRequest $teacherLeaveRequest): RedirectResponse
    {
        $teacherLeaveRequest->delete();

        return redirect()->route('teacherLeaveRequest.index')
            ->with('success', 'Leave request deleted.');
    }
}
