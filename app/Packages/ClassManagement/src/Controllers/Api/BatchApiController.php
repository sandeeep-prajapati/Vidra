<?php

namespace App\Packages\ClassManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Class Management
 */
class BatchApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Batch::with(['schoolClass', 'section', 'academicYear', 'enrollments.student'])->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,class_id',
            'section_id' => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'batch_name' => 'required|string|max:100',
            'batch_code' => 'nullable|string|max:20|unique:batches,batch_code',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $item = Batch::create($validated);

        return response()->json($item, 201);
    }

    public function show(Batch $batch): JsonResponse
    {
        $batch->load(['schoolClass', 'section', 'academicYear', 'enrollments.student']);

        return response()->json($batch);
    }

    public function update(Request $request, Batch $batch): JsonResponse
    {
        $validated = $request->validate([
            'class_id' => 'nullable|integer|exists:classes,class_id',
            'section_id' => 'nullable|integer|exists:sections,section_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'batch_name' => 'nullable|string|max:100',
            'batch_code' => 'nullable|string|max:20|unique:batches,batch_code,'.$batch->batch_id.',batch_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $batch->update($validated);

        return response()->json($batch);
    }

    public function destroy(Batch $batch): JsonResponse
    {
        $batch->delete();

        return response()->json(['message' => 'Batch deleted']);
    }
}
