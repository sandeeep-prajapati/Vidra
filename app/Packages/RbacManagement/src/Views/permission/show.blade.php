@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('permissions.index') }}" style="color:#64748b;text-decoration:none;">Permissions</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $permission->name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $permission->name }}</h1>
        @if($permission->module_name)
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Module: <strong>{{ $permission->module_name }}</strong></p>
        @endif
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('permissions.edit', $permission)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('permissions.destroy', $permission) }}" onsubmit="return confirm('Delete this permission?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <x-core-package::card title="Details">
        <div style="display:flex;flex-direction:column;gap:.75rem;font-size:.875rem;">
            <div>
                <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Name</span>
                <p style="margin:.25rem 0 0;color:#1e293b;font-weight:500;">{{ $permission->name }}</p>
            </div>
            <div>
                <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Module</span>
                <p style="margin:.25rem 0 0;">
                    @if($permission->module_name)
                    <x-core-package::badge color="indigo">{{ $permission->module_name }}</x-core-package::badge>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </p>
            </div>
            <div>
                <span style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;">Description</span>
                <p style="margin:.25rem 0 0;color:#64748b;">{{ $permission->description ?? '—' }}</p>
            </div>
        </div>
    </x-core-package::card>

    <x-core-package::card title="Assigned to Roles ({{ $permission->roles->count() }})">
        @forelse($permission->roles as $role)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;">
            <span style="font-size:.875rem;color:#374151;font-weight:500;">{{ $role->name }}</span>
            <x-core-package::btn :href="route('roles.show', $role)" color="primary" size="xs">Manage</x-core-package::btn>
        </div>
        @empty
        <p style="color:#94a3b8;font-size:.875rem;margin:0;">Not assigned to any role.</p>
        @endforelse
    </x-core-package::card>
</div>

@endsection
