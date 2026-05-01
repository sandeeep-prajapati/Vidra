@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Staff Management</span>
</nav>
@endsection

@section('content')

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Staff Management</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage all staff members, departments and designations</p>
    </div>
    <x-core-package::btn :href="route('staff.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Staff
    </x-core-package::btn>
</div>

{{-- Filters --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('staff.index') }}"
          style="display:grid;grid-template-columns:1fr 160px 220px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search"
            placeholder="Name, phone, email, designation"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="status" label="Status">
            <option value="" @selected(!request('status'))>All</option>
            @foreach(['Active','Inactive','Resigned'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="department_id" label="Department">
            <option value="" @selected(!request('department_id'))>All Departments</option>
            @foreach($departments as $department)
            <option value="{{ $department->department_id }}"
                @selected((string) request('department_id') === (string) $department->department_id)>
                {{ $department->department_name }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;">
            <x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn>
        </div>
        <div style="padding-top:1.375rem;">
            <x-core-package::btn :href="route('staff.index')" color="secondary">Reset</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

{{-- Table --}}
<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Staff Member</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Department</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Designation</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Contact</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($staff as $member)
            @php
            $sColor = match($member->status) {
                'Active'   => 'green',
                'Inactive' => 'yellow',
                default    => 'gray',
            };
            $initials = strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1));
            @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        @if($member->photo)
                        <img src="{{ $member->photo }}" alt="{{ $member->first_name }}"
                             style="width:2.25rem;height:2.25rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                        @else
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:.6875rem;font-weight:700;color:#fff;">{{ $initials }}</span>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $member->first_name }} {{ $member->last_name }}</p>
                            <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">Joined {{ optional($member->joining_date)->format('M d, Y') ?: 'N/A' }}</p>
                        </div>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $member->department->department_name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <p style="font-size:.8125rem;font-weight:500;color:#1e293b;margin:0;">{{ $member->designation ?: '—' }}</p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">{{ $member->employment_type ?: '' }}</p>
                </td>
                <td style="padding:.75rem 1rem;">
                    <p style="font-size:.8125rem;color:#374151;margin:0;">{{ $member->phone_number ?: '—' }}</p>
                    <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">{{ $member->email ?: '' }}</p>
                </td>
                <td style="padding:.75rem 1rem;"><x-core-package::badge :color="$sColor">{{ $member->status }}</x-core-package::badge></td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('staff.show', $member)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('staff.edit', $member)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('staff.destroy', $member) }}"
                              onsubmit="return confirm('Delete {{ $member->first_name }} {{ $member->last_name }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">
                    No staff members found.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $staff->links() }}</div>

@endsection
