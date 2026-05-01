@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentDiscount.index') }}" style="color:#64748b;text-decoration:none;">Student Discounts</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Assignment</span>
</nav>
@endsection

@section('content')

<div style="max-width:44rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Student Discount</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('studentDiscount.update', $studentDiscount) }}">
        @csrf @method('PUT')
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.select name="student_id" label="Student" required>
                <option value="">Select student...</option>
                @foreach($students as $student)
                <option value="{{ $student->student_id }}" @selected(old('student_id', $studentDiscount->student_id) == $student->student_id)>{{ $student->first_name }} {{ $student->last_name }}</option>
                @endforeach
            </x-core-package::form.select>
            <x-core-package::form.select name="discount_id" label="Discount" required>
                <option value="">Select discount...</option>
                @foreach($discounts as $discount)
                <option value="{{ $discount->discount_id }}" @selected(old('discount_id', $studentDiscount->discount_id) == $discount->discount_id)>
                    {{ $discount->discount_name }}
                    ({{ $discount->discount_type === 'Percentage' ? $discount->discount_amount.'%' : '₹'.number_format($discount->discount_amount, 2) }})
                </option>
                @endforeach
            </x-core-package::form.select>
            <x-core-package::form.select name="fee_structure_id" label="Applicable Fee Structure" required>
                <option value="">Select fee structure...</option>
                @foreach($feeStructures as $fs)
                <option value="{{ $fs->fee_structure_id }}" @selected(old('fee_structure_id', $studentDiscount->fee_structure_id) == $fs->fee_structure_id)>
                    {{ $fs->feeCategory?->category_name }} — {{ $fs->schoolClass?->class_name }}
                </option>
                @endforeach
            </x-core-package::form.select>
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Update Assignment</x-core-package::btn>
                <x-core-package::btn :href="route('studentDiscount.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
