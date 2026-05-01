@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('batches.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Batches</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">New Batch</span>
</nav>
@endsection

@section('content')
@include('class-management::batch._form', [
    'batch'        => null,
    'action'       => route('batches.store'),
    'method'       => 'POST',
    'title'        => 'Create Batch',
    'classes'      => $classes,
    'sections'     => $sections,
    'academicYears'=> $academicYears,
])
@endsection
