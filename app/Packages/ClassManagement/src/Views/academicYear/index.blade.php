@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Academic Years</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Academic Years</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Define and manage school academic years</p>
    </div>
    <x-core-package::btn :href="route('academic-years.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Academic Year
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('academic-years.index') }}"
          style="display:grid;grid-template-columns:1fr 180px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Year range (e.g. 2024-2025)"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="is_current" label="Status">
            <option value="" @selected(!request()->filled('is_current'))>All</option>
            <option value="1" @selected(request('is_current') === '1')>Current</option>
            <option value="0" @selected(request('is_current') === '0')>Past</option>
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('academic-years.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Year</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Duration</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Classes</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batches</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:1rem;height:1rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $item->year_range }}</p>
                            @if($item->description)
                            <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">{{ Str::limit($item->description, 50) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;">
                    <p style="font-size:.8125rem;color:#374151;margin:0;">{{ optional($item->start_date)->format('M d, Y') }}</p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">to {{ optional($item->end_date)->format('M d, Y') }}</p>
                </td>
                <td style="padding:.75rem 1rem;">
                    <span style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $item->classes->count() }}</span>
                    <span style="font-size:.75rem;color:#94a3b8;"> classes</span>
                </td>
                <td style="padding:.75rem 1rem;">
                    <span style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $item->batches->count() }}</span>
                    <span style="font-size:.75rem;color:#94a3b8;"> batches</span>
                </td>
                <td style="padding:.75rem 1rem;">
                    @if($item->is_current)
                    <x-core-package::badge color="green">Current</x-core-package::badge>
                    @else
                    <x-core-package::badge color="gray">Past</x-core-package::badge>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('academic-years.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('academic-years.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('academic-years.destroy', $item) }}"
                              onsubmit="return confirm('Delete {{ $item->year_range }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">
                    No academic years found.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
