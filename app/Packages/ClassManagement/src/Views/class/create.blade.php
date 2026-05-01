@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('classes.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Classes</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">New Class</span>
</nav>
@endsection

@section('content')
@include('class-management::class._form', [
    'schoolClass'  => null,
    'action'       => route('classes.store'),
    'method'       => 'POST',
    'title'        => 'Create Class',
    'academicYears'=> $academicYears,
])
@endsection
