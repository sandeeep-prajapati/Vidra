<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Room;
use App\Packages\TimetableManagement\Models\SpecialEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecialEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = SpecialEvent::with('room');

        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('from_date')) {
            $query->whereDate('event_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('event_date', '<=', $request->to_date);
        }

        $items = $query->orderBy('event_date', 'desc')->paginate(20)->withQueryString();

        return view('timetable::specialEvent.index', compact('items'));
    }

    public function create(): View
    {
        $rooms = Room::orderBy('room_name')->get();

        return view('timetable::specialEvent.create', compact('rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_name'  => 'required|string|max:100',
            'event_date'  => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|integer|exists:rooms,room_id',
        ]);

        SpecialEvent::create($validated);

        return redirect()->route('specialEvent.index')->with('success', 'Special event created successfully.');
    }

    public function show(SpecialEvent $specialEvent): View
    {
        $specialEvent->load('room');

        return view('timetable::specialEvent.show', compact('specialEvent'));
    }

    public function edit(SpecialEvent $specialEvent): View
    {
        $rooms = Room::orderBy('room_name')->get();

        return view('timetable::specialEvent.edit', compact('specialEvent', 'rooms'));
    }

    public function update(Request $request, SpecialEvent $specialEvent): RedirectResponse
    {
        $validated = $request->validate([
            'event_name'  => 'required|string|max:100',
            'event_date'  => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'room_id'     => 'nullable|integer|exists:rooms,room_id',
        ]);

        $specialEvent->update($validated);

        return redirect()->route('specialEvent.index')->with('success', 'Special event updated successfully.');
    }

    public function destroy(SpecialEvent $specialEvent): RedirectResponse
    {
        $specialEvent->delete();

        return redirect()->route('specialEvent.index')->with('success', 'Special event deleted.');
    }
}
