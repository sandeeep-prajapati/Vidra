<?php

namespace App\Packages\FeeManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Fee Management
 */
class ExpenseApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Expense::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for expense
        ]);

        $item = Expense::create($validated);

        return response()->json($item, 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        return response()->json($expense);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $expense->update($validated);

        return response()->json($expense);
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json(['message' => 'Expense deleted']);
    }
}
