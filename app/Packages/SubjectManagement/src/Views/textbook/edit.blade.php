@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('textbooks.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Textbooks</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('subject-management::textbook._form', [
    'textbook' => $textbook,
    'action'   => route('textbooks.update', $textbook),
    'method'   => 'PUT',
    'title'    => 'Edit Textbook',
    'subjects' => $subjects,
])
@endsection
