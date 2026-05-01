@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacherAttendance.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher Attendance</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <a href="{{ route('teacherAttendance.show', $teacherAttendance) }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Record #{{ $teacherAttendance->attendance_id }}</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('attendance-management::teacher-attendance._form', [
    'record' => $teacherAttendance,
    'action' => route('teacherAttendance.update', $teacherAttendance),
    'method' => 'PUT',
    'title'  => 'Edit Teacher Attendance',
])
@endsection
