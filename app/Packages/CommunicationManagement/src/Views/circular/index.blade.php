@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Circulars</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Circulars</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage important notices and announcements</p>
    </div>
    <x-core-package::btn :href="route('circular.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Circular
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('circular.index') }}"
          style="display:grid;grid-template-columns:200px 1fr auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="target_audience" label="Audience">
            <option value="">All Audiences</option>
            @foreach(['All','Students','Parents','Teachers','Staff'] as $a)
            <option value="{{ $a }}" @selected(request('target_audience') === $a)>{{ $a }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.input name="search" label="Search by title" type="text" value="{{ request('search') }}" />
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('circular.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Title</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Audience</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Issued By</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Attachment</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            @php $audColors = ['All'=>'indigo','Students'=>'blue','Parents'=>'purple','Teachers'=>'green','Staff'=>'orange']; @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#94a3b8;font-size:.75rem;">{{ $item->circular_id }}</td>
                <td style="padding:.75rem 1rem;font-weight:600;color:#1e293b;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $audColors[$item->target_audience] ?? 'gray' }}">{{ $item->target_audience }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->issuer?->name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;white-space:nowrap;">{{ $item->issued_date->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    @if($item->attachment_url)
                        <a href="{{ $item->attachment_url }}" target="_blank" style="color:#4f46e5;font-size:.75rem;">View</a>
                    @else
                        <span style="color:#cbd5e1;">—</span>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('circular.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('circular.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('circular.destroy', $item) }}" onsubmit="return confirm('Delete this circular?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No circulars found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
