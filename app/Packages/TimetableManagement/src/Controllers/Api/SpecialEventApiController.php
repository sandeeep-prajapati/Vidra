<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\SpecialEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class SpecialEventApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            SpecialEvent::with('room')->orderBy('event_date', 'desc')->paginate(50)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_name'  => 'required|string|max:100',
            'event_date'  => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|integer|exists:rooms,room_id',
        ]);

        $item = SpecialEvent::create($validated);

        return response()->json($item, 201);
    }

    public function show(SpecialEvent $specialEvent): JsonResponse
    {
        return response()->json($specialEvent->load('room'));
    }

    public function update(Request $request, SpecialEvent $specialEvent): JsonResponse
    {
        $validated = $request->validate([
            'event_name'  => 'required|string|max:100',
            'event_date'  => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|integer|exists:rooms,room_id',
        ]);

        $specialEvent->update($validated);

        return response()->json($specialEvent);
    }

    public function destroy(SpecialEvent $specialEvent): JsonResponse
    {
        $specialEvent->delete();

        return response()->json(['message' => 'Special event deleted']);
    }
}
