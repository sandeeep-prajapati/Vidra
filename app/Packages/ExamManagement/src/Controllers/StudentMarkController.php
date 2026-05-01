<?php

namespace App\Packages\ExamManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\ExamSchedule;
use App\Packages\ExamManagement\Models\GradingScheme;
use App\Packages\ExamManagement\Models\StudentMark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

class StudentMarkController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentMark::with(['student', 'schedule.exam', 'schedule.subject']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        $items         = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $examSchedules = ExamSchedule::with(['exam', 'subject'])->get();

        return view('exam-management::student-mark.index', compact('items', 'examSchedules'));
    }

    public function create(): View
    {
        $examSchedules = ExamSchedule::with(['exam', 'subject'])->get();
        return view('exam-management::student-mark.create', compact('examSchedules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'     => 'required|integer',
            'schedule_id'    => 'required|integer',
            'marks_obtained' => 'required|numeric|min:0',
            'grade'          => 'nullable|string|max:5',
            'remarks'        => 'nullable|string',
        ]);

        if (empty($validated['grade'])) {
            $validated['grade'] = $this->calculateGrade($validated['schedule_id'], $validated['marks_obtained']);
        }

        $mark = StudentMark::create($validated);

        Event::dispatch('webhook.exam.result', [
            'id'             => $mark->mark_id,
            'student_id'     => $mark->student_id,
            'schedule_id'    => $mark->schedule_id,
            'marks_obtained' => $mark->marks_obtained,
            'grade'          => $mark->grade,
        ]);

        return redirect()->route('studentMark.index')
            ->with('success', 'Marks saved successfully.');
    }

    public function show(StudentMark $studentMark): View
    {
        $studentMark->load(['student', 'schedule.exam', 'schedule.subject']);
        return view('exam-management::student-mark.show', compact('studentMark'));
    }

    public function edit(StudentMark $studentMark): View
    {
        $examSchedules = ExamSchedule::with(['exam', 'subject'])->get();
        return view('exam-management::student-mark.edit', compact('studentMark', 'examSchedules'));
    }

    public function update(Request $request, StudentMark $studentMark): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'     => 'required|integer',
            'schedule_id'    => 'required|integer',
            'marks_obtained' => 'required|numeric|min:0',
            'grade'          => 'nullable|string|max:5',
            'remarks'        => 'nullable|string',
        ]);

        if (empty($validated['grade'])) {
            $validated['grade'] = $this->calculateGrade($validated['schedule_id'], $validated['marks_obtained']);
        }

        $studentMark->update($validated);

        return redirect()->route('studentMark.index')
            ->with('success', 'Marks updated successfully.');
    }

    public function destroy(StudentMark $studentMark): RedirectResponse
    {
        $studentMark->delete();
        return redirect()->route('studentMark.index')
            ->with('success', 'Marks deleted successfully.');
    }

    private function calculateGrade(int $scheduleId, float $marksObtained): ?string
    {
        $schedule = ExamSchedule::find($scheduleId);
        if (!$schedule || $schedule->total_marks <= 0) {
            return null;
        }

        $percentage = ($marksObtained / $schedule->total_marks) * 100;

        $grading = GradingScheme::where('min_percentage', '<=', $percentage)
            ->where('max_percentage', '>=', $percentage)
            ->first();

        return $grading?->grade;
    }
}
