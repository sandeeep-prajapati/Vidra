<?php

namespace App\Packages\ClassManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Class Management
 */
class AcademicYearApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = AcademicYear::with(['classes', 'batches'])->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year_range' => 'required|string|max:20|unique:academic_years,year_range',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $item = AcademicYear::create($validated);

        return response()->json($item, 201);
    }

    public function show(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->load(['classes', 'batches']);

        return response()->json($academicYear);
    }

    public function update(Request $request, AcademicYear $academicYear): JsonResponse
    {
        $validated = $request->validate([
            'year_range' => 'nullable|string|max:20|unique:academic_years,year_range,'.$academicYear->academic_year_id.',academic_year_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $academicYear->update($validated);

        return response()->json($academicYear);
    }

    public function destroy(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->delete();

        return response()->json(['message' => 'AcademicYear deleted']);
    }
}
