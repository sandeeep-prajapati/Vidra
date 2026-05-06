<?php

namespace App\Packages\Pro\AlumniManagement\Controllers;

use App\Packages\Pro\AlumniManagement\Models\AlumniProfile;
use App\Packages\Pro\AlumniManagement\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class DonationController extends BaseController
{
    public function __construct(private DonationService $donationService) {}

    public function index(): View
    {
        $donations = $this->donationService->getAll();
        $stats     = $this->donationService->getStatistics();

        return view('alumni-management::donations.index', compact('donations', 'stats'));
    }

    public function create(): View
    {
        $alumni = AlumniProfile::where('status', 'active')->orderBy('full_name')->get();

        return view('alumni-management::donations.create', compact('alumni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumni_id'      => 'required|exists:alumni_profiles,id',
            'amount'         => 'required|numeric|min:1',
            'currency'       => 'required|string|size:3',
            'purpose'        => 'required|in:scholarship,infrastructure,general',
            'donated_at'     => 'required|date',
            'receipt_number' => 'nullable|string|unique:alumni_donations',
            'notes'          => 'nullable|string',
        ]);

        $this->donationService->create($validated);

        return redirect()->route('alumni.donations.index')->with('success', 'Donation recorded successfully');
    }

    public function confirm(int $donation)
    {
        $this->donationService->confirm($donation);

        return redirect()->back()->with('success', 'Donation confirmed successfully');
    }
}
