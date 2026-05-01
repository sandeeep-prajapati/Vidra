<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\ClassSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class ClassSubjectApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = ClassSubject::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for classSubject
        ]);

        $item = ClassSubject::create($validated);

        return response()->json($item, 201);
    }

    public function show(ClassSubject $classSubject): JsonResponse
    {
        return response()->json($classSubject);
    }

    public function update(Request $request, ClassSubject $classSubject): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $classSubject->update($validated);

        return response()->json($classSubject);
    }

    public function destroy(ClassSubject $classSubject): JsonResponse
    {
        $classSubject->delete();

        return response()->json(['message' => 'ClassSubject deleted']);
    }
}
