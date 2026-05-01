@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('holiday.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Holidays</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Add Holiday</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Holiday</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Create a new holiday entry</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('holiday.store') }}">
        @csrf

        <x-core-package::form.input name="title" label="Holiday Title" placeholder="e.g., Independence Day" required />
        
        <x-core-package::form.input name="date" label="Date" type="date" required />
        
        <x-core-package::form.textarea name="description" label="Description" placeholder="Optional description..." rows="3" />
        
        <x-core-package::form.select name="is_recurring" label="Recurring">
            <option value="0">One-time</option>
            <option value="1">Recurring every year</option>
        </x-core-package::form.select>

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Create Holiday</x-core-package::btn>
            <x-core-package::btn :href="route('holiday.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection