<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Hostel;
use App\Packages\HostelTransportManagement\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::with('hostel');

        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        $items   = $query->orderBy('room_number')->paginate(15)->withQueryString();
        $hostels = Hostel::orderBy('hostel_name')->get();

        return view('hostel-transport::room.index', compact('items', 'hostels'));
    }

    public function create(): View
    {
        $hostels = Hostel::orderBy('hostel_name')->get();

        return view('hostel-transport::room.create', compact('hostels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hostel_id'   => 'required|integer|exists:hostels,hostel_id',
            'room_number' => 'required|string|max:10',
            'room_type'   => 'required|in:Single,Double,Triple,Quad',
            'capacity'    => 'required|integer|min:1',
            'occupied'    => 'nullable|integer|min:0',
        ]);

        $validated['occupied'] = $validated['occupied'] ?? 0;

        Room::create($validated);

        return redirect()->route('hostel-rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room): View
    {
        $room->load(['hostel', 'studentHostels']);

        return view('hostel-transport::room.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        $hostels = Hostel::orderBy('hostel_name')->get();

        return view('hostel-transport::room.edit', compact('room', 'hostels'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'hostel_id'   => 'required|integer|exists:hostels,hostel_id',
            'room_number' => 'required|string|max:10',
            'room_type'   => 'required|in:Single,Double,Triple,Quad',
            'capacity'    => 'required|integer|min:1',
            'occupied'    => 'nullable|integer|min:0',
        ]);

        $room->update($validated);

        return redirect()->route('hostel-rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('hostel-rooms.index')
            ->with('success', 'Room deleted.');
    }
}
