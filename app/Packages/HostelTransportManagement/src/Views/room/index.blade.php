@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Hostel Rooms</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Hostel Rooms</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage rooms within hostels</p>
    </div>
    <x-core-package::btn :href="route('hostel-rooms.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Room
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('hostel-rooms.index') }}"
          style="display:grid;grid-template-columns:1fr 220px 180px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Room number"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="hostel_id" label="Hostel">
            <option value="" @selected(!request('hostel_id'))>All Hostels</option>
            @foreach($hostels as $h)
            <option value="{{ $h->hostel_id }}" @selected((string)request('hostel_id') === (string)$h->hostel_id)>{{ $h->hostel_name }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="room_type" label="Room Type">
            <option value="" @selected(!request('room_type'))>All Types</option>
            @foreach(['Single','Double','Triple','Quad'] as $t)
            <option value="{{ $t }}" @selected(request('room_type') === $t)>{{ $t }}</option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('hostel-rooms.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Room</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Hostel</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Occupancy</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">{{ $item->room_number }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->hostel->hostel_name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="blue">{{ $item->room_type }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$item->occupied >= $item->capacity ? 'red' : ($item->occupied > 0 ? 'yellow' : 'green')">
                        {{ $item->occupied }}/{{ $item->capacity }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('hostel-rooms.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('hostel-rooms.destroy', $item) }}"
                              onsubmit="return confirm('Delete room {{ $item->room_number }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No rooms found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
