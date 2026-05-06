<?php

namespace App\Packages\Pro\AlumniManagement\Services;

use App\Packages\Pro\AlumniManagement\Models\AlumniProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AlumniService
{
    public function getAllAlumni(int $perPage = 20): LengthAwarePaginator
    {
        return AlumniProfile::with(['employment' => fn ($q) => $q->where('is_current', true)])
            ->latest()
            ->paginate($perPage);
    }

    public function getDirectory(array $filters = []): LengthAwarePaginator
    {
        $query = AlumniProfile::with([
            'employment' => fn ($q) => $q->where('is_current', true),
        ])->where('status', 'active');

        if (!empty($filters['year'])) {
            $query->where('graduation_year', $filters['year']);
        }

        if (!empty($filters['city'])) {
            $query->where('current_city', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['industry'])) {
            $query->whereHas('employment', fn ($q) => $q->where('industry', 'like', '%' . $filters['industry'] . '%')->where('is_current', true));
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('full_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('graduation_year', 'desc')->paginate(24);
    }

    public function find(int $id): AlumniProfile
    {
        return AlumniProfile::with(['education', 'employment', 'donations', 'mentorships'])->findOrFail($id);
    }

    public function create(array $data): AlumniProfile
    {
        return AlumniProfile::create($data);
    }

    public function update(int $id, array $data): AlumniProfile
    {
        $alumni = AlumniProfile::findOrFail($id);
        $alumni->update($data);
        return $alumni->fresh();
    }

    public function delete(int $id): void
    {
        AlumniProfile::findOrFail($id)->delete();
    }

    public function getStatistics(): array
    {
        return [
            'total'       => AlumniProfile::count(),
            'active'      => AlumniProfile::where('status', 'active')->count(),
            'verified'    => AlumniProfile::where('is_verified', true)->count(),
            'this_year'   => AlumniProfile::where('graduation_year', date('Y'))->count(),
        ];
    }

    public function getGraduationYears(): Collection
    {
        return AlumniProfile::selectRaw('graduation_year')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->get()
            ->pluck('graduation_year');
    }
}
