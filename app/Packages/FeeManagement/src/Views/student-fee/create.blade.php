@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentFee.index') }}" style="color:#64748b;text-decoration:none;">Student Fees</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Assign Fee</span>
</nav>
@endsection

@section('content')

<div style="max-width:48rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Assign Fee to Student</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Create a fee obligation record for a student</p>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('studentFee.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.select name="student_id" label="Student" required>
                    <option value="">Select student...</option>
                    @foreach($students as $student)
                    <option value="{{ $student->student_id }}" @selected(old('student_id') == $student->student_id)>{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="fee_structure_id" label="Fee Structure" required>
                    <option value="">Select fee structure...</option>
                    @foreach($feeStructures as $fs)
                    <option value="{{ $fs->fee_structure_id }}" @selected(old('fee_structure_id') == $fs->fee_structure_id)>
                        {{ $fs->feeCategory?->category_name }} — {{ $fs->schoolClass?->class_name }} ({{ $fs->academicYear?->year_range }}) · ₹{{ number_format($fs->amount, 2) }}
                    </option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="amount_due" label="Amount Due (₹)" type="number" required value="{{ old('amount_due') }}" />
                <x-core-package::form.input name="discount_amount" label="Discount (₹)" type="number" hint="0 if none" value="{{ old('discount_amount', 0) }}" />
                <x-core-package::form.input name="penalty_amount" label="Penalty (₹)" type="number" hint="0 if none" value="{{ old('penalty_amount', 0) }}" />
            </div>
            <x-core-package::form.input name="due_date" label="Due Date" type="date" required value="{{ old('due_date') }}" />
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Assign Fee</x-core-package::btn>
                <x-core-package::btn :href="route('studentFee.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
