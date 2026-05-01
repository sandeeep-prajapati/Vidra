@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('exam.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Exams</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Exam</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Exam</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Update exam details</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('exam.update', $exam) }}">
        @csrf
        @method('PUT')

        <x-core-package::form.input name="exam_name" label="Exam Name" value="{{ old('exam_name', $exam->exam_name) }}" required />
        
        <x-core-package::form.select name="academic_year_id" label="Academic Year" required>
            <option value="">Select Academic Year</option>
            @foreach($academicYears ?? [] as $year)
            <option value="{{ $year->academic_year_id }}" @selected(old('academic_year_id', $exam->academic_year_id) == $year->academic_year_id)>{{ $year->title }}</option>
            @endforeach
        </x-core-package::form.select>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <x-core-package::form.input name="start_date" label="Start Date" type="date" value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}" required />
            <x-core-package::form.input name="end_date" label="End Date" type="date" value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}" required />
        </div>

        <x-core-package::form.textarea name="description" label="Description" rows="3">{{ old('description', $exam->description) }}</x-core-package::form.textarea>

        <x-core-package::form.select name="is_final" label="Exam Type">
            <option value="0" @selected(!$exam->is_final)>Regular Exam</option>
            <option value="1" @selected($exam->is_final)>Final Exam</option>
        </x-core-package::form.select>

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Update Exam</x-core-package::btn>
            <x-core-package::btn :href="route('exam.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection