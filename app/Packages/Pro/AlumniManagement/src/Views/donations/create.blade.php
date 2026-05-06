@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.donations.index') }}" style="color:#64748b;text-decoration:none;">Donations</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Record Donation</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;margin:0 auto;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Record Donation</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('alumni.donations.store') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div style="grid-column:1/-1;">
                <x-core-package::form.select name="alumni_id" label="Alumni" required>
                    <option value="">Select alumni...</option>
                    @foreach($alumni as $a)
                    <option value="{{ $a->id }}" {{ old('alumni_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->full_name }} ({{ $a->graduation_year }})
                    </option>
                    @endforeach
                </x-core-package::form.select>
                @error('alumni_id')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="amount" label="Amount" type="number" step="0.01" :value="old('amount')" required />
                @error('amount')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.select name="currency" label="Currency" required>
                    @foreach(['INR','USD','GBP','EUR'] as $c)
                    <option value="{{ $c }}" {{ old('currency','INR') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.select name="purpose" label="Purpose" required>
                    @foreach(['scholarship','infrastructure','general'] as $p)
                    <option value="{{ $p }}" {{ old('purpose') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.input name="donated_at" label="Donation Date" type="date" :value="old('donated_at', date('Y-m-d'))" required />
            </div>
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="receipt_number" label="Receipt Number (optional)" :value="old('receipt_number')" placeholder="Leave blank to auto-generate" />
            </div>
        </div>

        <div style="margin-bottom:1.25rem;">
            <x-core-package::form.textarea name="notes" label="Notes" rows="3" :value="old('notes')" />
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <x-core-package::btn :href="route('alumni.donations.index')" color="secondary">Cancel</x-core-package::btn>
            <x-core-package::btn type="submit" color="primary">Record Donation</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
