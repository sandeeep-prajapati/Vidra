<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class HostelRoomApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Room::with('hostel')->orderBy('room_number')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hostel_id'   => 'required|integer|exists:hostels,hostel_id',
            'room_number' => 'required|string|max:10',
            'room_type'   => 'required|in:Single,Double,Triple,Quad',
            'capacity'    => 'required|integer|min:1',
            'occupied'    => 'nullable|integer|min:0',
        ]);

        $item = Room::create($validated);

        return response()->json($item, 201);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load(['hostel', 'studentHostels']);

        return response()->json($room);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'hostel_id'   => 'sometimes|integer|exists:hostels,hostel_id',
            'room_number' => 'sometimes|string|max:10',
            'room_type'   => 'sometimes|in:Single,Double,Triple,Quad',
            'capacity'    => 'sometimes|integer|min:1',
            'occupied'    => 'nullable|integer|min:0',
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
