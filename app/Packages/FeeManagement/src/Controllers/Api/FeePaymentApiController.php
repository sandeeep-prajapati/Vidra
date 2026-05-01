<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class FeePaymentApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FeePayment::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for feePayment
        ]);

        $item = FeePayment::create($validated);

        return response()->json($item, 201);
    }

    public function show(FeePayment $feePayment): JsonResponse
    {
        return response()->json($feePayment);
    }

    public function update(Request $request, FeePayment $feePayment): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $feePayment->update($validated);

        return response()->json($feePayment);
    }

    public function destroy(FeePayment $feePayment): JsonResponse
    {
        $feePayment->delete();

        return response()->json(['message' => 'FeePayment deleted']);
    }
}
