<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Period;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class PeriodApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Period::orderBy('start_time')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $item = Period::create($validated);

        return response()->json($item, 201);
    }

    public function show(Period $period): JsonResponse
    {
        return response()->json($period);
    }

    public function update(Request $request, Period $period): JsonResponse
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $period->update($validated);

        return response()->json($period);
    }

    public function destroy(Period $period): JsonResponse
    {
        $period->delete();

        return response()->json(['message' => 'Period deleted']);
    }
}
