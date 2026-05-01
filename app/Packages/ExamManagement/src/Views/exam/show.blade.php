@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('exam.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Exams</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">View Exam</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $exam->exam_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Exam details</p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('exam.edit', $exam)" color="secondary">
            <svg style="width:.875rem;height:.875rem;margin-right:.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </x-core-package::btn>
        <form method="POST" action="{{ route('exam.destroy', $exam) }}" onsubmit="return confirm('Delete this exam?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">
                <svg style="width:.875rem;height:.875rem;margin-right:.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 21m5-12h6m-6 4h6m4-4h6m4-4h6"/></svg>
                Delete
            </x-core-package::btn>
        </form>
    </div>
</div>

<x-core-package::card>
    <div style="display:grid;gap:1.25rem;">
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Exam Name</p>
            <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $exam->exam_name }}</p>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Academic Year</p>
                <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $exam->academicYear?->title ?? '—' }}</p>
            </div>
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Type</p>
                <x-core-package::badge color="{{ $exam->is_final ? 'blue' : 'gray' }}">
                    {{ $exam->is_final ? 'Final Exam' : 'Regular Exam' }}
                </x-core-package::badge>
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Start Date</p>
                <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $exam->start_date?->format('d M Y') ?? '—' }}</p>
            </div>
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">End Date</p>
                <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $exam->end_date?->format('d M Y') ?? '—' }}</p>
            </div>
        </div>
        
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Description</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $exam->description ?? '—' }}</p>
        </div>
    </div>
</x-core-package::card>

@endsection