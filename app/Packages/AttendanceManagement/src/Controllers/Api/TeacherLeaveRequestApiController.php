<?php

namespace App\Packages\AttendanceManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\TeacherLeaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Attendance Management
 */
class TeacherLeaveRequestApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TeacherLeaveRequest::with(['staff', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        return response()->json($query->orderByDesc('applied_on')->paginate(20));
    }

    public function store(Request $request): JsonResponse
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

        $item = TeacherLeaveRequest::create($validated);

        return response()->json($item, 201);
    }

    public function show(TeacherLeaveRequest $teacherLeaveRequest): JsonResponse
    {
        return response()->json($teacherLeaveRequest->load(['staff', 'approvedBy']));
    }

    public function update(Request $request, TeacherLeaveRequest $teacherLeaveRequest): JsonResponse
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

        return response()->json($teacherLeaveRequest);
    }

    public function destroy(TeacherLeaveRequest $teacherLeaveRequest): JsonResponse
    {
        $teacherLeaveRequest->delete();

        return response()->json(['message' => 'Leave request deleted.']);
    }
}
