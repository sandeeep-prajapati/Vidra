@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('subjects.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Subjects</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <a href="{{ route('subjects.show', $subject) }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">{{ $subject->subject_name }}</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('subject-management::subject._form', [
    'subject' => $subject,
    'action'  => route('subjects.update', $subject),
    'method'  => 'PUT',
    'title'   => 'Edit Subject',
])
@endsection
