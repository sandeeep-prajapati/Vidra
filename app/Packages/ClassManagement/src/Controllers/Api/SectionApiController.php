<?php

namespace App\Packages\ClassManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Class Management
 */
class SectionApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Section::with(['schoolClass'])->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'required|string|max:10',
            'class_id' => 'required|integer|exists:classes,class_id',
            'class_teacher_id' => 'nullable|integer|exists:staff,staff_id',
            'capacity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $item = Section::create($validated);

        return response()->json($item, 201);
    }

    public function show(Section $section): JsonResponse
    {
        $section->load(['schoolClass', 'batches']);

        return response()->json($section);
    }

    public function update(Request $request, Section $section): JsonResponse
    {
        $validated = $request->validate([
            'section_name' => 'nullable|string|max:10',
            'class_id' => 'nullable|integer|exists:classes,class_id',
            'capacity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $section->update($validated);

        return response()->json($section);
    }

    public function destroy(Section $section): JsonResponse
    {
        $section->delete();

        return response()->json(['message' => 'Section deleted']);
    }
}
