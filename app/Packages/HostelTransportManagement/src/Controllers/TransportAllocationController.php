<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportAllocationController extends Controller
{
    public function index(): View
    {
        $items = TransportAllocation::paginate(10);

        return view('hosteltransport::transportAllocation.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::transportAllocation.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportAllocation
        ]);

        $item = TransportAllocation::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(TransportAllocation $transportAllocation): View
    {
        return view('hosteltransport::transportAllocation.show', compact('transportAllocation'));
    }

    public function edit(TransportAllocation $transportAllocation): View
    {
        return view('hosteltransport::transportAllocation.edit', compact('transportAllocation'));
    }

    public function update(Request $request, TransportAllocation $transportAllocation): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportAllocation->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(TransportAllocation $transportAllocation): JsonResponse
    {
        $transportAllocation->delete();

        return response()->json(['success' => true]);
    }
}
