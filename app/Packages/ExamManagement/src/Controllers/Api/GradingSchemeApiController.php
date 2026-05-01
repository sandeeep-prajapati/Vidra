<?php

namespace App\Packages\ExamManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\GradingScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Exam Management
 */
class GradingSchemeApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = GradingScheme::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for gradingScheme
        ]);

        $item = GradingScheme::create($validated);

        return response()->json($item, 201);
    }

    public function show(GradingScheme $gradingScheme): JsonResponse
    {
        return response()->json($gradingScheme);
    }

    public function update(Request $request, GradingScheme $gradingScheme): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $gradingScheme->update($validated);

        return response()->json($gradingScheme);
    }

    public function destroy(GradingScheme $gradingScheme): JsonResponse
    {
        $gradingScheme->delete();

        return response()->json(['message' => 'GradingScheme deleted']);
    }
}
