<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\Holiday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolidayController extends Controller
{
    public function index(Request $request): View
    {
        $query = Holiday::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('recurring')) {
            $query->where('is_recurring', $request->recurring === '1');
        }

        $items = $query->orderBy('date')->paginate(20)->withQueryString();

        return view('attendance-management::holiday.index', compact('items'));
    }

    public function create(): View
    {
        return view('attendance-management::holiday.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:100',
            'date'         => 'required|date',
            'description'  => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        $validated['is_recurring'] = $request->boolean('is_recurring');

        Holiday::create($validated);

        return redirect()->route('holiday.index')
            ->with('success', 'Holiday added successfully.');
    }

    public function show(Holiday $holiday): View
    {
        return view('attendance-management::holiday.show', compact('holiday'));
    }

    public function edit(Holiday $holiday): View
    {
        return view('attendance-management::holiday.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:100',
            'date'         => 'required|date',
            'description'  => 'nullable|string',
            'is_recurring' => 'sometimes|boolean',
        ]);

        $validated['is_recurring'] = $request->boolean('is_recurring');

        $holiday->update($validated);

        return redirect()->route('holiday.index')
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();

        return redirect()->route('holiday.index')
            ->with('success', 'Holiday deleted.');
    }
}
