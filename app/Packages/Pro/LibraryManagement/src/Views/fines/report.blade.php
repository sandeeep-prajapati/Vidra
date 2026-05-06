@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.fines.index') }}" style="color:#64748b;text-decoration:none;">Fines</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Report</span>
</nav>
@endsection

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Fine Collection Report</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Summary of fine collection across all periods</p>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
    @foreach([
        ['Total Fines','total_fines','#4f46e5'],
        ['Total Amount','total_amount','#dc2626'],
        ['Collected','collected','#059669'],
        ['Pending','pending','#d97706'],
        ['Waived','waived','#6366f1'],
    ] as [$label,$key,$color])
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.375rem;">{{ $label }}</div>
        <div style="font-size:1.5rem;font-weight:700;color:{{ $color }};">
            @if(in_array($key,['total_amount','collected','pending','waived']))
            ₹{{ number_format($report[$key] ?? 0, 2) }}
            @else
            {{ $report[$key] ?? 0 }}
            @endif
        </div>
    </div>
    @endforeach
</div>

<x-core-package::card>
    <div style="text-align:center;padding:1rem 0;color:#64748b;font-size:.875rem;">
        Detailed reports coming soon. Use the overdue and fines index for day-to-day operations.
    </div>
</x-core-package::card>

@endsection
