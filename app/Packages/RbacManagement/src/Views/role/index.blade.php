@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Roles</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Roles</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Define system roles and their access levels</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('role-permissions.index')" color="secondary">
            Manage Permissions Matrix
        </x-core-package::btn>
        <x-core-package::btn :href="route('roles.create')" color="primary">
            <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Role
        </x-core-package::btn>
    </div>
</div>

@if(session('success'))
<x-core-package::alert type="success">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('roles.index') }}"
          style="display:flex;gap:.75rem;align-items:flex-end;">
        <div style="flex:1;">
            <x-core-package::form.input name="search" label="Search" type="text"
                :value="request('search')" placeholder="Search by role name..." />
        </div>
        <div style="padding-top:1.375rem;display:flex;gap:.5rem;">
            <x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn>
            <x-core-package::btn :href="route('roles.index')" color="secondary">Reset</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Role Name</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Description</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Permissions</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#94a3b8;font-size:.75rem;">{{ $item->id }}</td>
                <td style="padding:.75rem 1rem;">
                    <span style="font-weight:600;color:#1e293b;">{{ $item->name }}</span>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;max-width:260px;">
                    {{ $item->description ?? '—' }}
                </td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    <x-core-package::badge color="indigo">{{ $item->permissions_count }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('roles.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('roles.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('roles.destroy', $item) }}" onsubmit="return confirm('Delete this role?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No roles found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
