<?php

namespace App\Packages\AttendanceManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\StudentLeaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Attendance Management
 */
class StudentLeaveRequestApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StudentLeaveRequest::with(['student', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        return response()->json($query->orderByDesc('applied_on')->paginate(20));
    }

    public function store(Request $request): JsonResponse
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

        $item = StudentLeaveRequest::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentLeaveRequest $studentLeaveRequest): JsonResponse
    {
        return response()->json($studentLeaveRequest->load(['student', 'approvedBy']));
    }

    public function update(Request $request, StudentLeaveRequest $studentLeaveRequest): JsonResponse
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

        return response()->json($studentLeaveRequest);
    }

    public function destroy(StudentLeaveRequest $studentLeaveRequest): JsonResponse
    {
        $studentLeaveRequest->delete();

        return response()->json(['message' => 'Leave request deleted.']);
    }
}
