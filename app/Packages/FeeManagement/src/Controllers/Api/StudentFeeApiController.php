<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\StudentFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class StudentFeeApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentFee::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for studentFee
        ]);

        $item = StudentFee::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentFee $studentFee): JsonResponse
    {
        return response()->json($studentFee);
    }

    public function update(Request $request, StudentFee $studentFee): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $studentFee->update($validated);

        return response()->json($studentFee);
    }

    public function destroy(StudentFee $studentFee): JsonResponse
    {
        $studentFee->delete();

        return response()->json(['message' => 'StudentFee deleted']);
    }
}
