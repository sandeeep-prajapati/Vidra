@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacher-subject-mappings.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher-Subject Mappings</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Assign Teacher</span>
</nav>
@endsection

@section('content')
@include('subject-management::teacher-subject-mapping._form', [
    'teacherSubjectMapping' => null,
    'action'                => route('teacher-subject-mappings.store'),
    'method'                => 'POST',
    'title'                 => 'Assign Teacher to Subject',
    'teachers'              => $teachers,
    'classes'               => $classes,
    'subjects'              => $subjects,
    'sections'              => $sections,
])
@endsection
