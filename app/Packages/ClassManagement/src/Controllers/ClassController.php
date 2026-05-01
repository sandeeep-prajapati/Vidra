<?php

namespace App\Packages\ClassManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(Request $request): View
    {
        $query = SchoolClass::with(['academicYear', 'sections']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('class_name', 'like', '%'.$request->search.'%')
                  ->orWhere('class_code', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $items        = $query->orderBy('class_name')->paginate(15)->withQueryString();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::class.index', compact('items', 'academicYears'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::class.create', compact('academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_name'       => 'required|string|max:50',
            'class_code'       => 'nullable|string|max:20|unique:classes,class_code',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'description'      => 'nullable|string',
            'is_active'        => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        SchoolClass::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $schoolClass): View
    {
        $schoolClass->load(['academicYear', 'sections.classTeacher', 'batches.section', 'batches.academicYear']);

        return view('class-management::class.show', compact('schoolClass'));
    }

    public function edit(SchoolClass $schoolClass): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::class.edit', compact('schoolClass', 'academicYears'));
    }

    public function update(Request $request, SchoolClass $schoolClass): RedirectResponse
    {
        $validated = $request->validate([
            'class_name'       => 'required|string|max:50',
            'class_code'       => 'nullable|string|max:20|unique:classes,class_code,'.$schoolClass->class_id.',class_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'description'      => 'nullable|string',
            'is_active'        => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $schoolClass->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $schoolClass): RedirectResponse
    {
        $schoolClass->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted.');
    }
}
