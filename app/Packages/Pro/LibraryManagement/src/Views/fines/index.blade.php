@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Library / Fines</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Fine Management</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Unpaid overdue fines</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('library.fines.pending')" color="secondary">Pending</x-core-package::btn>
        <x-core-package::btn :href="route('library.fines.report')" color="secondary">Report</x-core-package::btn>
    </div>
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif
@if(session('error'))
<x-core-package::alert type="error" style="margin-bottom:1rem;">{{ session('error') }}</x-core-package::alert>
@endif

{{-- Summary --}}
@if(isset($report))
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.25rem;">Total Fines</div>
        <div style="font-size:1.5rem;font-weight:700;color:#dc2626;">{{ $report['total_fines'] ?? 0 }}</div>
    </div>
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.25rem;">Total Amount</div>
        <div style="font-size:1.5rem;font-weight:700;color:#d97706;">₹{{ number_format($report['total_amount'] ?? 0, 2) }}</div>
    </div>
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.25rem;">Collected</div>
        <div style="font-size:1.5rem;font-weight:700;color:#059669;">₹{{ number_format($report['collected'] ?? 0, 2) }}</div>
    </div>
</div>
@endif

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Member</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Book</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Amount</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($fines as $fine)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $fine->issue->member->name ?? 'N/A' }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $fine->issue->member->membership_number ?? '' }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $fine->issue->book->title ?? 'N/A' }}</td>
                <td style="padding:.75rem 1rem;text-align:right;font-weight:600;color:#dc2626;">₹{{ number_format($fine->amount, 2) }}</td>
                <td style="padding:.75rem 1rem;">
                    @php $colors = ['unpaid'=>'red','paid'=>'green','waived'=>'indigo','partial'=>'yellow']; @endphp
                    <x-core-package::badge color="{{ $colors[$fine->status] ?? 'indigo' }}">{{ $fine->status }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    @can('manage_library_fines')
                    @if($fine->status === 'unpaid' || $fine->status === 'partial')
                    <div style="display:flex;gap:.5rem;">
                        <form method="POST" action="{{ route('library.fines.pay', $fine) }}" style="display:flex;gap:.375rem;align-items:center;">
                            @csrf
                            <input type="number" name="amount" step="0.01" min="0.01" value="{{ $fine->amount }}"
                                style="width:80px;padding:.25rem .5rem;border:1px solid #e2e8f0;border-radius:.375rem;font-size:.75rem;">
                            <x-core-package::btn type="submit" color="primary" size="sm">Pay</x-core-package::btn>
                        </form>
                        <form method="POST" action="{{ route('library.fines.waive', $fine) }}" onsubmit="return confirm('Waive this fine?')">
                            @csrf
                            <x-core-package::btn type="submit" color="secondary" size="sm">Waive</x-core-package::btn>
                        </form>
                    </div>
                    @endif
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No unpaid fines.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

@endsection
