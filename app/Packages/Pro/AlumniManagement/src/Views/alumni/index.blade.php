@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.directory') }}" style="color:#64748b;text-decoration:none;">Alumni</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">All Profiles</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Alumni Profiles</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage all alumni records</p>
    </div>
    @can('create_alumni-management_item')
    <x-core-package::btn :href="route('alumni.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Alumni
    </x-core-package::btn>
    @endcan
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Name</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batch</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Current Role</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($alumni as $person)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#94a3b8;font-size:.75rem;">{{ $person->id }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $person->full_name }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $person->email }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $person->graduation_year }}</td>
                <td style="padding:.75rem 1rem;">
                    @if($person->employment->first())
                    <div style="font-size:.8125rem;color:#1e293b;">{{ $person->employment->first()->designation }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $person->employment->first()->company_name }}</div>
                    @else
                    <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $person->status === 'active' ? 'green' : ($person->status === 'deceased' ? 'red' : 'gray') }}">
                        {{ $person->status }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('alumni.show', $person)" color="secondary" size="sm">View</x-core-package::btn>
                        @can('edit_alumni-management_item')
                        <x-core-package::btn :href="route('alumni.edit', $person)" color="secondary" size="sm">Edit</x-core-package::btn>
                        @endcan
                        @can('delete_alumni-management_item')
                        <form method="POST" action="{{ route('alumni.destroy', $person) }}" onsubmit="return confirm('Delete this alumni profile?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No alumni profiles found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $alumni->links() }}</div>

@endsection
