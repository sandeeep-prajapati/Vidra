@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('facilities.index') }}" style="color:#64748b;text-decoration:none;">Facilities</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Add Facility</span>
</nav>
@endsection

@section('content')
@include('hostel-transport::facility._form', [
    'title'  => 'Add New Facility',
    'action' => route('facilities.store'),
    'method' => 'POST',
])
@endsection
