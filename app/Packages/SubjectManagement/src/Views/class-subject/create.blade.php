@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('class-subjects.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Class-Subject Assignments</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Assign Subject</span>
</nav>
@endsection

@section('content')
@include('subject-management::class-subject._form', [
    'classSubject' => null,
    'action'       => route('class-subjects.store'),
    'method'       => 'POST',
    'title'        => 'Assign Subject to Class',
    'classes'      => $classes,
    'subjects'     => $subjects,
])
@endsection
