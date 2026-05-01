<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subject::withCount(['classSubjects', 'textbooks', 'teacherMappings']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject_name', 'like', '%'.$request->search.'%')
                  ->orWhere('subject_code', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('type')) {
            $query->where('subject_type', $request->type);
        }
        if ($request->filled('optional')) {
            $query->where('is_optional', $request->optional === '1');
        }

        $items = $query->orderBy('subject_name')->paginate(15)->withQueryString();

        return view('subject-management::subject.index', compact('items'));
    }

    public function create(): View
    {
        return view('subject-management::subject.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:100',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'subject_type' => 'required|in:Theory,Practical,Lab',
            'description'  => 'nullable|string',
            'is_optional'  => 'sometimes|boolean',
        ]);

        $validated['is_optional'] = $request->boolean('is_optional');

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject): View
    {
        $subject->load(['classSubjects.schoolClass', 'curriculums.academicYear', 'textbooks', 'teacherMappings.teacher', 'teacherMappings.schoolClass']);

        return view('subject-management::subject.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('subject-management::subject.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:100',
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,'.$subject->subject_id.',subject_id',
            'subject_type' => 'required|in:Theory,Practical,Lab',
            'description'  => 'nullable|string',
            'is_optional'  => 'sometimes|boolean',
        ]);

        $validated['is_optional'] = $request->boolean('is_optional');

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted.');
    }
}
