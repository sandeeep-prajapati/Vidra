@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('feePayment.index') }}" style="color:#64748b;text-decoration:none;">Fee Payments</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Payment Receipt</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Payment Receipt</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">#{{ $feePayment->payment_id }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('feePayment.edit', $feePayment)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('feePayment.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<x-core-package::card title="Payment Details" style="max-width:36rem;">
    <dl style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Student</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">
                {{ $feePayment->studentFee?->student?->first_name }} {{ $feePayment->studentFee?->student?->last_name }}
            </dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Fee Category</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feePayment->studentFee?->feeStructure?->feeCategory?->category_name ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Class</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feePayment->studentFee?->feeStructure?->schoolClass?->class_name ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Payment Date</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feePayment->payment_date?->format('d M Y') }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Amount Paid</dt>
            <dd style="font-size:1.25rem;font-weight:700;color:#0f766e;margin:0;">₹{{ number_format($feePayment->amount_paid, 2) }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Payment Mode</dt>
            <dd style="margin:0;"><x-core-package::badge color="{{ match($feePayment->payment_mode) { 'Cash' => 'green', 'UPI' => 'blue', 'Card' => 'indigo', default => 'gray' } }}">{{ $feePayment->payment_mode }}</x-core-package::badge></dd>
        </div>
        <div style="grid-column:span 2;">
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Transaction Reference</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feePayment->transaction_reference ?? '—' }}</dd>
        </div>
    </dl>
</x-core-package::card>

@endsection
