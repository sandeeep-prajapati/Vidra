<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Day;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class DayApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Day::orderBy('day_id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'day_name' => 'required|string|max:20|unique:days,day_name',
        ]);

        $item = Day::create($validated);

        return response()->json($item, 201);
    }

    public function show(Day $day): JsonResponse
    {
        return response()->json($day);
    }

    public function update(Request $request, Day $day): JsonResponse
    {
        $validated = $request->validate([
            'day_name' => 'required|string|max:20|unique:days,day_name,' . $day->day_id . ',day_id',
        ]);

        $day->update($validated);

        return response()->json($day);
    }

    public function destroy(Day $day): JsonResponse
    {
        $day->delete();

        return response()->json(['message' => 'Day deleted']);
    }
}
