@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Student Transport Assignments</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Transport Assignments</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage student transport assignments with pickup/drop locations</p>
    </div>
    <x-core-package::btn :href="route('student-transport.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Assign Student
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('student-transport.index') }}"
          style="display:grid;grid-template-columns:220px 180px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="transport_id" label="Transport Service">
            <option value="" @selected(!request('transport_id'))>All Services</option>
            @foreach($transports as $t)
            <option value="{{ $t->transport_id }}" @selected((string)request('transport_id') === (string)$t->transport_id)>{{ $t->transport_name }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="status" label="Status">
            <option value="" @selected(!request('status'))>All</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="left" @selected(request('status') === 'left')>Left</option>
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('student-transport.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Student ID</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Transport</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Pickup</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Drop</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;font-weight:600;color:#1e293b;">#{{ $item->student_id }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->transportation->transport_name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->pickup_location ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->drop_location ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$item->leave_date ? 'gray' : 'green'">
                        {{ $item->leave_date ? 'Left' : 'Active' }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('student-transport.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('student-transport.destroy', $item) }}"
                              onsubmit="return confirm('Remove this assignment?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Remove</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No assignments found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
