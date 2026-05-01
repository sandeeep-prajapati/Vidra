@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Exam Schedules</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Exam Schedules</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage exam schedules per class and subject</p>
    </div>
    <x-core-package::btn :href="route('examSchedule.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Schedule
    </x-core-package::btn>
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1.25rem;">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Exam</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Subject</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date & Time</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Marks</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $item->exam?->exam_name ?? 'Exam #'.$item->exam_id }}</p>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->schoolClass?->class_name ?? 'Class #'.$item->class_id }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->subject?->subject_name ?? 'Subject #'.$item->subject_id }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    {{ $item->exam_date?->format('d M Y') ?? '—' }}<br>
                    <span style="font-size:.7rem;">{{ $item->start_time?->format('H:i') ?? '' }} - {{ $item->end_time?->format('H:i') ?? '' }}</span>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    Total: {{ $item->total_marks ?? '-' }} / Pass: {{ $item->passing_marks ?? '-' }}
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('examSchedule.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('examSchedule.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('examSchedule.destroy', $item) }}" onsubmit="return confirm('Delete this schedule?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No exam schedules found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection