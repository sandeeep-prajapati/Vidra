@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Role-Permission Matrix</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Role-Permission Matrix</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Overview of all roles and their assigned permissions</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('roles.index')" color="secondary">Manage Roles</x-core-package::btn>
        <x-core-package::btn :href="route('permissions.index')" color="secondary">Manage Permissions</x-core-package::btn>
    </div>
</div>

@if(session('success'))
<x-core-package::alert type="success">{{ session('success') }}</x-core-package::alert>
@endif

@forelse($roles as $role)
<x-core-package::card style="margin-bottom:1.25rem;">
    <div slot="action">
        <x-core-package::btn :href="route('role-permissions.edit', $role)" color="secondary" size="sm">Edit Permissions</x-core-package::btn>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <div>
            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin:0;">{{ $role->name }}</h3>
            @if($role->description)
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $role->description }}</p>
            @endif
        </div>
        <x-core-package::btn :href="route('role-permissions.edit', $role)" color="secondary" size="sm">Edit Permissions</x-core-package::btn>
    </div>

    @if($role->permissions->isEmpty())
    <p style="color:#94a3b8;font-size:.8125rem;margin:0;">No permissions assigned.</p>
    @else
    <div style="display:flex;flex-wrap:wrap;gap:.375rem;">
        @foreach($role->permissions->sortBy('module_name') as $perm)
        <span style="display:inline-flex;align-items:center;gap:.25rem;padding:.2rem .6rem;background:#eef2ff;color:#4338ca;border-radius:999px;font-size:.72rem;font-weight:500;">
            @if($perm->module_name)
            <span style="color:#818cf8;font-size:.68rem;">{{ $perm->module_name }}</span>
            <span style="color:#c7d2fe;">·</span>
            @endif
            {{ $perm->name }}
        </span>
        @endforeach
    </div>
    @endif
</x-core-package::card>
@empty
<x-core-package::card>
    <p style="text-align:center;color:#94a3b8;padding:2rem 0;margin:0;">No roles defined. <a href="{{ route('roles.create') }}" style="color:#4f46e5;">Create a role</a></p>
</x-core-package::card>
@endforelse

@endsection
