<?php

namespace App\Packages\HostelTransportManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\HostelTransportManagement\Models\TransportSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportScheduleController extends Controller
{
    public function index(): View
    {
        $items = TransportSchedule::paginate(10);

        return view('hosteltransport::transportSchedule.index', compact('items'));
    }

    public function create(): View
    {
        return view('hosteltransport::transportSchedule.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules for transportSchedule
        ]);

        $item = TransportSchedule::create($validated);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function show(TransportSchedule $transportSchedule): View
    {
        return view('hosteltransport::transportSchedule.show', compact('transportSchedule'));
    }

    public function edit(TransportSchedule $transportSchedule): View
    {
        return view('hosteltransport::transportSchedule.edit', compact('transportSchedule'));
    }

    public function update(Request $request, TransportSchedule $transportSchedule): JsonResponse
    {
        $validated = $request->validate([
            // Define validation rules
        ]);

        $transportSchedule->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(TransportSchedule $transportSchedule): JsonResponse
    {
        $transportSchedule->delete();

        return response()->json(['success' => true]);
    }
}
