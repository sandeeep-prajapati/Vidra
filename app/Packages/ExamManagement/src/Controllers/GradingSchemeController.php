<?php

namespace App\Packages\ExamManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ExamManagement\Models\GradingScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradingSchemeController extends Controller
{
    public function index(): View
    {
        $items = GradingScheme::orderBy('min_percentage')->paginate(20);
        return view('exam-management::grading-scheme.index', compact('items'));
    }

    public function create(): View
    {
        return view('exam-management::grading-scheme.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grade'          => 'required|string|max:5',
            'min_percentage' => 'required|numeric|min:0|max:100',
            'max_percentage' => 'required|numeric|min:0|max:100|gte:min_percentage',
            'remarks'        => 'nullable|string|max:100',
        ]);

        GradingScheme::create($validated);

        return redirect()->route('gradingScheme.index')
            ->with('success', 'Grade created successfully.');
    }

    public function show(GradingScheme $gradingScheme): View
    {
        return view('exam-management::grading-scheme.show', compact('gradingScheme'));
    }

    public function edit(GradingScheme $gradingScheme): View
    {
        return view('exam-management::grading-scheme.edit', compact('gradingScheme'));
    }

    public function update(Request $request, GradingScheme $gradingScheme): RedirectResponse
    {
        $validated = $request->validate([
            'grade'          => 'required|string|max:5',
            'min_percentage' => 'required|numeric|min:0|max:100',
            'max_percentage' => 'required|numeric|min:0|max:100|gte:min_percentage',
            'remarks'        => 'nullable|string|max:100',
        ]);

        $gradingScheme->update($validated);

        return redirect()->route('gradingScheme.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(GradingScheme $gradingScheme): RedirectResponse
    {
        $gradingScheme->delete();
        return redirect()->route('gradingScheme.index')
            ->with('success', 'Grade deleted successfully.');
    }
}
