<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\SubjectManagement\Models\Curriculum;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurriculumController extends Controller
{
    public function index(Request $request): View
    {
        $query = Curriculum::with(['subject', 'academicYear'])->withCount('lessonPlans');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $items         = $query->paginate(15)->withQueryString();
        $subjects      = Subject::orderBy('subject_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('subject-management::curriculum.index', compact('items', 'subjects', 'academicYears'));
    }

    public function create(): View
    {
        $subjects      = Subject::orderBy('subject_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('subject-management::curriculum.create', compact('subjects', 'academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id'      => 'required|integer|exists:subjects,subject_id',
            'academic_year_id'=> 'required|integer|exists:academic_years,academic_year_id',
            'description'     => 'nullable|string',
            'syllabus_document_path' => 'nullable|string|max:255',
        ]);

        Curriculum::create($validated);

        return redirect()->route('curriculums.index')
            ->with('success', 'Curriculum created successfully.');
    }

    public function show(Curriculum $curriculum): View
    {
        $curriculum->load(['subject', 'academicYear', 'lessonPlans']);

        return view('subject-management::curriculum.show', compact('curriculum'));
    }

    public function edit(Curriculum $curriculum): View
    {
        $subjects      = Subject::orderBy('subject_name')->get();
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('subject-management::curriculum.edit', compact('curriculum', 'subjects', 'academicYears'));
    }

    public function update(Request $request, Curriculum $curriculum): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id'      => 'required|integer|exists:subjects,subject_id',
            'academic_year_id'=> 'required|integer|exists:academic_years,academic_year_id',
            'description'     => 'nullable|string',
            'syllabus_document_path' => 'nullable|string|max:255',
        ]);

        $curriculum->update($validated);

        return redirect()->route('curriculums.index')
            ->with('success', 'Curriculum updated successfully.');
    }

    public function destroy(Curriculum $curriculum): RedirectResponse
    {
        $curriculum->delete();

        return redirect()->route('curriculums.index')
            ->with('success', 'Curriculum deleted.');
    }
}
