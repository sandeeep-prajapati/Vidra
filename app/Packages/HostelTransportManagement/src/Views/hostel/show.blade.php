@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('hostels.index') }}" style="color:#64748b;text-decoration:none;">Hostels</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $hostel->hostel_name }}</span>
</nav>
@endsection

@section('content')
<div style="max-width:900px;margin:0 auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $hostel->hostel_name }}</h1>
        <div style="display:flex;gap:.5rem;">
            <x-core-package::btn :href="route('hostels.edit', $hostel)" color="secondary">Edit</x-core-package::btn>
            <x-core-package::btn :href="route('hostels.index')" color="ghost">Back</x-core-package::btn>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-bottom:1.25rem;">
        <x-core-package::stats-card label="Total Capacity" value="{{ $hostel->total_capacity }}" />
        <x-core-package::stats-card label="Available Beds" value="{{ $hostel->available_capacity }}" />
        <x-core-package::stats-card label="Total Rooms" value="{{ $hostel->rooms->count() }}" />
    </div>

    <x-core-package::card title="Hostel Details" style="margin-bottom:1.25rem;">
        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;font-size:.875rem;">
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Type</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $hostel->hostel_type }}</p>
            </div>
            <div>
                <p style="color:#94a3b8;font-size:.75rem;margin:0 0 .25rem;">Location</p>
                <p style="color:#1e293b;font-weight:500;margin:0;">{{ $hostel->location ?? '—' }}</p>
            </div>
        </div>
    </x-core-package::card>

    <x-core-package::card title="Rooms" :noPadding="true">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Room No.</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Capacity</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Occupied</th>
                </tr>
            </thead>
            <tbody>
            @forelse($hostel->rooms as $room)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">{{ $room->room_number }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $room->room_type }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $room->capacity }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$room->occupied >= $room->capacity ? 'red' : 'green'">
                        {{ $room->occupied }}/{{ $room->capacity }}
                    </x-core-package::badge>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding:2rem;text-align:center;color:#94a3b8;">No rooms yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </x-core-package::card>

</div>
@endsection
