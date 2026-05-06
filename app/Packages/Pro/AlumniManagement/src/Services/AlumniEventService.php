<?php

namespace App\Packages\Pro\AlumniManagement\Services;

use App\Packages\Pro\AlumniManagement\Models\AlumniEvent;
use App\Packages\Pro\AlumniManagement\Models\AlumniEventRegistration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AlumniEventService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return AlumniEvent::withCount('registrations')->latest('event_date')->paginate($perPage);
    }

    public function find(int $id): AlumniEvent
    {
        return AlumniEvent::with('registrations.alumni')->withCount('registrations')->findOrFail($id);
    }

    public function create(array $data): AlumniEvent
    {
        return AlumniEvent::create($data);
    }

    public function registerAlumni(int $eventId, int $alumniId): AlumniEventRegistration
    {
        return AlumniEventRegistration::firstOrCreate(
            ['event_id' => $eventId, 'alumni_id' => $alumniId],
            ['registered_at' => now()]
        );
    }

    public function getRegistrations(int $eventId): LengthAwarePaginator
    {
        return AlumniEventRegistration::with('alumni')
            ->where('event_id', $eventId)
            ->latest()
            ->paginate(25);
    }

    public function getStatistics(): array
    {
        return [
            'total'     => AlumniEvent::count(),
            'upcoming'  => AlumniEvent::where('status', 'upcoming')->count(),
            'completed' => AlumniEvent::where('status', 'completed')->count(),
        ];
    }
}
