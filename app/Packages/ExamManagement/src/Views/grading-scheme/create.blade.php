@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('gradingScheme.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Grading Schemes</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Add Grade</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Grade</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Define a new grade range</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('gradingScheme.store') }}">
        @csrf

        <x-core-package::form.input name="grade" label="Grade" placeholder="e.g., A+, A, B, C" required />
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <x-core-package::form.input name="min_percentage" label="Min Percentage" type="number" step="0.01" placeholder="0" required />
            <x-core-package::form.input name="max_percentage" label="Max Percentage" type="number" step="0.01" placeholder="100" required />
        </div>

        <x-core-package::form.input name="remarks" label="Remarks" placeholder="e.g., Excellent, Good, Needs Improvement" />

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Create Grade</x-core-package::btn>
            <x-core-package::btn :href="route('gradingScheme.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection