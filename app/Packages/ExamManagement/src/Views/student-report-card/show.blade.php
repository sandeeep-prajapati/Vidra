@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentReportCard.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Report Cards</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">View Report</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Report Card</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $studentReportCard->student?->first_name ?? 'Student #'.$studentReportCard->student_id }} - {{ $studentReportCard->exam?->exam_name ?? 'Exam' }}</p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('studentReportCard.edit', $studentReportCard)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('studentReportCard.destroy', $studentReportCard) }}" onsubmit="return confirm('Delete?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<x-core-package::card>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Student</p>
            <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $studentReportCard->student?->first_name ?? 'Student #'.$studentReportCard->student_id }} {{ $studentReportCard->student?->last_name ?? '' }}</p>
        </div>
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Exam</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $studentReportCard->exam?->exam_name ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Total Marks</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $studentReportCard->total_marks ?? '—' }} / {{ $studentReportCard->maximum_marks ?? '—' }}</p>
        </div>
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Percentage</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $studentReportCard->overall_percentage ? $studentReportCard->overall_percentage.'%' : '—' }}</p>
        </div>
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Grade</p>
            <x-core-package::badge color="blue">{{ $studentReportCard->overall_grade ?? '—' }}</x-core-package::badge>
        </div>
        <div>
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Class Rank</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $studentReportCard->rank_in_class ?? '—' }}</p>
        </div>
        <div style="grid-column:span 2;">
            <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Remarks</p>
            <p style="font-size:.9375rem;color:#1e293b;margin:0;">{{ $studentReportCard->remarks ?? '—' }}</p>
        </div>
    </div>
</x-core-package::card>

@endsection