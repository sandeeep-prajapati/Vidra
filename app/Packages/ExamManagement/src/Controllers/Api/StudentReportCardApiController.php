<?php

namespace App\Packages\ExamManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\StudentReportCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Exam Management
 */
class StudentReportCardApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentReportCard::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for studentReportCard
        ]);

        $item = StudentReportCard::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentReportCard $studentReportCard): JsonResponse
    {
        return response()->json($studentReportCard);
    }

    public function update(Request $request, StudentReportCard $studentReportCard): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $studentReportCard->update($validated);

        return response()->json($studentReportCard);
    }

    public function destroy(StudentReportCard $studentReportCard): JsonResponse
    {
        $studentReportCard->delete();

        return response()->json(['message' => 'StudentReportCard deleted']);
    }
}
