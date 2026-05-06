<?php

namespace App\Packages\Pro\AlumniManagement\Services;

use App\Packages\Pro\AlumniManagement\Models\AlumniMentorship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MentorshipService
{
    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        return AlumniMentorship::with('mentor')->latest()->paginate($perPage);
    }

    public function find(int $id): AlumniMentorship
    {
        return AlumniMentorship::with('mentor')->findOrFail($id);
    }

    public function create(array $data): AlumniMentorship
    {
        return AlumniMentorship::create($data);
    }

    public function complete(int $id): AlumniMentorship
    {
        $mentorship = AlumniMentorship::findOrFail($id);
        $mentorship->update(['status' => 'completed', 'end_date' => now()->toDateString()]);
        return $mentorship;
    }

    public function getStatistics(): array
    {
        return [
            'active'    => AlumniMentorship::where('status', 'active')->count(),
            'completed' => AlumniMentorship::where('status', 'completed')->count(),
            'total'     => AlumniMentorship::count(),
        ];
    }
}
