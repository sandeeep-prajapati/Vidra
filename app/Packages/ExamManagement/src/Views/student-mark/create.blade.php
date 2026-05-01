@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentMark.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Student Marks</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Add Marks</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Student Marks</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Enter marks for a student</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('studentMark.store') }}">
        @csrf

        <x-core-package::form.input name="student_id" label="Student ID" type="number" required />
        
        <x-core-package::form.select name="schedule_id" label="Exam Schedule" required>
            <option value="">Select Exam Schedule</option>
            @foreach($examSchedules ?? [] as $schedule)
            <option value="{{ $schedule->schedule_id }}">
                {{ $schedule->exam?->exam_name ?? 'Exam #'.$schedule->exam_id }} - {{ $schedule->subject?->subject_name ?? 'Subject #'.$schedule->subject_id }} (Max: {{ $schedule->total_marks }})
            </option>
            @endforeach
        </x-core-package::form.select>

        <x-core-package::form.input name="marks_obtained" label="Marks Obtained" type="number" step="0.01" required />
        
        <x-core-package::form.input name="grade" label="Grade" placeholder="Auto-calculated if not provided" />
        
        <x-core-package::form.textarea name="remarks" label="Remarks" placeholder="Optional comments..." rows="3" />

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Save Marks</x-core-package::btn>
            <x-core-package::btn :href="route('studentMark.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection