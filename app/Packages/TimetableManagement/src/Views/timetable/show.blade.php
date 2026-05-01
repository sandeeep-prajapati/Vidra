@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('timetable.index') }}" style="color:#64748b;text-decoration:none;">Timetable</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Entry #{{ $timetable->timetable_id }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Timetable Entry</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
            {{ $timetable->schoolClass?->class_name }} – {{ $timetable->section?->section_name }} | {{ $timetable->day?->day_name }}
        </p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('timetable.edit', $timetable)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('timetable.destroy', $timetable) }}" onsubmit="return confirm('Delete this entry?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <x-core-package::card title="Scheduling Details">
        <dl style="display:grid;gap:.875rem;">
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Class</dt>
                <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $timetable->schoolClass?->class_name ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Section</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">{{ $timetable->section?->section_name ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Academic Year</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">{{ $timetable->academicYear?->year_range ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Day</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">{{ $timetable->day?->day_name ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Period</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">
                    {{ $timetable->period?->start_time }} – {{ $timetable->period?->end_time }}
                </dd>
            </div>
        </dl>
    </x-core-package::card>

    <x-core-package::card title="Assignment Details">
        <dl style="display:grid;gap:.875rem;">
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Subject</dt>
                <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $timetable->subject?->subject_name ?? '—' }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Teacher</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">
                    {{ $timetable->teacher?->first_name }} {{ $timetable->teacher?->last_name }}
                </dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Room</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">
                    {{ $timetable->room?->room_name }}
                    @if($timetable->room)
                    <x-core-package::badge color="blue">{{ $timetable->room->room_type }}</x-core-package::badge>
                    @endif
                </dd>
            </div>
        </dl>
    </x-core-package::card>
</div>

@endsection
