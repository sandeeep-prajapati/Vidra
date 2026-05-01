@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('expense.index') }}" style="color:#64748b;text-decoration:none;">Expenses</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Expense Details</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $expense->expense_category }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $expense->expense_date?->format('d M Y') }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('expense.edit', $expense)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('expense.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<x-core-package::card title="Expense Details" style="max-width:32rem;">
    <dl style="display:flex;flex-direction:column;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Category</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $expense->expense_category }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Date</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $expense->expense_date?->format('d M Y') }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Amount</dt>
            <dd style="font-size:1.25rem;font-weight:700;color:#dc2626;margin:0;">₹{{ number_format($expense->amount, 2) }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Description</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $expense->description ?? '—' }}</dd>
        </div>
    </dl>
</x-core-package::card>

@endsection
