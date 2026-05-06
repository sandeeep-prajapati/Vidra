<?php

namespace App\Packages\Pro\AlumniManagement\Controllers;

use App\Packages\Pro\AlumniManagement\Models\AlumniProfile;
use App\Packages\Pro\AlumniManagement\Services\AlumniEventService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class EventController extends BaseController
{
    public function __construct(private AlumniEventService $eventService) {}

    public function index(): View
    {
        $events = $this->eventService->getAll();
        $stats  = $this->eventService->getStatistics();

        return view('alumni-management::events.index', compact('events', 'stats'));
    }

    public function show(int $event): View
    {
        $event = $this->eventService->find($event);

        return view('alumni-management::events.show', compact('event'));
    }

    public function create(): View
    {
        $alumni = AlumniProfile::where('status', 'active')->orderBy('full_name')->get();

        return view('alumni-management::events.create', compact('alumni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'description'            => 'nullable|string',
            'event_date'             => 'required|date',
            'venue'                  => 'nullable|string|max:255',
            'event_type'             => 'required|in:reunion,webinar,workshop,social',
            'organizer_alumni_id'    => 'nullable|exists:alumni_profiles,id',
            'max_attendees'          => 'nullable|integer|min:1',
            'registration_deadline'  => 'nullable|date|before:event_date',
            'status'                 => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $this->eventService->create($validated);

        return redirect()->route('alumni.events.index')->with('success', 'Event created successfully');
    }

    public function registrations(int $event): View
    {
        $registrations = $this->eventService->getRegistrations($event);
        $event = $this->eventService->find($event);

        return view('alumni-management::events.registrations', compact('event', 'registrations'));
    }

    public function register(Request $request, int $event)
    {
        $request->validate(['alumni_id' => 'required|exists:alumni_profiles,id']);

        $this->eventService->registerAlumni($event, $request->alumni_id);

        return redirect()->back()->with('success', 'Alumni registered for event');
    }
}
