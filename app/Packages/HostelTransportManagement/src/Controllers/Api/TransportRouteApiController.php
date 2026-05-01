<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class TransportRouteApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = TransportRoute::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportRoute
        ]);

        $item = TransportRoute::create($validated);

        return response()->json($item, 201);
    }

    public function show(TransportRoute $transportRoute): JsonResponse
    {
        return response()->json($transportRoute);
    }

    public function update(Request $request, TransportRoute $transportRoute): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportRoute->update($validated);

        return response()->json($transportRoute);
    }

    public function destroy(TransportRoute $transportRoute): JsonResponse
    {
        $transportRoute->delete();

        return response()->json(['message' => 'TransportRoute deleted']);
    }
}
