<?php

namespace App\Packages\ExamManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\StudentMark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Exam Management
 */
class StudentMarkApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentMark::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for studentMark
        ]);

        $item = StudentMark::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentMark $studentMark): JsonResponse
    {
        return response()->json($studentMark);
    }

    public function update(Request $request, StudentMark $studentMark): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $studentMark->update($validated);

        return response()->json($studentMark);
    }

    public function destroy(StudentMark $studentMark): JsonResponse
    {
        $studentMark->delete();

        return response()->json(['message' => 'StudentMark deleted']);
    }
}
