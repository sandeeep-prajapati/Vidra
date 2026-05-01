@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentAttendance.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Student Attendance</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Mark Attendance</span>
</nav>
@endsection

@section('content')
@include('attendance-management::student-attendance._form', [
    'record' => null,
    'action' => route('studentAttendance.store'),
    'method' => 'POST',
    'title'  => 'Mark Student Attendance',
])
@endsection
