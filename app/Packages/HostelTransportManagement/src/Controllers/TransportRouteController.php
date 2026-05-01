<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportRouteController extends Controller
{
    public function index(): View
    {
        $items = TransportRoute::paginate(10);

        return view('hosteltransport::transportRoute.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::transportRoute.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportRoute
        ]);

        $item = TransportRoute::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(TransportRoute $transportRoute): View
    {
        return view('hosteltransport::transportRoute.show', compact('transportRoute'));
    }

    public function edit(TransportRoute $transportRoute): View
    {
        return view('hosteltransport::transportRoute.edit', compact('transportRoute'));
    }

    public function update(Request $request, TransportRoute $transportRoute): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportRoute->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(TransportRoute $transportRoute): JsonResponse
    {
        $transportRoute->delete();

        return response()->json(['success' => true]);
    }
}
