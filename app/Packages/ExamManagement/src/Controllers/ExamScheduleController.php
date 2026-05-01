<?php

namespace App\Packages\ExamManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ExamManagement\Models\Exam;
use App\Packages\ExamManagement\Models\ExamSchedule;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $query = ExamSchedule::with(['exam', 'schoolClass', 'subject']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $exams = Exam::orderByDesc('created_at')->get();

        return view('exam-management::exam-schedule.index', compact('items', 'exams'));
    }

    public function create(): View
    {
        $exams    = Exam::orderByDesc('created_at')->get();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('exam-management::exam-schedule.create', compact('exams', 'classes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id'       => 'required|integer',
            'class_id'      => 'required|integer',
            'subject_id'    => 'required|integer',
            'exam_date'     => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'total_marks'   => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
        ]);

        ExamSchedule::create($validated);

        return redirect()->route('examSchedule.index')
            ->with('success', 'Exam schedule created successfully.');
    }

    public function show(ExamSchedule $examSchedule): View
    {
        $examSchedule->load(['exam', 'schoolClass', 'subject']);
        return view('exam-management::exam-schedule.show', compact('examSchedule'));
    }

    public function edit(ExamSchedule $examSchedule): View
    {
        $exams    = Exam::orderByDesc('created_at')->get();
        $classes  = SchoolClass::orderBy('class_name')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('exam-management::exam-schedule.edit', compact('examSchedule', 'exams', 'classes', 'subjects'));
    }

    public function update(Request $request, ExamSchedule $examSchedule): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id'       => 'required|integer',
            'class_id'      => 'required|integer',
            'subject_id'    => 'required|integer',
            'exam_date'     => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'total_marks'   => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
        ]);

        $examSchedule->update($validated);

        return redirect()->route('examSchedule.index')
            ->with('success', 'Exam schedule updated successfully.');
    }

    public function destroy(ExamSchedule $examSchedule): RedirectResponse
    {
        $examSchedule->delete();
        return redirect()->route('examSchedule.index')
            ->with('success', 'Exam schedule deleted successfully.');
    }
}
