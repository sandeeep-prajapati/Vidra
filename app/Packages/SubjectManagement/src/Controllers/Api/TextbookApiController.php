<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Textbook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class TextbookApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Textbook::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for textbook
        ]);

        $item = Textbook::create($validated);

        return response()->json($item, 201);
    }

    public function show(Textbook $textbook): JsonResponse
    {
        return response()->json($textbook);
    }

    public function update(Request $request, Textbook $textbook): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $textbook->update($validated);

        return response()->json($textbook);
    }

    public function destroy(Textbook $textbook): JsonResponse
    {
        $textbook->delete();

        return response()->json(['message' => 'Textbook deleted']);
    }
}
