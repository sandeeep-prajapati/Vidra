@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacherAttendance.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher Attendance</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Record #{{ $teacherAttendance->attendance_id }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#8b5cf6,#c4b5fd);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.375rem;height:1.375rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Teacher Attendance #{{ $teacherAttendance->attendance_id }}</h1>
                <x-core-package::badge color="{{ $teacherAttendance->status === 'Present' ? 'green' : ($teacherAttendance->status === 'Absent' ? 'red' : 'yellow') }}">
                    {{ $teacherAttendance->status }}
                </x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $teacherAttendance->date->format('d M Y') }}</p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('teacherAttendance.edit', $teacherAttendance)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('teacherAttendance.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    <x-core-package::card title="Attendance Details">
        <div style="display:flex;flex-direction:column;gap:.875rem;">
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Staff Member</p>
                <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">
                    {{ $teacherAttendance->staff ? $teacherAttendance->staff->first_name.' '.$teacherAttendance->staff->last_name : 'Staff #'.$teacherAttendance->staff_id }}
                </p>
            </div>
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Date</p>
                <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">{{ $teacherAttendance->date->format('d M Y') }}</p>
            </div>
            @if($teacherAttendance->remarks)
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Remarks</p>
                <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $teacherAttendance->remarks }}</p>
            </div>
            @endif
        </div>
    </x-core-package::card>

    <x-core-package::card title="Danger Zone">
        <form method="POST" action="{{ route('teacherAttendance.destroy', $teacherAttendance) }}">
            @csrf @method('DELETE')
            <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                Permanently delete this attendance record.
            </p>
            <x-core-package::btn type="submit" color="danger"
                onclick="return confirm('Delete this attendance record? This cannot be undone.')">
                Delete Record
            </x-core-package::btn>
        </form>
    </x-core-package::card>

</div>

@endsection
