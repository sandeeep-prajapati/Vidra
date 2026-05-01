<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class SubjectApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Subject::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for subject
        ]);

        $item = Subject::create($validated);

        return response()->json($item, 201);
    }

    public function show(Subject $subject): JsonResponse
    {
        return response()->json($subject);
    }

    public function update(Request $request, Subject $subject): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $subject->update($validated);

        return response()->json($subject);
    }

    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return response()->json(['message' => 'Subject deleted']);
    }
}
