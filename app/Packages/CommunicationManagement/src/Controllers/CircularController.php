<?php

namespace App\Packages\CommunicationManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Packages\CommunicationManagement\Models\Circular;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CircularController extends Controller
{
    public function index(Request $request): View
    {
        $query = Circular::with('issuer');

        if ($request->filled('target_audience')) {
            $query->where('target_audience', $request->target_audience);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderByDesc('issued_date')->paginate(20)->withQueryString();

        return view('communication::circular.index', compact('items'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('communication::circular.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:150',
            'content'         => 'required|string',
            'issued_by'       => 'nullable|exists:users,id',
            'issued_date'     => 'required|date',
            'target_audience' => 'required|in:All,Students,Parents,Teachers,Staff',
            'attachment'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment_url'] = Storage::url(
                $request->file('attachment')->store('circulars', 'public')
            );
        }
        unset($validated['attachment']);

        Circular::create($validated);

        return redirect()->route('circular.index')->with('success', 'Circular created successfully.');
    }

    public function show(Circular $circular): View
    {
        $circular->load('issuer');
        return view('communication::circular.show', compact('circular'));
    }

    public function edit(Circular $circular): View
    {
        $users = User::orderBy('name')->get();
        return view('communication::circular.edit', compact('circular', 'users'));
    }

    public function update(Request $request, Circular $circular): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:150',
            'content'         => 'required|string',
            'issued_by'       => 'nullable|exists:users,id',
            'issued_date'     => 'required|date',
            'target_audience' => 'required|in:All,Students,Parents,Teachers,Staff',
            'attachment'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'attachment_clear' => 'nullable|boolean',
        ]);

        if ($request->hasFile('attachment')) {
            if ($circular->attachment_url) {
                Storage::disk('public')->delete(ltrim($circular->attachment_url, '/storage/'));
            }
            $validated['attachment_url'] = Storage::url(
                $request->file('attachment')->store('circulars', 'public')
            );
        } elseif ($request->boolean('attachment_clear') && $circular->attachment_url) {
            Storage::disk('public')->delete(ltrim($circular->attachment_url, '/storage/'));
            $validated['attachment_url'] = null;
        }
        unset($validated['attachment'], $validated['attachment_clear']);

        $circular->update($validated);

        return redirect()->route('circular.index')->with('success', 'Circular updated successfully.');
    }

    public function destroy(Circular $circular): RedirectResponse
    {
        if ($circular->attachment_url) {
            Storage::disk('public')->delete(ltrim($circular->attachment_url, '/storage/'));
        }
        $circular->delete();
        return redirect()->route('circular.index')->with('success', 'Circular deleted.');
    }
}
