<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Hostel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostelController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hostel::withCount('rooms');

        if ($request->filled('search')) {
            $query->where('hostel_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('type')) {
            $query->where('hostel_type', $request->type);
        }

        $items = $query->orderBy('hostel_name')->paginate(15)->withQueryString();

        return view('hostel-transport::hostel.index', compact('items'));
    }

    public function create(): View
    {
        return view('hostel-transport::hostel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hostel_name'        => 'required|string|max:100',
            'hostel_type'        => 'required|in:Boys,Girls,Co-ed',
            'total_capacity'     => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
            'location'           => 'nullable|string|max:100',
        ]);

        Hostel::create($validated);

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel created successfully.');
    }

    public function show(Hostel $hostel): View
    {
        $hostel->load(['rooms', 'studentHostels.room']);

        return view('hostel-transport::hostel.show', compact('hostel'));
    }

    public function edit(Hostel $hostel): View
    {
        return view('hostel-transport::hostel.edit', compact('hostel'));
    }

    public function update(Request $request, Hostel $hostel): RedirectResponse
    {
        $validated = $request->validate([
            'hostel_name'        => 'required|string|max:100',
            'hostel_type'        => 'required|in:Boys,Girls,Co-ed',
            'total_capacity'     => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
            'location'           => 'nullable|string|max:100',
        ]);

        $hostel->update($validated);

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel updated successfully.');
    }

    public function destroy(Hostel $hostel): RedirectResponse
    {
        $hostel->delete();

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel deleted.');
    }
}
