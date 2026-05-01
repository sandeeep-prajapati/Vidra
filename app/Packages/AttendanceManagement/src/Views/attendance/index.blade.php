@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Attendance Overview</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Attendance Overview</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Today — {{ now()->format('l, d F Y') }}</p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('studentAttendance.create')" color="primary" size="sm">
            <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Mark Student
        </x-core-package::btn>
        <x-core-package::btn :href="route('teacherAttendance.create')" color="secondary" size="sm">
            <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Mark Teacher
        </x-core-package::btn>
    </div>
</div>

{{-- Stats grid --}}
<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-bottom:1.5rem;">

    {{-- Student stats --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
            <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#0ea5e9,#38bdf8);border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p style="font-size:.75rem;font-weight:600;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:.04em;">Students Today</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;">
            <div style="text-align:center;background:#f0fdf4;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#16a34a;margin:0;">{{ $stats['student_present_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#4ade80;color:#15803d;margin:.125rem 0 0;">Present</p>
            </div>
            <div style="text-align:center;background:#fef2f2;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#dc2626;margin:0;">{{ $stats['student_absent_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b91c1c;margin:.125rem 0 0;">Absent</p>
            </div>
            <div style="text-align:center;background:#fffbeb;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#d97706;margin:0;">{{ $stats['student_leave_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b45309;margin:.125rem 0 0;">Leave</p>
            </div>
        </div>
    </div>

    {{-- Teacher stats --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
            <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#8b5cf6,#c4b5fd);border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p style="font-size:.75rem;font-weight:600;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:.04em;">Teachers Today</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;">
            <div style="text-align:center;background:#f0fdf4;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#16a34a;margin:0;">{{ $stats['teacher_present_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#15803d;margin:.125rem 0 0;">Present</p>
            </div>
            <div style="text-align:center;background:#fef2f2;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#dc2626;margin:0;">{{ $stats['teacher_absent_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b91c1c;margin:.125rem 0 0;">Absent</p>
            </div>
            <div style="text-align:center;background:#fffbeb;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#d97706;margin:0;">{{ $stats['teacher_leave_today'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b45309;margin:.125rem 0 0;">Leave</p>
            </div>
        </div>
    </div>

    {{-- Leave & Holiday stats --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem;">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
            <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#f59e0b,#fcd34d);border-radius:.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1.125rem;height:1.125rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p style="font-size:.75rem;font-weight:600;color:#94a3b8;margin:0;text-transform:uppercase;letter-spacing:.04em;">Pending & Holidays</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;">
            <div style="text-align:center;background:#fef3c7;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#d97706;margin:0;">{{ $stats['pending_student_leaves'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b45309;margin:.125rem 0 0;">Stu. Leave</p>
            </div>
            <div style="text-align:center;background:#fef3c7;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#d97706;margin:0;">{{ $stats['pending_teacher_leaves'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#b45309;margin:.125rem 0 0;">Tea. Leave</p>
            </div>
            <div style="text-align:center;background:#eff6ff;border-radius:.5rem;padding:.625rem .5rem;">
                <p style="font-size:1.375rem;font-weight:700;color:#2563eb;margin:0;">{{ $stats['total_holidays'] }}</p>
                <p style="font-size:.65rem;font-weight:600;color:#1d4ed8;margin:.125rem 0 0;">Holidays</p>
            </div>
        </div>
    </div>

</div>

{{-- Quick links --}}
<div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:.75rem;margin-bottom:1.5rem;">
    @php
    $modules = [
        ['route'=>'studentAttendance.index','label'=>'Student Attendance','color'=>'#0ea5e9','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['route'=>'teacherAttendance.index','label'=>'Teacher Attendance','color'=>'#8b5cf6','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['route'=>'studentLeaveRequest.index','label'=>'Student Leave','color'=>'#f59e0b','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route'=>'teacherLeaveRequest.index','label'=>'Teacher Leave','color'=>'#ef4444','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route'=>'holiday.index','label'=>'Holidays','color'=>'#10b981','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
    ];
    @endphp
    @foreach($modules as $mod)
    <a href="{{ route($mod['route']) }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1rem;text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:.5rem;text-align:center;transition:box-shadow .15s,border-color .15s;"
        onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)';this.style.borderColor='{{ $mod['color'] }}40';"
        onmouseout="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
        <div style="width:2.25rem;height:2.25rem;background:{{ $mod['color'] }}1a;border-radius:.5rem;display:flex;align-items:center;justify-content:center;">
            <svg style="width:1.125rem;height:1.125rem;color:{{ $mod['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $mod['icon'] }}"/>
            </svg>
        </div>
        <p style="font-size:.75rem;font-weight:600;color:#374151;margin:0;line-height:1.3;">{{ $mod['label'] }}</p>
    </a>
    @endforeach
</div>

{{-- Bottom grid: recent activity + pending leaves + upcoming holidays --}}
<div style="display:grid;grid-template-columns:1fr 1fr 20rem;gap:1.25rem;align-items:start;">

    {{-- Today's student attendance --}}
    <x-core-package::card title="Student Attendance Today">
        @if($recentStudentAttendance->isEmpty())
        <p style="font-size:.8125rem;color:#94a3b8;padding:.5rem 0;">No student attendance marked for today.</p>
        @else
        <div style="display:flex;flex-direction:column;gap:.5rem;">
            @foreach($recentStudentAttendance as $rec)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .625rem;background:#f8fafc;border-radius:.5rem;">
                <div>
                    <p style="font-size:.8125rem;font-weight:600;color:#1e293b;margin:0;">
                        {{ $rec->student ? $rec->student->first_name.' '.$rec->student->last_name : 'Student #'.$rec->student_id }}
                    </p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.1rem 0 0;">
                        {{ $rec->batch?->batch_name ?? 'Batch #'.($rec->batch_id ?? '—') }}
                    </p>
                </div>
                <x-core-package::badge color="{{ $rec->status === 'Present' ? 'green' : ($rec->status === 'Absent' ? 'red' : 'yellow') }}">
                    {{ $rec->status }}
                </x-core-package::badge>
            </div>
            @endforeach
        </div>
        @endif
        <div style="margin-top:.875rem;">
            <x-core-package::btn :href="route('studentAttendance.index')" color="secondary" size="sm">View All</x-core-package::btn>
        </div>
    </x-core-package::card>

    {{-- Today's teacher attendance --}}
    <x-core-package::card title="Teacher Attendance Today">
        @if($recentTeacherAttendance->isEmpty())
        <p style="font-size:.8125rem;color:#94a3b8;padding:.5rem 0;">No teacher attendance marked for today.</p>
        @else
        <div style="display:flex;flex-direction:column;gap:.5rem;">
            @foreach($recentTeacherAttendance as $rec)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .625rem;background:#f8fafc;border-radius:.5rem;">
                <div>
                    <p style="font-size:.8125rem;font-weight:600;color:#1e293b;margin:0;">
                        {{ $rec->staff ? $rec->staff->first_name.' '.$rec->staff->last_name : 'Staff #'.$rec->staff_id }}
                    </p>
                </div>
                <x-core-package::badge color="{{ $rec->status === 'Present' ? 'green' : ($rec->status === 'Absent' ? 'red' : 'yellow') }}">
                    {{ $rec->status }}
                </x-core-package::badge>
            </div>
            @endforeach
        </div>
        @endif
        <div style="margin-top:.875rem;">
            <x-core-package::btn :href="route('teacherAttendance.index')" color="secondary" size="sm">View All</x-core-package::btn>
        </div>
    </x-core-package::card>

    {{-- Right column: pending leaves + upcoming holidays --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Pending student leaves --}}
        <x-core-package::card title="Pending Leave Requests">
            @if($pendingLeaves->isEmpty())
            <p style="font-size:.8125rem;color:#94a3b8;padding:.5rem 0;">No pending leave requests.</p>
            @else
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                @foreach($pendingLeaves as $leave)
                <a href="{{ route('studentLeaveRequest.show', $leave) }}" style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .625rem;background:#fffbeb;border-radius:.5rem;text-decoration:none;border:1px solid #fde68a;">
                    <div style="min-width:0;">
                        <p style="font-size:.8125rem;font-weight:600;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $leave->student ? $leave->student->first_name.' '.$leave->student->last_name : 'Student #'.$leave->student_id }}
                        </p>
                        <p style="font-size:.7rem;color:#94a3b8;margin:.1rem 0 0;">
                            {{ $leave->start_date->format('d M') }} — {{ $leave->end_date->format('d M') }}
                        </p>
                    </div>
                    <x-core-package::badge color="yellow">Pending</x-core-package::badge>
                </a>
                @endforeach
            </div>
            @endif
            <div style="margin-top:.875rem;">
                <x-core-package::btn :href="route('studentLeaveRequest.index', ['status'=>'Pending'])" color="warning" size="sm">View All</x-core-package::btn>
            </div>
        </x-core-package::card>

        {{-- Upcoming holidays --}}
        <x-core-package::card title="Upcoming Holidays">
            @if($upcomingHolidays->isEmpty())
            <p style="font-size:.8125rem;color:#94a3b8;padding:.5rem 0;">No upcoming holidays.</p>
            @else
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                @foreach($upcomingHolidays as $holiday)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem .625rem;background:#f0fdf4;border-radius:.5rem;border:1px solid #bbf7d0;">
                    <div>
                        <p style="font-size:.8125rem;font-weight:600;color:#1e293b;margin:0;">{{ $holiday->title }}</p>
                        <p style="font-size:.7rem;color:#94a3b8;margin:.1rem 0 0;">{{ $holiday->date->format('d M Y') }}</p>
                    </div>
                    @if($holiday->is_recurring)
                    <x-core-package::badge color="blue">Recurring</x-core-package::badge>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            <div style="margin-top:.875rem;">
                <x-core-package::btn :href="route('holiday.index')" color="secondary" size="sm">Manage</x-core-package::btn>
            </div>
        </x-core-package::card>

    </div>

</div>

@endsection
