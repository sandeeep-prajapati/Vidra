<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Transportation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class TransportVehicleApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Transportation::withCount('studentTransports')->orderBy('transport_name')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transport_name' => 'required|string|max:100',
            'transport_type' => 'required|in:Bus,Van,Shuttle',
            'capacity'       => 'required|integer|min:1',
            'route'          => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i',
        ]);

        $item = Transportation::create($validated);

        return response()->json($item, 201);
    }

    public function show(Transportation $transportation): JsonResponse
    {
        $transportation->load('studentTransports');

        return response()->json($transportation);
    }

    public function update(Request $request, Transportation $transportation): JsonResponse
    {
        $validated = $request->validate([
            'transport_name' => 'sometimes|string|max:100',
            'transport_type' => 'sometimes|in:Bus,Van,Shuttle',
            'capacity'       => 'sometimes|integer|min:1',
            'route'          => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i',
        ]);

        $transportation->update($validated);

        return response()->json($transportation);
    }

    public function destroy(Transportation $transportation): JsonResponse
    {
        $transportation->delete();

        return response()->json(['message' => 'Transportation deleted']);
    }
}
