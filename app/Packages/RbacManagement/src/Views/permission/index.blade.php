@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Permissions</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Permissions</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage granular permissions per module</p>
    </div>
    <x-core-package::btn :href="route('permissions.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Permission
    </x-core-package::btn>
</div>

@if(session('success'))
<x-core-package::alert type="success">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('permissions.index') }}"
          style="display:grid;grid-template-columns:200px 1fr auto auto;gap:.75rem;align-items:flex-end;">
        <x-core-package::form.select name="module_name" label="Module">
            <option value="">All Modules</option>
            @foreach($modules as $mod)
            <option value="{{ $mod }}" @selected(request('module_name') === $mod)>{{ $mod }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.input name="search" label="Search" type="text"
            :value="request('search')" placeholder="Search permission name..." />
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('permissions.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Permission Name</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Module</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Description</th>
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
                <td style="padding:.75rem 1rem;">
                    @if($item->module_name)
                    <x-core-package::badge color="indigo">{{ $item->module_name }}</x-core-package::badge>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;max-width:280px;">{{ $item->description ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('permissions.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('permissions.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('permissions.destroy', $item) }}" onsubmit="return confirm('Delete this permission?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No permissions found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
