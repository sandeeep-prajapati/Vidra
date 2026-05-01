<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\StudentDiscount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class StudentDiscountApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StudentDiscount::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for studentDiscount
        ]);

        $item = StudentDiscount::create($validated);

        return response()->json($item, 201);
    }

    public function show(StudentDiscount $studentDiscount): JsonResponse
    {
        return response()->json($studentDiscount);
    }

    public function update(Request $request, StudentDiscount $studentDiscount): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $studentDiscount->update($validated);

        return response()->json($studentDiscount);
    }

    public function destroy(StudentDiscount $studentDiscount): JsonResponse
    {
        $studentDiscount->delete();

        return response()->json(['message' => 'StudentDiscount deleted']);
    }
}
