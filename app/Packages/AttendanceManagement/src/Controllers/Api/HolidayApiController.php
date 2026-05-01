<?php

namespace App\Packages\AttendanceManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\Holiday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Attendance Management
 */
class HolidayApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Holiday::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('recurring')) {
            $query->where('is_recurring', $request->recurring === '1');
        }

        return response()->json($query->orderBy('date')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:100',
            'date'         => 'required|date',
            'description'  => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        $item = Holiday::create($validated);

        return response()->json($item, 201);
    }

    public function show(Holiday $holiday): JsonResponse
    {
        return response()->json($holiday);
    }

    public function update(Request $request, Holiday $holiday): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:100',
            'date'         => 'required|date',
            'description'  => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        $holiday->update($validated);

        return response()->json($holiday);
    }

    public function destroy(Holiday $holiday): JsonResponse
    {
        $holiday->delete();

        return response()->json(['message' => 'Holiday deleted.']);
    }
}
