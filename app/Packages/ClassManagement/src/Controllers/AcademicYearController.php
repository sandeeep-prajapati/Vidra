<?php

namespace App\Packages\ClassManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(Request $request): View
    {
        $query = AcademicYear::with(['classes', 'batches']);

        if ($request->filled('search')) {
            $query->where('year_range', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('is_current')) {
            $query->where('is_current', $request->is_current === '1');
        }

        $items = $query->orderByDesc('start_date')->paginate(15)->withQueryString();

        return view('class-management::academicYear.index', compact('items'));
    }

    public function create(): View
    {
        return view('class-management::academicYear.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year_range'  => 'required|string|max:20|unique:academic_years,year_range',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'is_current'  => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        if ($validated['is_current']) {
            AcademicYear::where('is_current', true)->update(['is_current' => false]);
        }

        AcademicYear::create($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    public function show(AcademicYear $academicYear): View
    {
        $academicYear->load(['classes', 'batches.section']);

        return view('class-management::academicYear.show', compact('academicYear'));
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('class-management::academicYear.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $request->validate([
            'year_range'  => 'required|string|max:20|unique:academic_years,year_range,'.$academicYear->academic_year_id.',academic_year_id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'is_current'  => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->boolean('is_current');

        if ($validated['is_current']) {
            AcademicYear::where('is_current', true)
                ->where('academic_year_id', '!=', $academicYear->academic_year_id)
                ->update(['is_current' => false]);
        }

        $academicYear->update($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic year deleted.');
    }
}
