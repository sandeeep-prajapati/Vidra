<?php

namespace App\Packages\ClassManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Section::with(['schoolClass', 'classTeacher']);

        if ($request->filled('search')) {
            $query->where('section_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $items   = $query->orderBy('section_name')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('is_active', true)->orderBy('class_name')->get();

        return view('class-management::section.index', compact('items', 'classes'));
    }

    public function create(): View
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('class_name')->get();
        $staff   = Staff::where('status', 'Active')->orderBy('first_name')->get();

        return view('class-management::section.create', compact('classes', 'staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_name'    => 'required|string|max:10',
            'class_id'        => 'required|integer|exists:classes,class_id',
            'class_teacher_id'=> 'nullable|integer|exists:staff,staff_id',
            'capacity'        => 'nullable|integer|min:0',
            'description'     => 'nullable|string',
            'is_active'       => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Section::create($validated);

        return redirect()->route('sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function show(Section $section): View
    {
        $section->load(['schoolClass', 'batches.academicYear', 'classTeacher']);

        return view('class-management::section.show', compact('section'));
    }

    public function edit(Section $section): View
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('class_name')->get();
        $staff   = Staff::where('status', 'Active')->orderBy('first_name')->get();

        return view('class-management::section.edit', compact('section', 'classes', 'staff'));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'section_name'    => 'required|string|max:10',
            'class_id'        => 'required|integer|exists:classes,class_id',
            'class_teacher_id'=> 'nullable|integer|exists:staff,staff_id',
            'capacity'        => 'nullable|integer|min:0',
            'description'     => 'nullable|string',
            'is_active'       => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $section->update($validated);

        return redirect()->route('sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('sections.index')
            ->with('success', 'Section deleted.');
    }
}
