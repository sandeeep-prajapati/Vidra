@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Student Marks</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Student Marks</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Enter marks for students per subject</p>
    </div>
    <x-core-package::btn :href="route('studentMark.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Marks
    </x-core-package::btn>
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1.25rem;">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('studentMark.index') }}"
          style="display:grid;grid-template-columns:1fr 160px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="student_id" label="Student ID" type="number" placeholder="Filter by student ID" value="{{ request('student_id') }}" />
        <x-core-package::form.select name="schedule_id" label="Exam Schedule">
            <option value="">All Schedules</option>
            @foreach($examSchedules ?? [] as $schedule)
            <option value="{{ $schedule->schedule_id }}" @selected(request('schedule_id') == $schedule->schedule_id)>
                {{ $schedule->exam?->exam_name ?? 'Exam #'.$schedule->exam_id }} - {{ $schedule->subject?->subject_name ?? 'Subject #'.$schedule->subject_id }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('studentMark.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Student</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Exam Schedule</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Marks</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Grade</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $item->student?->first_name ?? 'Student #'.$item->student_id }} {{ $item->student?->last_name ?? '' }}</p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">ID: {{ $item->student_id }}</p>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    {{ $item->schedule?->exam?->exam_name ?? 'Exam #'.$item->schedule?->exam_id }}<br>
                    <span style="font-size:.7rem;">{{ $item->schedule?->subject?->subject_name ?? 'Subject #'.$item->schedule?->subject_id }}</span>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->marks_obtained ?? '—' }} / {{ $item->schedule?->total_marks ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $item->grade ? 'blue' : 'gray' }}">{{ $item->grade ?? '—' }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('studentMark.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('studentMark.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('studentMark.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No marks found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection