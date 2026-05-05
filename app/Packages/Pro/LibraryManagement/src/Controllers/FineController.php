<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use App\Packages\Pro\LibraryManagement\Services\FineService;
use App\Packages\Pro\LibraryManagement\Repositories\FineRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FineController extends BaseController
{
    public function __construct(
        private FineService $fineService,
        private FineRepository $fineRepository
    ) {}

    public function index(): View
    {
        $this->authorize('view_library-management');
        $fines = $this->fineRepository->all();
        $stats = [
            'total_pending' => $this->fineRepository->getTotalPending(),
            'total_collected' => $this->fineRepository->getTotalCollected(),
        ];
        return view('library-management::fines.index', compact('fines', 'stats'));
    }

    public function pending(): View
    {
        $this->authorize('view_library-management');
        $fines = $this->fineRepository->getPending();
        return view('library-management::fines.pending', compact('fines'));
    }

    public function recordPayment(Request $request, LibraryFine $fine): RedirectResponse
    {
        $this->authorize('edit_library-management_item');
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $fine->balance_amount,
        ]);

        if ($this->fineService->recordPayment($fine, (float) $validated['amount'])) {
            return back()->with('success', 'Payment recorded successfully');
        }

        return back()->withErrors(['error' => 'Unable to record payment']);
    }

    public function waive(Request $request, LibraryFine $fine): RedirectResponse
    {
        $this->authorize('edit_library-management_item');
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->fineService->waiveFine($fine, auth()->id(), $validated['reason']);
        return back()->with('success', 'Fine waived successfully');
    }

    public function report(): View
    {
        $this->authorize('view_library-management');
        $report = $this->fineService->getCollectionReport();
        return view('library-management::fines.report', compact('report'));
    }
}
