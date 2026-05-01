<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Period;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodController extends Controller
{
    public function index(): View
    {
        $items = Period::orderBy('start_time')->paginate(20);

        return view('timetable::period.index', compact('items'));
    }

    public function create(): View
    {
        return view('timetable::period.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        Period::create($validated);

        return redirect()->route('period.index')->with('success', 'Period created successfully.');
    }

    public function show(Period $period): View
    {
        return view('timetable::period.show', compact('period'));
    }

    public function edit(Period $period): View
    {
        return view('timetable::period.edit', compact('period'));
    }

    public function update(Request $request, Period $period): RedirectResponse
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $period->update($validated);

        return redirect()->route('period.index')->with('success', 'Period updated successfully.');
    }

    public function destroy(Period $period): RedirectResponse
    {
        $period->delete();

        return redirect()->route('period.index')->with('success', 'Period deleted.');
    }
}
