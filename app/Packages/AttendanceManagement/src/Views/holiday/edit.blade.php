@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('holiday.index') }}" style="color:#374151;font-weight:500;text-decoration:none;">Holidays</a>
    <span style="margin:0 .375rem;color:#94a3b8;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Holiday</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Holiday</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Update holiday details</p>
    </div>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('holiday.update', $holiday) }}">
        @csrf
        @method('PUT')

        <x-core-package::form.input name="title" label="Holiday Title" value="{{ old('title', $holiday->title) }}" required />
        
        <x-core-package::form.input name="date" label="Date" type="date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}" required />
        
        <x-core-package::form.textarea name="description" label="Description" rows="3">{{ old('description', $holiday->description) }}</x-core-package::form.textarea>
        
        <x-core-package::form.select name="is_recurring" label="Recurring">
            <option value="0" @selected(!$holiday->is_recurring)>One-time</option>
            <option value="1" @selected($holiday->is_recurring)>Recurring every year</option>
        </x-core-package::form.select>

        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <x-core-package::btn type="submit" color="primary">Update Holiday</x-core-package::btn>
            <x-core-package::btn :href="route('holiday.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection