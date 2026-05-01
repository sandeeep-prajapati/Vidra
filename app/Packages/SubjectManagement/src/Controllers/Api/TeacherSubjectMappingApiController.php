<?php

namespace App\Packages\SubjectManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\TeacherSubjectMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Subject Management
 */
class TeacherSubjectMappingApiController extends Controller
{
    public function index(): JsonResponse
    {
        $items = TeacherSubjectMapping::paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for teacherSubjectMapping
        ]);

        $item = TeacherSubjectMapping::create($validated);

        return response()->json($item, 201);
    }

    public function show(TeacherSubjectMapping $teacherSubjectMapping): JsonResponse
    {
        return response()->json($teacherSubjectMapping);
    }

    public function update(Request $request, TeacherSubjectMapping $teacherSubjectMapping): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $teacherSubjectMapping->update($validated);

        return response()->json($teacherSubjectMapping);
    }

    public function destroy(TeacherSubjectMapping $teacherSubjectMapping): JsonResponse
    {
        $teacherSubjectMapping->delete();

        return response()->json(['message' => 'TeacherSubjectMapping deleted']);
    }
}
