@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentFee.index') }}" style="color:#64748b;text-decoration:none;">Student Fees</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Fee Details</span>
</nav>
@endsection

@section('content')

@php
    $statusColor = match($studentFee->payment_status) {
        'Paid' => 'green',
        'Partially Paid' => 'orange',
        default => 'yellow',
    };
    $totalPaid = $studentFee->payments->sum('amount_paid');
    $balance   = $studentFee->total_payable - $totalPaid;
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">
            {{ $studentFee->student?->first_name }} {{ $studentFee->student?->last_name }}
        </h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Fee Record · {{ $studentFee->feeStructure?->feeCategory?->category_name }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        @if($studentFee->payment_status !== 'Paid')
        <x-core-package::btn :href="route('feePayment.create', ['student_fee_id' => $studentFee->student_fee_id])" color="green">Record Payment</x-core-package::btn>
        @endif
        <x-core-package::btn :href="route('studentFee.edit', $studentFee)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('studentFee.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:1.25rem;margin-bottom:1.25rem;">

<x-core-package::card title="Fee Summary">
    <dl style="display:flex;flex-direction:column;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Status</dt>
            <dd style="margin:0;"><x-core-package::badge color="{{ $statusColor }}">{{ $studentFee->payment_status }}</x-core-package::badge></dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Amount Due</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">₹{{ number_format($studentFee->amount_due, 2) }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Discount</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">- ₹{{ number_format($studentFee->discount_amount, 2) }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Penalty</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">+ ₹{{ number_format($studentFee->penalty_amount, 2) }}</dd>
        </div>
        <div style="border-top:1px solid #e2e8f0;padding-top:.875rem;">
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Total Payable</dt>
            <dd style="font-size:1.25rem;font-weight:700;color:#0f766e;margin:0;">₹{{ number_format($studentFee->total_payable, 2) }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Total Paid</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">₹{{ number_format($totalPaid, 2) }}</dd>
        </div>
        @if($balance > 0)
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Balance</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#dc2626;margin:0;">₹{{ number_format($balance, 2) }}</dd>
        </div>
        @endif
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Due Date</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $studentFee->due_date?->format('d M Y') ?? '—' }}</dd>
        </div>
    </dl>
</x-core-package::card>

<x-core-package::card title="Payment History" :noPadding="true">
    <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Date</th>
                <th style="padding:.5rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Amount</th>
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Mode</th>
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Reference</th>
            </tr>
        </thead>
        <tbody>
        @forelse($studentFee->payments as $payment)
        <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:.625rem 1rem;color:#374151;">{{ $payment->payment_date?->format('d M Y') }}</td>
            <td style="padding:.625rem 1rem;text-align:right;font-weight:600;color:#0f766e;">₹{{ number_format($payment->amount_paid, 2) }}</td>
            <td style="padding:.625rem 1rem;color:#64748b;">{{ $payment->payment_mode }}</td>
            <td style="padding:.625rem 1rem;color:#94a3b8;font-size:.75rem;">{{ $payment->transaction_reference ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="padding:1.5rem;text-align:center;color:#94a3b8;">No payments recorded.</td></tr>
        @endforelse
        </tbody>
    </table>
</x-core-package::card>

</div>

@endsection
