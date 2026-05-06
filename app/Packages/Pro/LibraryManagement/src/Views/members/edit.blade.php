@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.members.index') }}" style="color:#64748b;text-decoration:none;">Members</a>
    <span style="margin:0 .375rem;">›</span>
    <a href="{{ route('library.members.show', $member) }}" style="color:#64748b;text-decoration:none;">{{ $member->name }}</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')
<div style="max-width:680px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Member</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $member->membership_number }}</p>
</div>

@if($errors->any())
<x-core-package::alert type="error" style="margin-bottom:1rem;">
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</x-core-package::alert>
@endif

<x-core-package::card>
    <form method="POST" action="{{ route('library.members.update', $member) }}">
        @csrf @method('PUT')
        <div style="display:flex;flex-direction:column;gap:1rem;">

            <x-core-package::form.input name="name" label="Full Name" required :value="old('name', $member->name)" />

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="email" label="Email" type="email" :value="old('email', $member->email)" />
                <x-core-package::form.input name="phone" label="Phone" :value="old('phone', $member->phone)" />
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="max_books_allowed" label="Max Books Allowed" type="number" min="1" required :value="old('max_books_allowed', $member->max_books_allowed)" />
                <x-core-package::form.select name="status" label="Status" required>
                    <option value="active" @selected(old('status', $member->status)==='active')>Active</option>
                    <option value="inactive" @selected(old('status', $member->status)==='inactive')>Inactive</option>
                    <option value="suspended" @selected(old('status', $member->status)==='suspended')>Suspended</option>
                </x-core-package::form.select>
            </div>

            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Update Member</x-core-package::btn>
                <x-core-package::btn :href="route('library.members.show', $member)" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
