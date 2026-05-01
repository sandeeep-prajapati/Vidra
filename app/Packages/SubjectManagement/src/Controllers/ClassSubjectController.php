<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\SubjectManagement\Models\ClassSubject;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = ClassSubject::with(['schoolClass', 'subject']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('mandatory')) {
            $query->where('is_mandatory', $request->mandatory === '1');
        }

        $items    = $query->paginate(15)->withQueryString();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::class-subject.index', compact('items', 'classes', 'subjects'));
    }

    public function create(): View
    {
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::class-subject.create', compact('classes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'     => 'required|integer|exists:classes,class_id',
            'subject_id'   => 'required|integer|exists:subjects,subject_id',
            'is_mandatory' => 'sometimes|boolean',
        ]);

        $validated['is_mandatory'] = $request->boolean('is_mandatory', true);

        ClassSubject::create($validated);

        return redirect()->route('class-subjects.index')
            ->with('success', 'Subject assigned to class successfully.');
    }

    public function show(ClassSubject $classSubject): View
    {
        $classSubject->load(['schoolClass', 'subject']);

        return view('subject-management::class-subject.show', compact('classSubject'));
    }

    public function edit(ClassSubject $classSubject): View
    {
        $classSubject->load(['schoolClass', 'subject']);
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::class-subject.edit', compact('classSubject', 'classes', 'subjects'));
    }

    public function update(Request $request, ClassSubject $classSubject): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'     => 'required|integer|exists:classes,class_id',
            'subject_id'   => 'required|integer|exists:subjects,subject_id',
            'is_mandatory' => 'sometimes|boolean',
        ]);

        $validated['is_mandatory'] = $request->boolean('is_mandatory');

        $classSubject->update($validated);

        return redirect()->route('class-subjects.index')
            ->with('success', 'Class-subject updated successfully.');
    }

    public function destroy(ClassSubject $classSubject): RedirectResponse
    {
        $classSubject->delete();

        return redirect()->route('class-subjects.index')
            ->with('success', 'Assignment removed.');
    }
}
