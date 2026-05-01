@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Facilities</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Campus Facilities</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage gym, library, labs and other campus facilities</p>
    </div>
    <x-core-package::btn :href="route('facilities.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Facility
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('facilities.index') }}"
          style="display:grid;grid-template-columns:1fr 180px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Facility name"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="type" label="Type">
            <option value="" @selected(!request('type'))>All Types</option>
            @foreach(['Sports','Library','Cafeteria','Lab'] as $t)
            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('facilities.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Facility</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Location</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Capacity</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Available</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Bookings</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#8b5cf6,#a78bfa);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:1rem;height:1rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $item->facility_name }}</span>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;">
                    @php $typeColor = ['Sports'=>'green','Library'=>'blue','Cafeteria'=>'orange','Lab'=>'purple'][$item->facility_type] ?? 'gray'; @endphp
                    <x-core-package::badge :color="$typeColor">{{ $item->facility_type }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->location ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->capacity }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$item->available_capacity > 0 ? 'green' : 'red'">
                        {{ $item->available_capacity }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->bookings_count }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('facilities.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('facilities.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('facilities.destroy', $item) }}"
                              onsubmit="return confirm('Delete {{ $item->facility_name }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No facilities found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
