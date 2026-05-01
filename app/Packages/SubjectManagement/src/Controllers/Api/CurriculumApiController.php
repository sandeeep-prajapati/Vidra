<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Curriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class CurriculumApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Curriculum::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for curriculum
        ]);

        $item = Curriculum::create($validated);

        return response()->json($item, 201);
    }

    public function show(Curriculum $curriculum): JsonResponse
    {
        return response()->json($curriculum);
    }

    public function update(Request $request, Curriculum $curriculum): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $curriculum->update($validated);

        return response()->json($curriculum);
    }

    public function destroy(Curriculum $curriculum): JsonResponse
    {
        $curriculum->delete();

        return response()->json(['message' => 'Curriculum deleted']);
    }
}
