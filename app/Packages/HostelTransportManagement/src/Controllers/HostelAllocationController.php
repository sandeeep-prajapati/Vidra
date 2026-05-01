<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\HostelAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostelAllocationController extends Controller
{
    public function index(): View
    {
        $items = HostelAllocation::paginate(10);

        return view('hosteltransport::hostelAllocation.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::hostelAllocation.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for hostelAllocation
        ]);

        $item = HostelAllocation::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(HostelAllocation $hostelAllocation): View
    {
        return view('hosteltransport::hostelAllocation.show', compact('hostelAllocation'));
    }

    public function edit(HostelAllocation $hostelAllocation): View
    {
        return view('hosteltransport::hostelAllocation.edit', compact('hostelAllocation'));
    }

    public function update(Request $request, HostelAllocation $hostelAllocation): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $hostelAllocation->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(HostelAllocation $hostelAllocation): JsonResponse
    {
        $hostelAllocation->delete();

        return response()->json(['success' => true]);
    }
}
