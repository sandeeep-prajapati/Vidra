<?php

namespace App\Packages\Pro\AlumniManagement\Services;

use App\Packages\Pro\AlumniManagement\Models\AlumniDonation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DonationService
{
    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        return AlumniDonation::with('alumni')->latest('donated_at')->paginate($perPage);
    }

    public function create(array $data): AlumniDonation
    {
        if (empty($data['receipt_number'])) {
            $data['receipt_number'] = 'RCP-' . strtoupper(uniqid());
        }

        return AlumniDonation::create($data);
    }

    public function confirm(int $id): AlumniDonation
    {
        $donation = AlumniDonation::findOrFail($id);
        $donation->update(['status' => 'confirmed']);
        return $donation;
    }

    public function getStatistics(): array
    {
        return [
            'total_donations'    => AlumniDonation::count(),
            'confirmed_total'    => AlumniDonation::where('status', 'confirmed')->sum('amount'),
            'pending_count'      => AlumniDonation::where('status', 'pending')->count(),
            'scholarship_total'  => AlumniDonation::where('purpose', 'scholarship')->where('status', 'confirmed')->sum('amount'),
        ];
    }
}
