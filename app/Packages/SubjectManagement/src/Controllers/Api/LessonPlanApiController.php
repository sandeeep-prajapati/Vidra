<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\LessonPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class LessonPlanApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = LessonPlan::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for lessonPlan
        ]);

        $item = LessonPlan::create($validated);

        return response()->json($item, 201);
    }

    public function show(LessonPlan $lessonPlan): JsonResponse
    {
        return response()->json($lessonPlan);
    }

    public function update(Request $request, LessonPlan $lessonPlan): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $lessonPlan->update($validated);

        return response()->json($lessonPlan);
    }

    public function destroy(LessonPlan $lessonPlan): JsonResponse
    {
        $lessonPlan->delete();

        return response()->json(['message' => 'LessonPlan deleted']);
    }
}
