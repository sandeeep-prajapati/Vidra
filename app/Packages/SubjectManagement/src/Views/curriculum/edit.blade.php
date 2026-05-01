@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('curriculums.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Curriculum</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('subject-management::curriculum._form', [
    'curriculum'    => $curriculum,
    'action'        => route('curriculums.update', $curriculum),
    'method'        => 'PUT',
    'title'         => 'Edit Curriculum',
    'subjects'      => $subjects,
    'academicYears' => $academicYears,
])
@endsection
