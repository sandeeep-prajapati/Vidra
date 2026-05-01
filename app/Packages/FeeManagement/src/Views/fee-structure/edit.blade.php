@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('feeStructure.index') }}" style="color:#64748b;text-decoration:none;">Fee Structures</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Structure</span>
</nav>
@endsection

@section('content')

<div style="max-width:44rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Fee Structure</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('feeStructure.update', $feeStructure) }}">
        @csrf @method('PUT')
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.select name="academic_year_id" label="Academic Year" required>
                    <option value="">Select year...</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->academic_year_id }}" @selected(old('academic_year_id', $feeStructure->academic_year_id) == $year->academic_year_id)>{{ $year->year_range }}</option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="class_id" label="Class" required>
                    <option value="">Select class...</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->class_id }}" @selected(old('class_id', $feeStructure->class_id) == $class->class_id)>{{ $class->class_name }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <x-core-package::form.select name="fee_category_id" label="Fee Category" required>
                <option value="">Select category...</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->fee_category_id }}" @selected(old('fee_category_id', $feeStructure->fee_category_id) == $cat->fee_category_id)>{{ $cat->category_name }}</option>
                @endforeach
            </x-core-package::form.select>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="amount" label="Amount (₹)" type="number" required value="{{ old('amount', $feeStructure->amount) }}" />
                <x-core-package::form.input name="due_date" label="Due Date" type="date" required value="{{ old('due_date', $feeStructure->due_date?->format('Y-m-d')) }}" />
            </div>
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Update Structure</x-core-package::btn>
                <x-core-package::btn :href="route('feeStructure.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
