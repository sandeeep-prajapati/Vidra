<?php

namespace App\Packages\AttendanceManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\TeacherAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Attendance Management
 */
class TeacherAttendanceApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TeacherAttendance::with('staff');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderByDesc('date')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'date'     => 'required|date',
            'status'   => 'required|in:Present,Absent,Leave',
            'remarks'  => 'nullable|string',
        ]);

        $item = TeacherAttendance::create($validated);

        return response()->json($item, 201);
    }

    public function show(TeacherAttendance $teacherAttendance): JsonResponse
    {
        return response()->json($teacherAttendance->load('staff'));
    }

    public function update(Request $request, TeacherAttendance $teacherAttendance): JsonResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'date'     => 'required|date',
            'status'   => 'required|in:Present,Absent,Leave',
            'remarks'  => 'nullable|string',
        ]);

        $teacherAttendance->update($validated);

        return response()->json($teacherAttendance);
    }

    public function destroy(TeacherAttendance $teacherAttendance): JsonResponse
    {
        $teacherAttendance->delete();

        return response()->json(['message' => 'Teacher attendance record deleted.']);
    }
}
