<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\TimetableManagement\Models\SubstituteAssignment;
use App\Packages\TimetableManagement\Models\Timetable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubstituteAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = SubstituteAssignment::with(['timetable.day', 'timetable.period', 'timetable.subject', 'originalTeacher', 'substituteTeacher']);

        if ($request->filled('date')) {
            $query->whereDate('date_of_substitution', $request->date);
        }

        $items = $query->orderBy('date_of_substitution', 'desc')->paginate(20)->withQueryString();

        return view('timetable::substituteAssignment.index', compact('items'));
    }

    public function create(): View
    {
        $timetables = Timetable::with(['day', 'period', 'subject', 'schoolClass', 'section'])->orderBy('timetable_id')->get();
        $teachers   = Staff::orderBy('first_name')->get();

        return view('timetable::substituteAssignment.create', compact('timetables', 'teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'timetable_id'          => 'required|integer|exists:timetables,timetable_id',
            'original_teacher_id'   => 'required|integer|exists:staff,staff_id',
            'substitute_teacher_id' => 'required|integer|exists:staff,staff_id|different:original_teacher_id',
            'date_of_substitution'  => 'required|date',
        ]);

        SubstituteAssignment::create($validated);

        return redirect()->route('substituteAssignment.index')->with('success', 'Substitute assignment created successfully.');
    }

    public function show(SubstituteAssignment $substituteAssignment): View
    {
        $substituteAssignment->load('timetable.day', 'timetable.period', 'timetable.subject', 'timetable.schoolClass', 'timetable.section', 'originalTeacher', 'substituteTeacher');

        return view('timetable::substituteAssignment.show', compact('substituteAssignment'));
    }

    public function edit(SubstituteAssignment $substituteAssignment): View
    {
        $timetables = Timetable::with(['day', 'period', 'subject', 'schoolClass', 'section'])->orderBy('timetable_id')->get();
        $teachers   = Staff::orderBy('first_name')->get();

        return view('timetable::substituteAssignment.edit', compact('substituteAssignment', 'timetables', 'teachers'));
    }

    public function update(Request $request, SubstituteAssignment $substituteAssignment): RedirectResponse
    {
        $validated = $request->validate([
            'timetable_id'          => 'required|integer|exists:timetables,timetable_id',
            'original_teacher_id'   => 'required|integer|exists:staff,staff_id',
            'substitute_teacher_id' => 'required|integer|exists:staff,staff_id|different:original_teacher_id',
            'date_of_substitution'  => 'required|date',
        ]);

        $substituteAssignment->update($validated);

        return redirect()->route('substituteAssignment.index')->with('success', 'Substitute assignment updated successfully.');
    }

    public function destroy(SubstituteAssignment $substituteAssignment): RedirectResponse
    {
        $substituteAssignment->delete();

        return redirect()->route('substituteAssignment.index')->with('success', 'Substitute assignment deleted.');
    }
}
