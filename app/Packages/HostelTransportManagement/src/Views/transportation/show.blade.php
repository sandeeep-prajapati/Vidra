@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('transportation.index') }}" style="color:#64748b;text-decoration:none;">Transportation</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $transportation->transport_name }}</span>
</nav>
@endsection

@section('content')
<div style="max-width:900px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $transportation->transport_name }}</h1>
        <div style="display:flex;gap:.5rem;">
            <x-core-package::btn :href="route('transportation.edit', $transportation)" color="secondary">Edit</x-core-package::btn>
            <x-core-package::btn :href="route('transportation.index')" color="ghost">Back</x-core-package::btn>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-bottom:1.25rem;">
        <x-core-package::stats-card label="Type" value="{{ $transportation->transport_type }}" />
        <x-core-package::stats-card label="Capacity" value="{{ $transportation->capacity }}" />
        <x-core-package::stats-card label="Assigned Students" value="{{ $transportation->studentTransports->count() }}" />
    </div>

    <x-core-package::card title="Service Details" style="margin-bottom:1.25rem;">
        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;font-size:.875rem;">
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Route</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $transportation->route ?? '—' }}</p>
            </div>
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Departure Time</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $transportation->departure_time ?? '—' }}</p>
            </div>
        </div>
    </x-core-package::card>

    <x-core-package::card title="Assigned Students" :noPadding="true">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Student ID</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Pickup</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Drop</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Since</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transportation->studentTransports as $st)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">#{{ $st->student_id }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $st->pickup_location ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $st->drop_location ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $st->assigned_date?->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding:2rem;text-align:center;color:#94a3b8;">No students assigned.</td></tr>
            @endforelse
            </tbody>
        </table>
    </x-core-package::card>
</div>
@endsection
