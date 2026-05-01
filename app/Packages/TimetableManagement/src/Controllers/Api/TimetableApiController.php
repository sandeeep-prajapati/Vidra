<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Timetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class TimetableApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Timetable::with(['day', 'period', 'subject', 'teacher', 'room'])->paginate(50)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'required|integer|exists:academic_years,academic_year_id',
            'day_id'           => 'required|integer|exists:days,day_id',
            'period_id'        => 'required|integer|exists:periods,period_id',
            'subject_id'       => 'required|integer|exists:subjects,subject_id',
            'teacher_id'       => 'required|integer|exists:staff,staff_id',
            'room_id'          => 'required|integer|exists:rooms,room_id',
        ]);

        $item = Timetable::create($validated);

        return response()->json($item->load(['day', 'period', 'subject', 'teacher', 'room']), 201);
    }

    public function show(Timetable $timetable): JsonResponse
    {
        return response()->json($timetable->load(['day', 'period', 'subject', 'teacher', 'room']));
    }

    public function update(Request $request, Timetable $timetable): JsonResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'required|integer|exists:academic_years,academic_year_id',
            'day_id'           => 'required|integer|exists:days,day_id',
            'period_id'        => 'required|integer|exists:periods,period_id',
            'subject_id'       => 'required|integer|exists:subjects,subject_id',
            'teacher_id'       => 'required|integer|exists:staff,staff_id',
            'room_id'          => 'required|integer|exists:rooms,room_id',
        ]);

        $timetable->update($validated);

        return response()->json($timetable->load(['day', 'period', 'subject', 'teacher', 'room']));
    }

    public function destroy(Timetable $timetable): JsonResponse
    {
        $timetable->delete();

        return response()->json(['message' => 'Timetable entry deleted']);
    }
}
