<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\HostelRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostelRoomController extends Controller
{
    public function index(): View
    {
        $items = HostelRoom::paginate(10);

        return view('hosteltransport::hostelRoom.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::hostelRoom.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for hostelRoom
        ]);

        $item = HostelRoom::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(HostelRoom $hostelRoom): View
    {
        return view('hosteltransport::hostelRoom.show', compact('hostelRoom'));
    }

    public function edit(HostelRoom $hostelRoom): View
    {
        return view('hosteltransport::hostelRoom.edit', compact('hostelRoom'));
    }

    public function update(Request $request, HostelRoom $hostelRoom): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $hostelRoom->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(HostelRoom $hostelRoom): JsonResponse
    {
        $hostelRoom->delete();

        return response()->json(['success' => true]);
    }
}
