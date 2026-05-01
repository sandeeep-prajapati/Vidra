<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Hostel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class HostelApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Hostel::withCount('rooms')->orderBy('hostel_name')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hostel_name'        => 'required|string|max:100',
            'hostel_type'        => 'required|in:Boys,Girls,Co-ed',
            'total_capacity'     => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
            'location'           => 'nullable|string|max:100',
        ]);

        $item = Hostel::create($validated);

        return response()->json($item, 201);
    }

    public function show(Hostel $hostel): JsonResponse
    {
        $hostel->load(['rooms', 'studentHostels']);

        return response()->json($hostel);
    }

    public function update(Request $request, Hostel $hostel): JsonResponse
    {
        $validated = $request->validate([
            'hostel_name'        => 'sometimes|string|max:100',
            'hostel_type'        => 'sometimes|in:Boys,Girls,Co-ed',
            'total_capacity'     => 'sometimes|integer|min:1',
            'available_capacity' => 'sometimes|integer|min:0',
            'location'           => 'nullable|string|max:100',
        ]);

        $hostel->update($validated);

        return response()->json($hostel);
    }

    public function destroy(Hostel $hostel): JsonResponse
    {
        $hostel->delete();

        return response()->json(['message' => 'Hostel deleted']);
    }
}
