<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\StudentTransport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class TransportAllocationApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentTransport::with('transportation')->orderByDesc('assigned_date')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id'      => 'required|integer',
            'transport_id'    => 'required|integer|exists:transportation,transport_id',
            'pickup_location' => 'nullable|string|max:255',
            'drop_location'   => 'nullable|string|max:255',
            'assigned_date'   => 'required|date',
            'leave_date'      => 'nullable|date',
        ]);

        $item = StudentTransport::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentTransport $studentTransport): JsonResponse
    {
        $studentTransport->load('transportation');

        return response()->json($studentTransport);
    }

    public function update(Request $request, StudentTransport $studentTransport): JsonResponse
    {
        $validated = $request->validate([
            'student_id'      => 'sometimes|integer',
            'transport_id'    => 'sometimes|integer|exists:transportation,transport_id',
            'pickup_location' => 'nullable|string|max:255',
            'drop_location'   => 'nullable|string|max:255',
            'assigned_date'   => 'sometimes|date',
            'leave_date'      => 'nullable|date',
        ]);

        $studentTransport->update($validated);

        return response()->json($studentTransport);
    }

    public function destroy(StudentTransport $studentTransport): JsonResponse
    {
        $studentTransport->delete();

        return response()->json(['message' => 'Transport assignment deleted']);
    }
}
