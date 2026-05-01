<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\FacilityManagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = FacilityManagement::withCount('bookings');

        if ($request->filled('search')) {
            $query->where('facility_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('type')) {
            $query->where('facility_type', $request->type);
        }

        $items = $query->orderBy('facility_name')->paginate(15)->withQueryString();

        return view('hostel-transport::facility.index', compact('items'));
    }

    public function create(): View
    {
        return view('hostel-transport::facility.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'facility_name'      => 'required|string|max:100',
            'facility_type'      => 'required|in:Sports,Library,Cafeteria,Lab',
            'location'           => 'nullable|string|max:100',
            'capacity'           => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
        ]);

        FacilityManagement::create($validated);

        return redirect()->route('facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    public function show(FacilityManagement $facility): View
    {
        $facility->load('bookings');

        return view('hostel-transport::facility.show', compact('facility'));
    }

    public function edit(FacilityManagement $facility): View
    {
        return view('hostel-transport::facility.edit', compact('facility'));
    }

    public function update(Request $request, FacilityManagement $facility): RedirectResponse
    {
        $validated = $request->validate([
            'facility_name'      => 'required|string|max:100',
            'facility_type'      => 'required|in:Sports,Library,Cafeteria,Lab',
            'location'           => 'nullable|string|max:100',
            'capacity'           => 'required|integer|min:1',
            'available_capacity' => 'required|integer|min:0',
        ]);

        $facility->update($validated);

        return redirect()->route('facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    public function destroy(FacilityManagement $facility): RedirectResponse
    {
        $facility->delete();

        return redirect()->route('facilities.index')
            ->with('success', 'Facility deleted.');
    }
}
