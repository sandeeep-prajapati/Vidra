@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentLeaveRequest.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Student Leave Requests</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">New Request</span>
</nav>
@endsection

@section('content')
@include('attendance-management::student-leave-request._form', [
    'record' => null,
    'action' => route('studentLeaveRequest.store'),
    'method' => 'POST',
    'title'  => 'Submit Leave Request',
])
@endsection
