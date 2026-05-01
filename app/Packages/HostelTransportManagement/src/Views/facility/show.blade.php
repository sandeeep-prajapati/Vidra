@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('facilities.index') }}" style="color:#64748b;text-decoration:none;">Facilities</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $facility->facility_name }}</span>
</nav>
@endsection

@section('content')
<div style="max-width:900px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $facility->facility_name }}</h1>
        <div style="display:flex;gap:.5rem;">
            <x-core-package::btn :href="route('facilities.edit', $facility)" color="secondary">Edit</x-core-package::btn>
            <x-core-package::btn :href="route('facilities.index')" color="ghost">Back</x-core-package::btn>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-bottom:1.25rem;">
        <x-core-package::stats-card label="Total Capacity" value="{{ $facility->capacity }}" />
        <x-core-package::stats-card label="Available Slots" value="{{ $facility->available_capacity }}" />
        <x-core-package::stats-card label="Total Bookings" value="{{ $facility->bookings->count() }}" />
    </div>

    <x-core-package::card title="Facility Details" style="margin-bottom:1.25rem;">
        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;font-size:.875rem;">
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Type</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $facility->facility_type }}</p>
            </div>
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Location</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $facility->location ?? '—' }}</p>
            </div>
        </div>
    </x-core-package::card>

    <x-core-package::card title="Recent Bookings" :noPadding="true">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Student ID</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Date</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Time</th>
                </tr>
            </thead>
            <tbody>
            @forelse($facility->bookings->take(10) as $booking)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">#{{ $booking->student_id }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $booking->booking_date?->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $booking->start_time }} – {{ $booking->end_time }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="padding:2rem;text-align:center;color:#94a3b8;">No bookings yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </x-core-package::card>
</div>
@endsection
