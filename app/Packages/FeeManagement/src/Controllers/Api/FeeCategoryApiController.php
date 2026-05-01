<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeeCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class FeeCategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FeeCategory::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for feeCategory
        ]);

        $item = FeeCategory::create($validated);

        return response()->json($item, 201);
    }

    public function show(FeeCategory $feeCategory): JsonResponse
    {
        return response()->json($feeCategory);
    }

    public function update(Request $request, FeeCategory $feeCategory): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $feeCategory->update($validated);

        return response()->json($feeCategory);
    }

    public function destroy(FeeCategory $feeCategory): JsonResponse
    {
        $feeCategory->delete();

        return response()->json(['message' => 'FeeCategory deleted']);
    }
}
