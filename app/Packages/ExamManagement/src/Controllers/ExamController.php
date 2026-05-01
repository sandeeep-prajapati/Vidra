<?php

namespace App\Packages\ExamManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ExamManagement\Models\Exam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $query = Exam::with('academicYear');

        if ($request->filled('search')) {
            $query->where('exam_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('exam-management::exam.index', compact('items', 'academicYears'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('exam-management::exam.create', compact('academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_name'        => 'required|string|max:100',
            'academic_year_id' => 'required|integer',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'description'      => 'nullable|string',
            'is_final'         => 'sometimes|boolean',
        ]);

        $validated['is_final'] = $request->boolean('is_final');

        Exam::create($validated);

        return redirect()->route('exam.index')
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam): View
    {
        $exam->load('academicYear');
        return view('exam-management::exam.show', compact('exam'));
    }

    public function edit(Exam $exam): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('exam-management::exam.edit', compact('exam', 'academicYears'));
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'exam_name'        => 'required|string|max:100',
            'academic_year_id' => 'required|integer',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'description'      => 'nullable|string',
            'is_final'         => 'sometimes|boolean',
        ]);

        $validated['is_final'] = $request->boolean('is_final');

        $exam->update($validated);

        return redirect()->route('exam.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();
        return redirect()->route('exam.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
