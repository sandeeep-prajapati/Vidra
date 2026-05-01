<?php

namespace App\Packages\HostelTransportManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\StudentHostel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Hostel & Transport Management
 */
class HostelAllocationApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentHostel::with(['hostel', 'room'])->orderByDesc('assigned_date')->paginate(15);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id'    => 'required|integer',
            'hostel_id'     => 'required|integer|exists:hostels,hostel_id',
            'room_id'       => 'required|integer|exists:rooms,room_id',
            'assigned_date' => 'required|date',
            'checkout_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        $item = StudentHostel::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentHostel $studentHostel): JsonResponse
    {
        $studentHostel->load(['hostel', 'room']);

        return response()->json($studentHostel);
    }

    public function update(Request $request, StudentHostel $studentHostel): JsonResponse
    {
        $validated = $request->validate([
            'student_id'    => 'sometimes|integer',
            'hostel_id'     => 'sometimes|integer|exists:hostels,hostel_id',
            'room_id'       => 'sometimes|integer|exists:rooms,room_id',
            'assigned_date' => 'sometimes|date',
            'checkout_date' => 'nullable|date',
        ]);

        $studentHostel->update($validated);

        return response()->json($studentHostel);
    }

    public function destroy(StudentHostel $studentHostel): JsonResponse
    {
        $studentHostel->delete();

        return response()->json(['message' => 'Hostel assignment deleted']);
    }
}
