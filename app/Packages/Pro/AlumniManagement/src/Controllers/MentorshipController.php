<?php

namespace App\Packages\Pro\AlumniManagement\Controllers;

use App\Packages\Pro\AlumniManagement\Models\AlumniProfile;
use App\Packages\Pro\AlumniManagement\Services\MentorshipService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class MentorshipController extends BaseController
{
    public function __construct(private MentorshipService $mentorshipService) {}

    public function index(): View
    {
        $mentorships = $this->mentorshipService->getAll();
        $stats       = $this->mentorshipService->getStatistics();

        return view('alumni-management::mentorship.index', compact('mentorships', 'stats'));
    }

    public function show(int $mentorship): View
    {
        $mentorship = $this->mentorshipService->find($mentorship);

        return view('alumni-management::mentorship.show', compact('mentorship'));
    }

    public function create(): View
    {
        $alumni = AlumniProfile::where('status', 'active')->orderBy('full_name')->get();

        return view('alumni-management::mentorship.create', compact('alumni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mentor_alumni_id'    => 'required|exists:alumni_profiles,id',
            'mentee_name'         => 'required|string|max:255',
            'mentee_student_id'   => 'nullable|integer',
            'area_of_mentorship'  => 'required|string|max:255',
            'start_date'          => 'required|date',
            'end_date'            => 'nullable|date|after:start_date',
        ]);

        $this->mentorshipService->create($validated);

        return redirect()->route('alumni.mentorship.index')->with('success', 'Mentorship pairing created successfully');
    }

    public function complete(int $mentorship)
    {
        $this->mentorshipService->complete($mentorship);

        return redirect()->back()->with('success', 'Mentorship marked as completed');
    }
}
