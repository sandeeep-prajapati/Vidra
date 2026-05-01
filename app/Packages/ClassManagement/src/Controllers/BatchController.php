<?php

namespace App\Packages\ClassManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\Batch;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Batch::with(['schoolClass', 'section', 'academicYear']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('batch_name', 'like', '%'.$request->search.'%')
                  ->orWhere('batch_code', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $items         = $query->orderByDesc('start_date')->paginate(15)->withQueryString();
        $classes       = SchoolClass::where('is_active', true)->orderBy('class_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::batch.index', compact('items', 'classes', 'academicYears'));
    }

    public function create(): View
    {
        $classes       = SchoolClass::where('is_active', true)->orderBy('class_name')->get();
        $sections      = Section::where('is_active', true)->orderBy('section_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::batch.create', compact('classes', 'sections', 'academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'batch_name'       => 'required|string|max:100',
            'batch_code'       => 'nullable|string|max:20|unique:batches,batch_code',
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i',
            'description'      => 'nullable|string',
            'is_active'        => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Batch::create($validated);

        return redirect()->route('batches.index')
            ->with('success', 'Batch created successfully.');
    }

    public function show(Batch $batch): View
    {
        $batch->load(['schoolClass', 'section', 'academicYear', 'enrollments.student']);

        return view('class-management::batch.show', compact('batch'));
    }

    public function edit(Batch $batch): View
    {
        $classes       = SchoolClass::where('is_active', true)->orderBy('class_name')->get();
        $sections      = Section::where('is_active', true)->orderBy('section_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('class-management::batch.edit', compact('batch', 'classes', 'sections', 'academicYears'));
    }

    public function update(Request $request, Batch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'batch_name'       => 'required|string|max:100',
            'batch_code'       => 'nullable|string|max:20|unique:batches,batch_code,'.$batch->batch_id.',batch_id',
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i',
            'description'      => 'nullable|string',
            'is_active'        => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $batch->update($validated);

        return redirect()->route('batches.index')
            ->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()->route('batches.index')
            ->with('success', 'Batch deleted.');
    }
}
