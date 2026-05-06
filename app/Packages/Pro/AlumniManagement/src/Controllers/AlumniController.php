<?php

namespace App\Packages\Pro\AlumniManagement\Controllers;

use App\Packages\Pro\AlumniManagement\Services\AlumniService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class AlumniController extends BaseController
{
    public function __construct(private AlumniService $alumniService) {}

    public function directory(Request $request): View
    {
        $filters = $request->only(['year', 'city', 'industry', 'search']);
        $alumni = $this->alumniService->getDirectory($filters);
        $years = $this->alumniService->getGraduationYears();
        $stats = $this->alumniService->getStatistics();

        return view('alumni-management::alumni.directory', compact('alumni', 'years', 'filters', 'stats'));
    }

    public function index(): View
    {
        $alumni = $this->alumniService->getAllAlumni();
        $stats = $this->alumniService->getStatistics();

        return view('alumni-management::alumni.index', compact('alumni', 'stats'));
    }

    public function show(int $alumni): View
    {
        $alumni = $this->alumniService->find($alumni);

        return view('alumni-management::alumni.show', compact('alumni'));
    }

    public function create(): View
    {
        $years = range(date('Y'), 1980);

        return view('alumni-management::alumni.create', compact('years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:alumni_profiles',
            'phone'           => 'nullable|string|max:20',
            'graduation_year' => 'required|integer|min:1980|max:' . date('Y'),
            'graduation_class'=> 'nullable|string|max:50',
            'current_city'    => 'nullable|string|max:100',
            'current_country' => 'nullable|string|max:100',
            'bio'             => 'nullable|string|max:1000',
            'linkedin_url'    => 'nullable|url',
            'website_url'     => 'nullable|url',
            'status'          => 'required|in:active,inactive,deceased',
        ]);

        $this->alumniService->create($validated);

        return redirect()->route('alumni.directory')->with('success', 'Alumni profile created successfully');
    }

    public function edit(int $alumni): View
    {
        $alumni = $this->alumniService->find($alumni);
        $years = range(date('Y'), 1980);

        return view('alumni-management::alumni.edit', compact('alumni', 'years'));
    }

    public function update(Request $request, int $alumni)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'email'           => "required|email|unique:alumni_profiles,email,{$alumni}",
            'phone'           => 'nullable|string|max:20',
            'graduation_year' => 'required|integer|min:1980|max:' . date('Y'),
            'graduation_class'=> 'nullable|string|max:50',
            'current_city'    => 'nullable|string|max:100',
            'current_country' => 'nullable|string|max:100',
            'bio'             => 'nullable|string|max:1000',
            'linkedin_url'    => 'nullable|url',
            'website_url'     => 'nullable|url',
            'status'          => 'required|in:active,inactive,deceased',
        ]);

        $this->alumniService->update($alumni, $validated);

        return redirect()->route('alumni.show', $alumni)->with('success', 'Alumni profile updated successfully');
    }

    public function destroy(int $alumni)
    {
        $this->alumniService->delete($alumni);

        return redirect()->route('alumni.directory')->with('success', 'Alumni profile deleted successfully');
    }
}
