<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\Transportation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transportation::withCount('studentTransports');

        if ($request->filled('search')) {
            $query->where('transport_name', 'like', '%'.$request->search.'%')
                  ->orWhere('route', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('type')) {
            $query->where('transport_type', $request->type);
        }

        $items = $query->orderBy('transport_name')->paginate(15)->withQueryString();

        return view('hostel-transport::transportation.index', compact('items'));
    }

    public function create(): View
    {
        return view('hostel-transport::transportation.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transport_name' => 'required|string|max:100',
            'transport_type' => 'required|in:Bus,Van,Shuttle',
            'capacity'       => 'required|integer|min:1',
            'route'          => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i',
        ]);

        Transportation::create($validated);

        return redirect()->route('transportation.index')
            ->with('success', 'Transport service created successfully.');
    }

    public function show(Transportation $transportation): View
    {
        $transportation->load('studentTransports');

        return view('hostel-transport::transportation.show', compact('transportation'));
    }

    public function edit(Transportation $transportation): View
    {
        return view('hostel-transport::transportation.edit', compact('transportation'));
    }

    public function update(Request $request, Transportation $transportation): RedirectResponse
    {
        $validated = $request->validate([
            'transport_name' => 'required|string|max:100',
            'transport_type' => 'required|in:Bus,Van,Shuttle',
            'capacity'       => 'required|integer|min:1',
            'route'          => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i',
        ]);

        $transportation->update($validated);

        return redirect()->route('transportation.index')
            ->with('success', 'Transport service updated successfully.');
    }

    public function destroy(Transportation $transportation): RedirectResponse
    {
        $transportation->delete();

        return redirect()->route('transportation.index')
            ->with('success', 'Transport service deleted.');
    }
}
