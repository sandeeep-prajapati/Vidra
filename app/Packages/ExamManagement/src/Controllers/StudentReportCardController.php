<?php

namespace App\Packages\ExamManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\Exam;
use App\Packages\ExamManagement\Models\GradingScheme;
use App\Packages\ExamManagement\Models\StudentReportCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentReportCardController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentReportCard::with(['student', 'exam']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $exams = Exam::orderByDesc('created_at')->get();

        return view('exam-management::student-report-card.index', compact('items', 'exams'));
    }

    public function create(): View
    {
        $exams = Exam::orderByDesc('created_at')->get();
        return view('exam-management::student-report-card.create', compact('exams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'         => 'required|integer',
            'exam_id'            => 'required|integer',
            'total_marks'        => 'required|numeric|min:0',
            'maximum_marks'      => 'required|numeric|min:1',
            'overall_percentage' => 'nullable|numeric|min:0|max:100',
            'overall_grade'      => 'nullable|string|max:5',
            'rank_in_class'      => 'nullable|integer|min:1',
            'remarks'            => 'nullable|string',
        ]);

        $validated = $this->autoCalculate($validated);

        StudentReportCard::create($validated);

        return redirect()->route('studentReportCard.index')
            ->with('success', 'Report card generated successfully.');
    }

    public function show(StudentReportCard $studentReportCard): View
    {
        $studentReportCard->load(['student', 'exam']);
        return view('exam-management::student-report-card.show', compact('studentReportCard'));
    }

    public function edit(StudentReportCard $studentReportCard): View
    {
        $exams = Exam::orderByDesc('created_at')->get();
        return view('exam-management::student-report-card.edit', compact('studentReportCard', 'exams'));
    }

    public function update(Request $request, StudentReportCard $studentReportCard): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'         => 'required|integer',
            'exam_id'            => 'required|integer',
            'total_marks'        => 'required|numeric|min:0',
            'maximum_marks'      => 'required|numeric|min:1',
            'overall_percentage' => 'nullable|numeric|min:0|max:100',
            'overall_grade'      => 'nullable|string|max:5',
            'rank_in_class'      => 'nullable|integer|min:1',
            'remarks'            => 'nullable|string',
        ]);

        $validated = $this->autoCalculate($validated);

        $studentReportCard->update($validated);

        return redirect()->route('studentReportCard.index')
            ->with('success', 'Report card updated successfully.');
    }

    public function destroy(StudentReportCard $studentReportCard): RedirectResponse
    {
        $studentReportCard->delete();
        return redirect()->route('studentReportCard.index')
            ->with('success', 'Report card deleted successfully.');
    }

    private function autoCalculate(array $data): array
    {
        if (empty($data['overall_percentage']) && $data['maximum_marks'] > 0) {
            $data['overall_percentage'] = round(($data['total_marks'] / $data['maximum_marks']) * 100, 2);
        }

        if (empty($data['overall_grade']) && isset($data['overall_percentage'])) {
            $grading = GradingScheme::where('min_percentage', '<=', $data['overall_percentage'])
                ->where('max_percentage', '>=', $data['overall_percentage'])
                ->first();
            $data['overall_grade'] = $grading?->grade;
        }

        return $data;
    }
}
