@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('lesson-plans.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Lesson Plans</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
@include('subject-management::lesson-plan._form', [
    'lessonPlan'  => $lessonPlan,
    'action'      => route('lesson-plans.update', $lessonPlan),
    'method'      => 'PUT',
    'title'       => 'Edit Lesson Plan',
    'curriculums' => $curriculums,
])
@endsection
