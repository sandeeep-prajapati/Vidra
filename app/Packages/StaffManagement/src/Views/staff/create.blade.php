@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('staff.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Staff</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Add Staff Member</span>
</nav>
@endsection

@section('content')
@include('staff-management::staff._form', [
    'staffMember' => null,
    'action'      => route('staff.store'),
    'method'      => 'POST',
    'title'       => 'Add Staff Member',
])
@endsection
