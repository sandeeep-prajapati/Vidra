<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportVehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportVehicleController extends Controller
{
    public function index(): View
    {
        $items = TransportVehicle::paginate(10);

        return view('hosteltransport::transportVehicle.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::transportVehicle.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportVehicle
        ]);

        $item = TransportVehicle::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(TransportVehicle $transportVehicle): View
    {
        return view('hosteltransport::transportVehicle.show', compact('transportVehicle'));
    }

    public function edit(TransportVehicle $transportVehicle): View
    {
        return view('hosteltransport::transportVehicle.edit', compact('transportVehicle'));
    }

    public function update(Request $request, TransportVehicle $transportVehicle): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportVehicle->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(TransportVehicle $transportVehicle): JsonResponse
    {
        $transportVehicle->delete();

        return response()->json(['success' => true]);
    }
}
