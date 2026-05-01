<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FinancialReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class FinancialReportApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = FinancialReport::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for financialReport
        ]);

        $item = FinancialReport::create($validated);

        return response()->json($item, 201);
    }

    public function show(FinancialReport $financialReport): JsonResponse
    {
        return response()->json($financialReport);
    }

    public function update(Request $request, FinancialReport $financialReport): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $financialReport->update($validated);

        return response()->json($financialReport);
    }

    public function destroy(FinancialReport $financialReport): JsonResponse
    {
        $financialReport->delete();

        return response()->json(['message' => 'FinancialReport deleted']);
    }
}
