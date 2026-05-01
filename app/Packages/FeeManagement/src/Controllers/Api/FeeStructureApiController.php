<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeeStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class FeeStructureApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FeeStructure::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for feeStructure
        ]);

        $item = FeeStructure::create($validated);

        return response()->json($item, 201);
    }

    public function show(FeeStructure $feeStructure): JsonResponse
    {
        return response()->json($feeStructure);
    }

    public function update(Request $request, FeeStructure $feeStructure): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $feeStructure->update($validated);

        return response()->json($feeStructure);
    }

    public function destroy(FeeStructure $feeStructure): JsonResponse
    {
        $feeStructure->delete();

        return response()->json(['message' => 'FeeStructure deleted']);
    }
}
