<?php

namespace App\Packages\SubjectManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\SubjectManagement\Models\Subject;
use App\Packages\SubjectManagement\Models\Textbook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TextbookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Textbook::with('subject');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('author', 'like', '%'.$request->search.'%')
                  ->orWhere('isbn', 'like', '%'.$request->search.'%');
            });
        }

        $items    = $query->orderBy('title')->paginate(15)->withQueryString();
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::textbook.index', compact('items', 'subjects'));
    }

    public function create(): View
    {
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::textbook.create', compact('subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer|exists:subjects,subject_id',
            'title'              => 'required|string|max:150',
            'author'             => 'nullable|string|max:100',
            'publisher'          => 'nullable|string|max:100',
            'edition'            => 'nullable|string|max:50',
            'isbn'               => 'nullable|string|max:50',
            'textbook_file_path' => 'nullable|string|max:255',
        ]);

        Textbook::create($validated);

        return redirect()->route('textbooks.index')
            ->with('success', 'Textbook added successfully.');
    }

    public function show(Textbook $textbook): View
    {
        $textbook->load('subject');

        return view('subject-management::textbook.show', compact('textbook'));
    }

    public function edit(Textbook $textbook): View
    {
        $subjects = Subject::orderBy('subject_name')->get();

        return view('subject-management::textbook.edit', compact('textbook', 'subjects'));
    }

    public function update(Request $request, Textbook $textbook): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer|exists:subjects,subject_id',
            'title'              => 'required|string|max:150',
            'author'             => 'nullable|string|max:100',
            'publisher'          => 'nullable|string|max:100',
            'edition'            => 'nullable|string|max:50',
            'isbn'               => 'nullable|string|max:50',
            'textbook_file_path' => 'nullable|string|max:255',
        ]);

        $textbook->update($validated);

        return redirect()->route('textbooks.index')
            ->with('success', 'Textbook updated successfully.');
    }

    public function destroy(Textbook $textbook): RedirectResponse
    {
        $textbook->delete();

        return redirect()->route('textbooks.index')
            ->with('success', 'Textbook deleted.');
    }
}
