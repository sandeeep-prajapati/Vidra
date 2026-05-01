@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('financialReport.index') }}" style="color:#64748b;text-decoration:none;">Financial Reports</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Report #{{ $financialReport->report_id }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Financial Report #{{ $financialReport->report_id }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Generated {{ $financialReport->generated_at?->format('d M Y, H:i') }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('financialReport.create')" color="primary">New Report</x-core-package::btn>
        <x-core-package::btn :href="route('financialReport.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;max-width:48rem;">

<x-core-package::card title="Report Summary">
    <dl style="display:flex;flex-direction:column;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Report Type</dt>
            <dd style="margin:0;">
                <x-core-package::badge color="{{ $financialReport->report_type === 'Income' ? 'green' : 'red' }}">
                    {{ $financialReport->report_type }}
                </x-core-package::badge>
            </dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Period Start</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $financialReport->report_period_start?->format('d M Y') }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Period End</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $financialReport->report_period_end?->format('d M Y') }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Generated At</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $financialReport->generated_at?->format('d M Y, H:i') }}</dd>
        </div>
    </dl>
</x-core-package::card>

<x-core-package::card title="Total Amount">
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:1rem 0;">
        <p style="font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .5rem;">
            Total {{ $financialReport->report_type }}
        </p>
        <p style="font-size:2.25rem;font-weight:800;color:{{ $financialReport->report_type === 'Income' ? '#0f766e' : '#dc2626' }};margin:0;">
            ₹{{ number_format($financialReport->total_amount, 2) }}
        </p>
        <p style="font-size:.75rem;color:#94a3b8;margin:.5rem 0 0;">
            {{ $financialReport->report_period_start?->format('d M Y') }} — {{ $financialReport->report_period_end?->format('d M Y') }}
        </p>
    </div>
</x-core-package::card>

</div>

@endsection
