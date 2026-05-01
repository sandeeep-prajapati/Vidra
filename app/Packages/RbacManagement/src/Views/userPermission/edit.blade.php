@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('user-roles.index') }}" style="color:#64748b;text-decoration:none;">User Role Assignment</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $user->name }} — Direct Permissions</span>
</nav>
@endsection

@section('content')

<div style="max-width:780px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Direct Permissions — {{ $user->name }}</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
        {{ $user->email }} &nbsp;·&nbsp; Override or extend permissions beyond assigned roles
    </p>
</div>

@if($user->roles->isNotEmpty())
<x-core-package::alert type="info" style="margin-bottom:1rem;">
    This user already has roles:
    @foreach($user->roles as $role)
    <strong>{{ $role->name }}</strong>@if(!$loop->last), @endif
    @endforeach
    — Direct permissions below are in <em>addition</em> to role permissions.
</x-core-package::alert>
@endif

@if($errors->any())
<x-core-package::alert type="error" style="margin-bottom:1rem;">
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</x-core-package::alert>
@endif

<form method="POST" action="{{ route('user-permissions.update', $user) }}">
    @csrf @method('PUT')

    <x-core-package::card>
        <p style="font-size:.8125rem;color:#64748b;margin:0 0 1.25rem;">
            Select direct permissions for <strong>{{ $user->name }}</strong>.
        </p>

        @forelse($allPermissions as $module => $perms)
        <div style="margin-bottom:1.5rem;">
            <div style="font-size:.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.625rem;padding-bottom:.375rem;border-bottom:1px solid #e2e8f0;">
                {{ $module ?: 'General' }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:.5rem;">
                @foreach($perms as $permission)
                @php $checked = $user->permissions->contains('id', $permission->id); @endphp
                <label style="display:flex;align-items:flex-start;gap:.5rem;font-size:.8125rem;color:#374151;cursor:pointer;padding:.5rem;border-radius:.375rem;border:1px solid {{ $checked ? '#a5b4fc' : '#e2e8f0' }};background:{{ $checked ? '#eef2ff' : '#fff' }};">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ $checked ? 'checked' : '' }}
                        style="width:1rem;height:1rem;margin-top:.1rem;accent-color:#4f46e5;flex-shrink:0;">
                    <div>
                        <div style="font-weight:500;">{{ $permission->name }}</div>
                        @if($permission->description)
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem;">{{ $permission->description }}</div>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @empty
        <p style="color:#94a3b8;font-size:.875rem;text-align:center;padding:1.5rem 0;">No permissions defined.</p>
        @endforelse

        <div style="padding-top:1rem;border-top:1px solid #e2e8f0;display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">Save Direct Permissions</x-core-package::btn>
            <x-core-package::btn :href="route('user-roles.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </x-core-package::card>
</form>

</div>

<script>
document.querySelectorAll('[name="permissions[]"]').forEach(function (cb) {
    cb.addEventListener('change', function () {
        var label = this.closest('label');
        label.style.borderColor = this.checked ? '#a5b4fc' : '#e2e8f0';
        label.style.background  = this.checked ? '#eef2ff' : '#fff';
    });
});
</script>

@endsection
