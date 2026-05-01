<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Curriculum;
use App\Packages\SubjectManagement\Models\LessonPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonPlanController extends Controller
{
    public function index(Request $request): View
    {
        $query = LessonPlan::with(['curriculum.subject', 'curriculum.academicYear']);

        if ($request->filled('curriculum_id')) {
            $query->where('curriculum_id', $request->curriculum_id);
        }
        if ($request->filled('search')) {
            $query->where('topic_name', 'like', '%'.$request->search.'%');
        }

        $items      = $query->orderBy('start_date')->paginate(15)->withQueryString();
        $curriculums = Curriculum::with(['subject', 'academicYear'])->get();

        return view('subject-management::lesson-plan.index', compact('items', 'curriculums'));
    }

    public function create(): View
    {
        $curriculums = Curriculum::with(['subject', 'academicYear'])->get();

        return view('subject-management::lesson-plan.create', compact('curriculums'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|integer|exists:curriculums,curriculum_id',
            'topic_name'    => 'required|string|max:150',
            'objectives'    => 'nullable|string',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        LessonPlan::create($validated);

        return redirect()->route('lesson-plans.index')
            ->with('success', 'Lesson plan created successfully.');
    }

    public function show(LessonPlan $lessonPlan): View
    {
        $lessonPlan->load(['curriculum.subject', 'curriculum.academicYear']);

        return view('subject-management::lesson-plan.show', compact('lessonPlan'));
    }

    public function edit(LessonPlan $lessonPlan): View
    {
        $curriculums = Curriculum::with(['subject', 'academicYear'])->get();

        return view('subject-management::lesson-plan.edit', compact('lessonPlan', 'curriculums'));
    }

    public function update(Request $request, LessonPlan $lessonPlan): RedirectResponse
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|integer|exists:curriculums,curriculum_id',
            'topic_name'    => 'required|string|max:150',
            'objectives'    => 'nullable|string',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
        ]);

        $lessonPlan->update($validated);

        return redirect()->route('lesson-plans.index')
            ->with('success', 'Lesson plan updated successfully.');
    }

    public function destroy(LessonPlan $lessonPlan): RedirectResponse
    {
        $lessonPlan->delete();

        return redirect()->route('lesson-plans.index')
            ->with('success', 'Lesson plan deleted.');
    }
}
