@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Substitute Assignments</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Substitute Assignments</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Track teacher substitutions</p>
    </div>
    <x-core-package::btn :href="route('substituteAssignment.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Substitution
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('substituteAssignment.index') }}"
          style="display:grid;grid-template-columns:1fr auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="date" label="Filter by Date" type="date" value="{{ request('date') }}" />
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('substituteAssignment.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Timetable Slot</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Original Teacher</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Substitute Teacher</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">{{ $item->date_of_substitution?->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    @if($item->timetable)
                    {{ $item->timetable->day?->day_name }} |
                    {{ $item->timetable->period?->start_time }} –
                    {{ $item->timetable->period?->end_time }} |
                    {{ $item->timetable->subject?->subject_name }}
                    @else
                    —
                    @endif
                </td>
                <td style="padding:.75rem 1rem;color:#dc2626;">{{ $item->originalTeacher?->first_name }} {{ $item->originalTeacher?->last_name }}</td>
                <td style="padding:.75rem 1rem;color:#16a34a;">{{ $item->substituteTeacher?->first_name }} {{ $item->substituteTeacher?->last_name }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('substituteAssignment.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('substituteAssignment.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('substituteAssignment.destroy', $item) }}" onsubmit="return confirm('Delete this substitution?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No substitutions recorded.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
