<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class TransportScheduleApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = TransportSchedule::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportSchedule
        ]);

        $item = TransportSchedule::create($validated);

        return response()->json($item, 201);
    }

    public function show(TransportSchedule $transportSchedule): JsonResponse
    {
        return response()->json($transportSchedule);
    }

    public function update(Request $request, TransportSchedule $transportSchedule): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportSchedule->update($validated);

        return response()->json($transportSchedule);
    }

    public function destroy(TransportSchedule $transportSchedule): JsonResponse
    {
        $transportSchedule->delete();

        return response()->json(['message' => 'TransportSchedule deleted']);
    }
}
