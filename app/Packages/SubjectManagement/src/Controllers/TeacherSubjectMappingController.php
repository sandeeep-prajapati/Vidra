<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\SubjectManagement\Models\Subject;
use App\Packages\SubjectManagement\Models\TeacherSubjectMapping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherSubjectMappingController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeacherSubjectMapping::with(['teacher', 'schoolClass', 'subject', 'section']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $items    = $query->paginate(15)->withQueryString();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        $teachers = Staff::orderBy('first_name')->get();

        return view('subject-management::teacher-subject-mapping.index', compact('items', 'classes', 'subjects', 'teachers'));
    }

    public function create(): View
    {
        $teachers = Staff::orderBy('first_name')->get();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        $sections = Section::orderBy('section_name')->get();

        return view('subject-management::teacher-subject-mapping.create', compact('teachers', 'classes', 'subjects', 'sections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:staff,staff_id',
            'class_id'   => 'required|integer|exists:classes,class_id',
            'subject_id' => 'required|integer|exists:subjects,subject_id',
            'section_id' => 'nullable|integer|exists:sections,section_id',
        ]);

        TeacherSubjectMapping::create($validated);

        return redirect()->route('teacher-subject-mappings.index')
            ->with('success', 'Teacher assigned to subject successfully.');
    }

    public function show(TeacherSubjectMapping $teacherSubjectMapping): View
    {
        $teacherSubjectMapping->load(['teacher', 'schoolClass', 'subject', 'section']);

        return view('subject-management::teacher-subject-mapping.show', compact('teacherSubjectMapping'));
    }

    public function edit(TeacherSubjectMapping $teacherSubjectMapping): View
    {
        $teachers = Staff::orderBy('first_name')->get();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        $sections = Section::orderBy('section_name')->get();

        return view('subject-management::teacher-subject-mapping.edit', compact('teacherSubjectMapping', 'teachers', 'classes', 'subjects', 'sections'));
    }

    public function update(Request $request, TeacherSubjectMapping $teacherSubjectMapping): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:staff,staff_id',
            'class_id'   => 'required|integer|exists:classes,class_id',
            'subject_id' => 'required|integer|exists:subjects,subject_id',
            'section_id' => 'nullable|integer|exists:sections,section_id',
        ]);

        $teacherSubjectMapping->update($validated);

        return redirect()->route('teacher-subject-mappings.index')
            ->with('success', 'Mapping updated successfully.');
    }

    public function destroy(TeacherSubjectMapping $teacherSubjectMapping): RedirectResponse
    {
        $teacherSubjectMapping->delete();

        return redirect()->route('teacher-subject-mappings.index')
            ->with('success', 'Mapping removed.');
    }
}
