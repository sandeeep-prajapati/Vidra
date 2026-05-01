@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacherLeaveRequest.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher Leave Requests</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <a href="{{ route('teacherLeaveRequest.show', $teacherLeaveRequest) }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Request #{{ $teacherLeaveRequest->leave_request_id }}</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('attendance-management::teacher-leave-request._form', [
    'record' => $teacherLeaveRequest,
    'action' => route('teacherLeaveRequest.update', $teacherLeaveRequest),
    'method' => 'PUT',
    'title'  => 'Edit Leave Request',
])
@endsection
