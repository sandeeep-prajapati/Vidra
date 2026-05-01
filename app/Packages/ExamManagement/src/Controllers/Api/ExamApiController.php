<?php

namespace App\Packages\ExamManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Exam Management
 */
class ExamApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Exam::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for exam
        ]);

        $item = Exam::create($validated);

        return response()->json($item, 201);
    }

    public function show(Exam $exam): JsonResponse
    {
        return response()->json($exam);
    }

    public function update(Request $request, Exam $exam): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $exam->update($validated);

        return response()->json($exam);
    }

    public function destroy(Exam $exam): JsonResponse
    {
        $exam->delete();

        return response()->json(['message' => 'Exam deleted']);
    }
}
