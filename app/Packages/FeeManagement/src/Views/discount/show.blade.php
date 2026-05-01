@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('discount.index') }}" style="color:#64748b;text-decoration:none;">Discounts</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $discount->discount_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $discount->discount_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Discount Details</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('discount.edit', $discount)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('discount.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:1.25rem;">

<x-core-package::card title="Discount Info">
    <dl style="display:flex;flex-direction:column;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Name</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $discount->discount_name }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Type</dt>
            <dd style="margin:0;"><x-core-package::badge color="{{ $discount->discount_type === 'Percentage' ? 'blue' : 'indigo' }}">{{ $discount->discount_type }}</x-core-package::badge></dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Value</dt>
            <dd style="font-size:1.25rem;font-weight:700;color:#0f766e;margin:0;">
                {{ $discount->discount_type === 'Percentage' ? $discount->discount_amount.'%' : '₹'.number_format($discount->discount_amount, 2) }}
            </dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Description</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $discount->description ?? '—' }}</dd>
        </div>
    </dl>
</x-core-package::card>

<x-core-package::card title="Students with this Discount" :noPadding="true">
    <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Student</th>
            </tr>
        </thead>
        <tbody>
        @forelse($discount->studentDiscounts as $sd)
        <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:.625rem 1rem;color:#374151;">{{ $sd->student?->first_name }} {{ $sd->student?->last_name }}</td>
        </tr>
        @empty
        <tr><td style="padding:1.5rem;text-align:center;color:#94a3b8;">No students assigned.</td></tr>
        @endforelse
        </tbody>
    </table>
</x-core-package::card>

</div>

@endsection
