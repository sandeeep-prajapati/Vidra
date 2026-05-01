@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('student-transport.index') }}" style="color:#64748b;text-decoration:none;">Transport Assignments</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Assign Student</span>
</nav>
@endsection

@section('content')
@include('hostel-transport::student-transport._form', [
    'title'  => 'Assign Student to Transport',
    'action' => route('student-transport.store'),
    'method' => 'POST',
])
@endsection
