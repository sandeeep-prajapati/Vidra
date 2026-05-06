@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.fines.index') }}" style="color:#64748b;text-decoration:none;">Fines</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Pending</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Pending Fines</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">All pending and partial payments</p>
    </div>
    <x-core-package::btn :href="route('library.fines.index')" color="secondary">All Fines</x-core-package::btn>
</div>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Member</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Book</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Amount</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Paid</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($fines as $fine)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $fine->issue->member->name ?? 'N/A' }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $fine->issue->book->title ?? 'N/A' }}</td>
                <td style="padding:.75rem 1rem;text-align:right;font-weight:600;color:#dc2626;">₹{{ number_format($fine->amount, 2) }}</td>
                <td style="padding:.75rem 1rem;text-align:right;color:#059669;">₹{{ number_format($fine->amount_paid ?? 0, 2) }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $fine->status === 'partial' ? 'yellow' : 'red' }}">{{ $fine->status }}</x-core-package::badge>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No pending fines.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

@endsection
