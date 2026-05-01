@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('financialReport.index') }}" style="color:#64748b;text-decoration:none;">Financial Reports</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Generate Report</span>
</nav>
@endsection

@section('content')

<div style="max-width:40rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Generate Financial Report</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Calculates total income or expense for a selected period</p>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('financialReport.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.select name="report_type" label="Report Type" required>
                <option value="">Select type...</option>
                <option value="Income" @selected(old('report_type') === 'Income')>Income (Fee Payments)</option>
                <option value="Expense" @selected(old('report_type') === 'Expense')>Expense (School Expenses)</option>
            </x-core-package::form.select>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="report_period_start" label="Period Start" type="date" required value="{{ old('report_period_start') }}" />
                <x-core-package::form.input name="report_period_end" label="Period End" type="date" required value="{{ old('report_period_end') }}" />
            </div>
            <x-core-package::alert type="info">
                The system will automatically sum all transactions of the selected type within this date range.
            </x-core-package::alert>
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Generate Report</x-core-package::btn>
                <x-core-package::btn :href="route('financialReport.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
