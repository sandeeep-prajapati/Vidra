<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\FacilityManagement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class FacilityApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FacilityManagement::withCount('bookings')->orderBy('facility_name')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_name'      => 'required|string|max:100',
            'facility_type'      => 'required|in:Sports,Library,Cafeteria,Lab',
            'location'           => 'nullable|string|max:100',
            'capacity'           => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
        ]);

        $item = FacilityManagement::create($validated);

        return response()->json($item, 201);
    }

    public function show(FacilityManagement $facility): JsonResponse
    {
        $facility->load('bookings');

        return response()->json($facility);
    }

    public function update(Request $request, FacilityManagement $facility): JsonResponse
    {
        $validated = $request->validate([
            'facility_name'      => 'sometimes|string|max:100',
            'facility_type'      => 'sometimes|in:Sports,Library,Cafeteria,Lab',
            'location'           => 'nullable|string|max:100',
            'capacity'           => 'sometimes|integer|min:1',
            'available_capacity' => 'sometimes|integer|min:0',
        ]);

        $facility->update($validated);

        return response()->json($facility);
    }

    public function destroy(FacilityManagement $facility): JsonResponse
    {
        $facility->delete();

        return response()->json(['message' => 'Facility deleted']);
    }
}
