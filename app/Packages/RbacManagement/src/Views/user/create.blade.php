@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('user-roles.index') }}" style="color:#0ea5e9;">User Roles</a>
    <span style="color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Create User</span>
</nav>
@endsection

@section('content')

<div style="max-width:32rem;margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Create User</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Add a new user to the system</p>
    </div>

    @if($errors->any())
    <x-core-package::alert type="error">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <x-core-package::card>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <x-core-package::form.input
                    name="name"
                    label="Full Name"
                    type="text"
                    placeholder="John Doe"
                    :value="old('name')"
                    required
                />
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-core-package::form.input
                    name="email"
                    label="Email Address"
                    type="email"
                    placeholder="john@example.com"
                    :value="old('email')"
                    required
                />
            </div>

            <div style="margin-bottom:1.25rem;">
                <x-core-package::form.input
                    name="password"
                    label="Password"
                    type="password"
                    placeholder="Minimum 8 characters"
                    required
                />
            </div>

            <div style="margin-bottom:1.5rem;">
                <x-core-package::form.input
                    name="password_confirmation"
                    label="Confirm Password"
                    type="password"
                    placeholder="Re-enter password"
                    required
                />
            </div>

            <div style="display:flex;gap:.75rem;">
                <x-core-package::btn type="submit" color="primary">Create User</x-core-package::btn>
                <x-core-package::btn :href="route('user-roles.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
