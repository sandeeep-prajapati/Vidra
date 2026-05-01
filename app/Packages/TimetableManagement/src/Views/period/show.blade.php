@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('period.index') }}" style="color:#64748b;text-decoration:none;">Periods</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Period #{{ $period->period_id }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Period #{{ $period->period_id }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $period->start_time }} – {{ $period->end_time }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('period.edit', $period)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('period.destroy', $period) }}" onsubmit="return confirm('Delete this period?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<x-core-package::card title="Period Details" style="max-width:400px;">
    <dl style="display:grid;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Start Time</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $period->start_time }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">End Time</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $period->end_time }}</dd>
        </div>
    </dl>
</x-core-package::card>

@endsection
