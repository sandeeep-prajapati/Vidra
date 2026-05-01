@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('discount.index') }}" style="color:#64748b;text-decoration:none;">Discounts</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Add Discount</span>
</nav>
@endsection

@section('content')

<div style="max-width:40rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Discount</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('discount.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.input name="discount_name" label="Discount Name" required placeholder="e.g. Sibling Discount" value="{{ old('discount_name') }}" />
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.select name="discount_type" label="Discount Type" required>
                    <option value="">Select type...</option>
                    <option value="Fixed" @selected(old('discount_type') === 'Fixed')>Fixed Amount</option>
                    <option value="Percentage" @selected(old('discount_type') === 'Percentage')>Percentage</option>
                </x-core-package::form.select>
                <x-core-package::form.input name="discount_amount" label="Value" type="number" required hint="Amount (₹) or percentage (%)" value="{{ old('discount_amount') }}" />
            </div>
            <x-core-package::form.textarea name="description" label="Description" rows="3" value="{{ old('description') }}" />
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Save Discount</x-core-package::btn>
                <x-core-package::btn :href="route('discount.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
