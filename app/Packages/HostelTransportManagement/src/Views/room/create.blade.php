@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('hostel-rooms.index') }}" style="color:#64748b;text-decoration:none;">Hostel Rooms</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Add Room</span>
</nav>
@endsection

@section('content')
@include('hostel-transport::room._form', [
    'title'  => 'Add New Room',
    'action' => route('hostel-rooms.store'),
    'method' => 'POST',
])
@endsection
