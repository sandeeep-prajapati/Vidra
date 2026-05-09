<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\SubjectManagement\Models\Subject;
use App\Packages\TimetableManagement\Models\Day;
use App\Packages\TimetableManagement\Models\Period;
use App\Packages\TimetableManagement\Models\Room;
use App\Packages\TimetableManagement\Models\Timetable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        $classes      = SchoolClass::orderBy('class_name')->get();
        $academicYears = AcademicYear::orderBy('year_range', 'desc')->get();
        $days         = Day::orderBy('day_id')->get();
        $periods      = Period::orderBy('start_time')->get();

        $sections = collect();
        $timetableGrid = [];

        $selectedClassId      = $request->input('class_id');
        $selectedSectionId    = $request->input('section_id');
        $selectedAcademicYearId = $request->input('academic_year_id');

        if ($selectedClassId) {
            $sections = Section::where('class_id', $selectedClassId)->orderBy('section_name')->get();
        }

        if ($selectedClassId && $selectedSectionId && $selectedAcademicYearId) {
            $timetableEntries = Timetable::with(['day', 'period', 'subject', 'teacher', 'room'])
                ->where('class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->where('academic_year_id', $selectedAcademicYearId)
                ->get();

            foreach ($timetableEntries as $entry) {
                $timetableGrid[$entry->day_id][$entry->period_id] = $entry;
            }
        }

        return view('timetable::timetable.index', compact(
            'classes', 'academicYears', 'days', 'periods', 'sections',
            'timetableGrid', 'selectedClassId', 'selectedSectionId', 'selectedAcademicYearId'
        ));
    }

    public function create(): View
    {
        $classes      = SchoolClass::orderBy('class_name')->get();
        $sections     = Section::orderBy('section_name')->get();
        $academicYears = AcademicYear::orderBy('year_range', 'desc')->get();
        $days         = Day::orderBy('day_id')->get();
        $periods      = Period::orderBy('start_time')->get();
        $subjects     = Subject::orderBy('subject_name')->get();
        $teachers     = Staff::orderBy('first_name')->get();
        $rooms        = Room::orderBy('room_name')->get();

        return view('timetable::timetable.create', compact(
            'classes', 'sections', 'academicYears', 'days', 'periods', 'subjects', 'teachers', 'rooms'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'required|integer|exists:academic_years,academic_year_id',
            'day_id'           => 'required|integer|exists:days,day_id',
            'period_id'        => 'required|integer|exists:periods,period_id',
            'subject_id'       => 'required|integer|exists:subjects,subject_id',
            'teacher_id'       => 'required|integer|exists:staff,staff_id',
            'room_id'          => 'required|integer|exists:rooms,room_id',
        ]);

        // Conflict: same slot already scheduled
        $exists = Timetable::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('day_id', $validated['day_id'])
            ->where('period_id', $validated['period_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['period_id' => 'This slot is already scheduled for the selected class/section.']);
        }

        Timetable::create($validated);

        return redirect()->route('timetable.index')->with('success', 'Timetable entry created successfully.');
    }

    public function show(Timetable $timetable): View
    {
        $timetable->load('schoolClass', 'section', 'academicYear', 'day', 'period', 'subject', 'teacher', 'room');

        return view('timetable::timetable.show', compact('timetable'));
    }

    public function edit(Timetable $timetable): View
    {
        $classes      = SchoolClass::orderBy('class_name')->get();
        $sections     = Section::orderBy('section_name')->get();
        $academicYears = AcademicYear::orderBy('year_range', 'desc')->get();
        $days         = Day::orderBy('day_id')->get();
        $periods      = Period::orderBy('start_time')->get();
        $subjects     = Subject::orderBy('subject_name')->get();
        $teachers     = Staff::orderBy('first_name')->get();
        $rooms        = Room::orderBy('room_name')->get();

        return view('timetable::timetable.edit', compact(
            'timetable', 'classes', 'sections', 'academicYears', 'days', 'periods', 'subjects', 'teachers', 'rooms'
        ));
    }

    public function update(Request $request, Timetable $timetable): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer|exists:classes,class_id',
            'section_id'       => 'required|integer|exists:sections,section_id',
            'academic_year_id' => 'required|integer|exists:academic_years,academic_year_id',
            'day_id'           => 'required|integer|exists:days,day_id',
            'period_id'        => 'required|integer|exists:periods,period_id',
            'subject_id'       => 'required|integer|exists:subjects,subject_id',
            'teacher_id'       => 'required|integer|exists:staff,staff_id',
            'room_id'          => 'required|integer|exists:rooms,room_id',
        ]);

        $exists = Timetable::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('day_id', $validated['day_id'])
            ->where('period_id', $validated['period_id'])
            ->where('timetable_id', '!=', $timetable->timetable_id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['period_id' => 'This slot is already scheduled for the selected class/section.']);
        }

        $timetable->update($validated);

        return redirect()->route('timetable.index')->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()->route('timetable.index')->with('success', 'Timetable entry deleted.');
    }
}
