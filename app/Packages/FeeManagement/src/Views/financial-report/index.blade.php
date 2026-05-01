@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Financial Reports</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Financial Reports</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Income and expense summaries by period</p>
    </div>
    <x-core-package::btn :href="route('financialReport.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Generate Report
    </x-core-package::btn>
</div>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Period</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Total Amount</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Generated At</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#64748b;">{{ $item->report_id }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $item->report_type === 'Income' ? 'green' : 'red' }}">
                        {{ $item->report_type }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    {{ $item->report_period_start?->format('d M Y') }} — {{ $item->report_period_end?->format('d M Y') }}
                </td>
                <td style="padding:.75rem 1rem;text-align:right;font-weight:700;color:{{ $item->report_type === 'Income' ? '#0f766e' : '#dc2626' }};">
                    ₹{{ number_format($item->total_amount, 2) }}
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->generated_at?->format('d M Y, H:i') }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('financialReport.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <form method="POST" action="{{ route('financialReport.destroy', $item) }}" onsubmit="return confirm('Delete this report?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No reports generated yet.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
