@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Fee Payments</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Fee Payments</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">All payment transactions</p>
    </div>
    <x-core-package::btn :href="route('feePayment.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Record Payment
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('feePayment.index') }}"
          style="display:grid;grid-template-columns:160px 1fr 1fr auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="payment_mode" label="Mode">
            <option value="">All Modes</option>
            @foreach(['Cash','Card','UPI','Bank Transfer'] as $mode)
            <option value="{{ $mode }}" @selected(request('payment_mode') === $mode)>{{ $mode }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.input name="from_date" label="From Date" type="date" value="{{ request('from_date') }}" />
        <x-core-package::form.input name="to_date" label="To Date" type="date" value="{{ request('to_date') }}" />
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('feePayment.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Student</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Fee Category</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Amount</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Mode</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Reference</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">
                    {{ $item->studentFee?->student?->first_name }} {{ $item->studentFee?->student?->last_name }}
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->studentFee?->feeStructure?->feeCategory?->category_name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->payment_date?->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;text-align:right;font-weight:600;color:#0f766e;">₹{{ number_format($item->amount_paid, 2) }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ match($item->payment_mode) { 'Cash' => 'green', 'UPI' => 'blue', 'Card' => 'indigo', default => 'gray' } }}">{{ $item->payment_mode }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#94a3b8;font-size:.75rem;">{{ $item->transaction_reference ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('feePayment.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('feePayment.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('feePayment.destroy', $item) }}" onsubmit="return confirm('Delete this payment?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No payments found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
