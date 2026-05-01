<?php

namespace App\Packages\TimetableManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\TimetableManagement\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::query();
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }
        if ($request->filled('search')) {
            $query->where('room_name', 'like', '%' . $request->search . '%');
        }
        $items = $query->orderBy('room_name')->paginate(20)->withQueryString();

        return view('timetable::room.index', compact('items'));
    }

    public function create(): View
    {
        return view('timetable::room.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:50|unique:rooms,room_name',
            'room_type' => 'required|in:Classroom,Lab,Auditorium,Office',
            'capacity'  => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()->route('room.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room): View
    {
        $room->load('timetables.day', 'timetables.period', 'specialEvents');

        return view('timetable::room.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        return view('timetable::room.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:50|unique:rooms,room_name,' . $room->room_id . ',room_id',
            'room_type' => 'required|in:Classroom,Lab,Auditorium,Office',
            'capacity'  => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted.');
    }
}
