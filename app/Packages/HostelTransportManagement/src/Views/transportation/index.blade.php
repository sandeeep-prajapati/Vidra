@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Transportation</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Transportation Services</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage buses, vans and shuttle services</p>
    </div>
    <x-core-package::btn :href="route('transportation.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Transport
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('transportation.index') }}"
          style="display:grid;grid-template-columns:1fr 180px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Transport name or route"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="type" label="Type">
            <option value="" @selected(!request('type'))>All Types</option>
            @foreach(['Bus','Van','Shuttle'] as $t)
            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('transportation.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Service</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Route</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Capacity</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Departure</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Students</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:1rem;height:1rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8m-8 0H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-2m-8 0v2a2 2 0 002 2h4a2 2 0 002-2v-2"/></svg>
                        </div>
                        <span style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $item->transport_name }}</span>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;">
                    @php $typeColor = ['Bus'=>'blue','Van'=>'green','Shuttle'=>'orange'][$item->transport_type] ?? 'gray'; @endphp
                    <x-core-package::badge :color="$typeColor">{{ $item->transport_type }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->route ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->capacity }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->departure_time ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->student_transports_count }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('transportation.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('transportation.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('transportation.destroy', $item) }}"
                              onsubmit="return confirm('Delete {{ $item->transport_name }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No transport services found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
