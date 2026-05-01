<?php

namespace App\Packages\ExamManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\ExamSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Exam Management
 */
class ExamScheduleApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = ExamSchedule::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for examSchedule
        ]);

        $item = ExamSchedule::create($validated);

        return response()->json($item, 201);
    }

    public function show(ExamSchedule $examSchedule): JsonResponse
    {
        return response()->json($examSchedule);
    }

    public function update(Request $request, ExamSchedule $examSchedule): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $examSchedule->update($validated);

        return response()->json($examSchedule);
    }

    public function destroy(ExamSchedule $examSchedule): JsonResponse
    {
        $examSchedule->delete();

        return response()->json(['message' => 'ExamSchedule deleted']);
    }
}
