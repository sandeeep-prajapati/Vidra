@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Batches</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Batches</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage class batches combining class, section, and academic year</p>
    </div>
    <x-core-package::btn :href="route('batches.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Batch
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('batches.index') }}"
          style="display:grid;grid-template-columns:1fr 200px 200px 160px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Batch name or code"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="class_id" label="Class">
            <option value="" @selected(!request('class_id'))>All Classes</option>
            @foreach($classes as $class)
            <option value="{{ $class->class_id }}" @selected((string)request('class_id') === (string)$class->class_id)>
                {{ $class->class_name }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="academic_year_id" label="Academic Year">
            <option value="" @selected(!request('academic_year_id'))>All Years</option>
            @foreach($academicYears as $year)
            <option value="{{ $year->academic_year_id }}" @selected((string)request('academic_year_id') === (string)$year->academic_year_id)>
                {{ $year->year_range }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="status" label="Status">
            <option value="" @selected(!request('status'))>All</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('batches.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batch</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class / Section</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Academic Year</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Dates</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:1rem;height:1rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $item->batch_name }}</p>
                            @if($item->batch_code)
                            <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">Code: {{ $item->batch_code }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;">
                    <p style="font-size:.8125rem;font-weight:500;color:#1e293b;margin:0;">{{ $item->schoolClass->class_name ?? '—' }}</p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">Section {{ $item->section->section_name ?? '—' }}</p>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->academicYear->year_range ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <p style="font-size:.8125rem;color:#374151;margin:0;">{{ optional($item->start_date)->format('M d, Y') ?: '—' }}</p>
                    @if($item->start_time)
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">
                        {{ optional($item->start_time)->format('h:i A') }}
                        @if($item->end_time) – {{ optional($item->end_time)->format('h:i A') }} @endif
                    </p>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$item->is_active ? 'green' : 'gray'">
                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('batches.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('batches.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('batches.destroy', $item) }}"
                              onsubmit="return confirm('Delete {{ $item->batch_name }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">
                    No batches found.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
