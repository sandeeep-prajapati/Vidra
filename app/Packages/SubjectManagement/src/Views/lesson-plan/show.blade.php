@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('lesson-plans.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Lesson Plans</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $lessonPlan->topic_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $lessonPlan->topic_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
            {{ $lessonPlan->curriculum->subject->subject_name ?? '—' }} &middot; {{ $lessonPlan->curriculum->academicYear->year_range ?? '—' }}
        </p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('lesson-plans.edit', $lessonPlan)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('lesson-plans.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="max-width:760px;">
    <x-core-package::card title="Lesson Plan Details">
        <div style="display:flex;flex-direction:column;gap:1rem;font-size:.875rem;">
            @if($lessonPlan->start_date || $lessonPlan->end_date)
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Date Range</p>
                <p style="font-weight:500;color:#1e293b;margin:0;">
                    {{ optional($lessonPlan->start_date)->format('M d, Y') ?: '—' }}
                    @if($lessonPlan->end_date) → {{ $lessonPlan->end_date->format('M d, Y') }} @endif
                </p>
            </div>
            @endif
            @if($lessonPlan->objectives)
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Learning Objectives</p>
                <p style="color:#475569;margin:0;line-height:1.7;white-space:pre-line;">{{ $lessonPlan->objectives }}</p>
            </div>
            @endif
        </div>
    </x-core-package::card>

    <div style="margin-top:1.25rem;">
        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('lesson-plans.destroy', $lessonPlan) }}">
                @csrf @method('DELETE')
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete this lesson plan?')">
                    Delete Lesson Plan
                </x-core-package::btn>
            </form>
        </x-core-package::card>
    </div>
</div>
@endsection
