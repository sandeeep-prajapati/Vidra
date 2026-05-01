@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Sections</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Sections</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage sections within each class</p>
    </div>
    <x-core-package::btn :href="route('sections.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Section
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('sections.index') }}"
          style="display:grid;grid-template-columns:1fr 220px 160px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.input name="search" label="Search" placeholder="Section name"
            value="{{ request('search') }}" />
        <x-core-package::form.select name="class_id" label="Class">
            <option value="" @selected(!request('class_id'))>All Classes</option>
            @foreach($classes as $class)
            <option value="{{ $class->class_id }}" @selected((string)request('class_id') === (string)$class->class_id)>
                {{ $class->class_name }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="status" label="Status">
            <option value="" @selected(!request('status'))>All</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('sections.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Section</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class Teacher</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Capacity</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#10b981,#34d399);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:.75rem;font-weight:700;color:#fff;">{{ strtoupper(substr($item->section_name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">Section {{ $item->section_name }}</p>
                            @if($item->description)
                            <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">{{ Str::limit($item->description, 40) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->schoolClass->class_name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">
                    {{ $item->classTeacher ? $item->classTeacher->first_name.' '.$item->classTeacher->last_name : '—' }}
                </td>
                <td style="padding:.75rem 1rem;">
                    @if($item->capacity)
                    <span style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $item->capacity }}</span>
                    <span style="font-size:.75rem;color:#94a3b8;"> students</span>
                    @else
                    <span style="color:#cbd5e1;">—</span>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge :color="$item->is_active ? 'green' : 'gray'">
                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <x-core-package::btn :href="route('sections.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('sections.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('sections.destroy', $item) }}"
                              onsubmit="return confirm('Delete Section {{ $item->section_name }}?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">
                    No sections found.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
