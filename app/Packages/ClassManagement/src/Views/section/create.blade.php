@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('sections.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Sections</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">New Section</span>
</nav>
@endsection

@section('content')
@include('class-management::section._form', [
    'section' => null,
    'action'  => route('sections.store'),
    'method'  => 'POST',
    'title'   => 'Create Section',
    'classes' => $classes,
    'staff'   => $staff,
])
@endsection
