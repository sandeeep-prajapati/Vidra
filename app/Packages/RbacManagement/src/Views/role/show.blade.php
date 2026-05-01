@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('roles.index') }}" style="color:#64748b;text-decoration:none;">Roles</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $role->name }}</span>
</nav>
@endsection

@section('content')

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $role->name }}</h1>
        @if($role->description)
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $role->description }}</p>
        @endif
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('roles.edit', $role)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<x-core-package::card title="Assign Permissions">
    <form method="POST" action="{{ route('roles.sync-permissions', $role) }}">
        @csrf
        <p style="font-size:.8125rem;color:#64748b;margin:0 0 1.25rem;">
            Select permissions to assign to the <strong>{{ $role->name }}</strong> role.
        </p>

        @forelse($allPermissions as $module => $perms)
        <div style="margin-bottom:1.25rem;">
            <div style="font-size:.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.625rem;padding-bottom:.375rem;border-bottom:1px solid #e2e8f0;">
                {{ $module ?: 'General' }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:.5rem;">
                @foreach($perms as $permission)
                @php $checked = $role->permissions->contains('id', $permission->id); @endphp
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.8125rem;color:#374151;cursor:pointer;padding:.375rem .5rem;border-radius:.375rem;{{ $checked ? 'background:#eef2ff;' : '' }}">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ $checked ? 'checked' : '' }}
                        style="width:1rem;height:1rem;accent-color:#4f46e5;">
                    <span>{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @empty
        <p style="color:#94a3b8;font-size:.875rem;">No permissions defined yet. <a href="{{ route('permissions.create') }}" style="color:#4f46e5;">Create permissions</a></p>
        @endforelse

        <div style="padding-top:1rem;border-top:1px solid #e2e8f0;margin-top:.5rem;">
            <x-core-package::btn type="submit" color="primary">Save Permissions</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

@endsection
