<?php

namespace App\Packages\AttendanceManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\StudentAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Attendance Management
 */
class StudentAttendanceApiController extends Controller
{
    public function index(Request $request): JsonResponse
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

        return response()->json($query->orderByDesc('date')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave',
            'batch_id'   => 'nullable|integer',
            'remarks'    => 'nullable|string',
            'marked_by'  => 'nullable|integer',
        ]);

        $item = StudentAttendance::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentAttendance $studentAttendance): JsonResponse
    {
        return response()->json($studentAttendance->load(['student', 'batch', 'markedBy']));
    }

    public function update(Request $request, StudentAttendance $studentAttendance): JsonResponse
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

        return response()->json($studentAttendance);
    }

    public function destroy(StudentAttendance $studentAttendance): JsonResponse
    {
        $studentAttendance->delete();

        return response()->json(['message' => 'Attendance record deleted.']);
    }
}
