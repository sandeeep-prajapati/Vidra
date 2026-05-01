<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Day;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DayController extends Controller
{
    public function index(): View
    {
        $items = Day::orderBy('day_id')->paginate(20);

        return view('timetable::day.index', compact('items'));
    }

    public function create(): View
    {
        return view('timetable::day.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'day_name' => 'required|string|max:20|unique:days,day_name',
        ]);

        Day::create($validated);

        return redirect()->route('day.index')->with('success', 'Day created successfully.');
    }

    public function show(Day $day): View
    {
        return view('timetable::day.show', compact('day'));
    }

    public function edit(Day $day): View
    {
        return view('timetable::day.edit', compact('day'));
    }

    public function update(Request $request, Day $day): RedirectResponse
    {
        $validated = $request->validate([
            'day_name' => 'required|string|max:20|unique:days,day_name,' . $day->day_id . ',day_id',
        ]);

        $day->update($validated);

        return redirect()->route('day.index')->with('success', 'Day updated successfully.');
    }

    public function destroy(Day $day): RedirectResponse
    {
        $day->delete();

        return redirect()->route('day.index')->with('success', 'Day deleted.');
    }
}
