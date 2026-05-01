@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('academic-years.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Academic Years</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <a href="{{ route('academic-years.show', $academicYear) }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">{{ $academicYear->year_range }}</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('class-management::academicYear._form', [
    'academicYear' => $academicYear,
    'action'       => route('academic-years.update', $academicYear),
    'method'       => 'PUT',
    'title'        => 'Edit Academic Year',
])
@endsection
