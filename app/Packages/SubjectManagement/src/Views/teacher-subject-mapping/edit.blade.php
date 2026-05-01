@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacher-subject-mappings.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher-Subject Mappings</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Mapping</span>
</nav>
@endsection

@section('content')
@include('subject-management::teacher-subject-mapping._form', [
    'teacherSubjectMapping' => $teacherSubjectMapping,
    'action'                => route('teacher-subject-mappings.update', $teacherSubjectMapping),
    'method'                => 'PUT',
    'title'                 => 'Edit Teacher-Subject Mapping',
    'teachers'              => $teachers,
    'classes'               => $classes,
    'subjects'              => $subjects,
    'sections'              => $sections,
])
@endsection
