@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentReportCard.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Report Cards</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Generate Report</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Generate Report Card</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Create a new report card</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('studentReportCard.store') }}">
        @csrf

        <x-core-package::form.input name="student_id" label="Student ID" type="number" required />
        
        <x-core-package::form.select name="exam_id" label="Exam" required>
            <option value="">Select Exam</option>
            @foreach($exams ?? [] as $exam)
            <option value="{{ $exam->exam_id }}">{{ $exam->exam_name }}</option>
            @endforeach
        </x-core-package::form.select>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <x-core-package::form.input name="total_marks" label="Total Marks Obtained" type="number" step="0.01" required />
            <x-core-package::form.input name="maximum_marks" label="Maximum Marks" type="number" step="0.01" required />
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <x-core-package::form.input name="overall_percentage" label="Overall Percentage" type="number" step="0.01" placeholder="Auto-calculated" />
            <x-core-package::form.input name="overall_grade" label="Overall Grade" placeholder="Auto-calculated" />
        </div>

        <x-core-package::form.input name="rank_in_class" label="Class Rank" type="number" />

        <x-core-package::form.textarea name="remarks" label="Remarks" placeholder="General performance remarks..." rows="3" />

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Generate Report</x-core-package::btn>
            <x-core-package::btn :href="route('studentReportCard.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection