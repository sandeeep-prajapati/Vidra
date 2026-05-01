<?php

namespace App\Packages\TimetableManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Timetable Management
 */
class RoomApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Room::orderBy('room_name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_name'   => 'required|string|max:50|unique:rooms,room_name',
            'room_type'   => 'required|in:Classroom,Lab,Auditorium,Office',
            'capacity'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $item = Room::create($validated);

        return response()->json($item, 201);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json($room);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'room_name'   => 'required|string|max:50|unique:rooms,room_name,' . $room->room_id . ',room_id',
            'room_type'   => 'required|in:Classroom,Lab,Auditorium,Office',
            'capacity'    => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return response()->json($room);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json(['message' => 'Room deleted']);
    }
}
