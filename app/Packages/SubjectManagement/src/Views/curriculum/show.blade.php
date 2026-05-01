@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('curriculums.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Curriculum</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $curriculum->subject->subject_name ?? 'Detail' }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">
            {{ $curriculum->subject->subject_name ?? '—' }} — {{ $curriculum->academicYear->year_range ?? '—' }}
        </h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Curriculum details and lesson plans</p>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;">
        <x-core-package::btn :href="route('curriculums.edit', $curriculum)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('curriculums.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1.25rem;">{{ session('success') }}</x-core-package::alert>
@endif

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">
    <div>
        {{-- Lesson Plans --}}
        <x-core-package::card :noPadding="true">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">Lesson Plans</p>
                <x-core-package::btn :href="route('lesson-plans.create').'?curriculum_id='.$curriculum->curriculum_id" color="primary" size="sm">+ Add Lesson Plan</x-core-package::btn>
            </div>
            @if($curriculum->lessonPlans->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Topic</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Dates</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($curriculum->lessonPlans as $plan)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $plan->topic_name }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">
                            @if($plan->start_date)
                                {{ $plan->start_date->format('M d') }} – {{ optional($plan->end_date)->format('M d, Y') ?: '?' }}
                            @else —
                            @endif
                        </td>
                        <td style="padding:.625rem 1rem;">
                            <div style="display:flex;gap:.375rem;">
                                <x-core-package::btn :href="route('lesson-plans.show', $plan)" color="primary" size="sm">View</x-core-package::btn>
                                <form method="POST" action="{{ route('lesson-plans.destroy', $plan) }}"
                                      onsubmit="return confirm('Delete this lesson plan?')">
                                    @csrf @method('DELETE')
                                    <x-core-package::btn type="submit" color="danger" size="sm">Del</x-core-package::btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No lesson plans yet.</p>
            @endif
        </x-core-package::card>
    </div>

    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <x-core-package::card title="Curriculum Info">
            <div style="display:flex;flex-direction:column;gap:.875rem;font-size:.875rem;">
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Subject</p>
                    <p style="font-weight:500;color:#1e293b;margin:0;">{{ $curriculum->subject->subject_name ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Academic Year</p>
                    <p style="font-weight:500;color:#1e293b;margin:0;">{{ $curriculum->academicYear->year_range ?? '—' }}</p>
                </div>
                @if($curriculum->syllabus_document_path)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Syllabus Document</p>
                    <p style="color:#4f46e5;margin:0;word-break:break-all;">{{ $curriculum->syllabus_document_path }}</p>
                </div>
                @endif
                @if($curriculum->description)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Description</p>
                    <p style="color:#475569;margin:0;line-height:1.5;">{{ $curriculum->description }}</p>
                </div>
                @endif
            </div>
        </x-core-package::card>

        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('curriculums.destroy', $curriculum) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">Delete curriculum and all its lesson plans.</p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete this curriculum? All lesson plans will be removed.')">
                    Delete Curriculum
                </x-core-package::btn>
            </form>
        </x-core-package::card>
    </div>
</div>
@endsection
