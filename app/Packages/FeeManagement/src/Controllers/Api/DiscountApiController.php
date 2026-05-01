<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class DiscountApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Discount::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for discount
        ]);

        $item = Discount::create($validated);

        return response()->json($item, 201);
    }

    public function show(Discount $discount): JsonResponse
    {
        return response()->json($discount);
    }

    public function update(Request $request, Discount $discount): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $discount->update($validated);

        return response()->json($discount);
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();

        return response()->json(['message' => 'Discount deleted']);
    }
}
