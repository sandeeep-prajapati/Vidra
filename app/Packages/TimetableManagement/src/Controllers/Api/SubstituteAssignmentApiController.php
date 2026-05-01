<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\SubstituteAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class SubstituteAssignmentApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            SubstituteAssignment::with(['timetable', 'originalTeacher', 'substituteTeacher'])
                ->orderBy('date_of_substitution', 'desc')
                ->paginate(50)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'timetable_id'          => 'required|integer|exists:timetables,timetable_id',
            'original_teacher_id'   => 'required|integer|exists:staff,staff_id',
            'substitute_teacher_id' => 'required|integer|exists:staff,staff_id|different:original_teacher_id',
            'date_of_substitution'  => 'required|date',
        ]);

        $item = SubstituteAssignment::create($validated);

        return response()->json($item, 201);
    }

    public function show(SubstituteAssignment $substituteAssignment): JsonResponse
    {
        return response()->json($substituteAssignment->load(['timetable', 'originalTeacher', 'substituteTeacher']));
    }

    public function update(Request $request, SubstituteAssignment $substituteAssignment): JsonResponse
    {
        $validated = $request->validate([
            'timetable_id'          => 'required|integer|exists:timetables,timetable_id',
            'original_teacher_id'   => 'required|integer|exists:staff,staff_id',
            'substitute_teacher_id' => 'required|integer|exists:staff,staff_id|different:original_teacher_id',
            'date_of_substitution'  => 'required|date',
        ]);

        $substituteAssignment->update($validated);

        return response()->json($substituteAssignment);
    }

    public function destroy(SubstituteAssignment $substituteAssignment): JsonResponse
    {
        $substituteAssignment->delete();

        return response()->json(['message' => 'Substitute assignment deleted']);
    }
}
