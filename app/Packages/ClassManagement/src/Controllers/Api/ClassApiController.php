<?php

namespace App\Packages\ClassManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Class Management
 */
class ClassApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = SchoolClass::with(['academicYear', 'sections', 'batches'])->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:50',
            'class_code' => 'nullable|string|max:20|unique:classes,class_code',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $item = SchoolClass::create($validated);

        return response()->json($item, 201);
    }

    public function show(SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass->load(['academicYear', 'sections', 'batches']);

        return response()->json($schoolClass);
    }

    public function update(Request $request, SchoolClass $schoolClass): JsonResponse
    {
        $validated = $request->validate([
            'class_name' => 'nullable|string|max:50',
            'class_code' => 'nullable|string|max:20|unique:classes,class_code,'.$schoolClass->class_id.',class_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $schoolClass->update($validated);

        return response()->json($schoolClass);
    }

    public function destroy(SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass->delete();

        return response()->json(['message' => 'Class deleted']);
    }
}
