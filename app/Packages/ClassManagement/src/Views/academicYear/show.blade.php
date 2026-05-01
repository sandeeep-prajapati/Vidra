@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('academic-years.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Academic Years</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $academicYear->year_range }}</span>
</nav>
@endsection

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.375rem;height:1.375rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $academicYear->year_range }}</h1>
                @if($academicYear->is_current)
                <x-core-package::badge color="green">Current</x-core-package::badge>
                @else
                <x-core-package::badge color="gray">Past</x-core-package::badge>
                @endif
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                {{ optional($academicYear->start_date)->format('M d, Y') }} – {{ optional($academicYear->end_date)->format('M d, Y') }}
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('academic-years.edit', $academicYear)" color="primary" size="sm">
            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </x-core-package::btn>
        <x-core-package::btn :href="route('academic-years.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Classes --}}
        <x-core-package::card title="Classes in this Year" :noPadding="true">
            @if($academicYear->classes->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Code</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($academicYear->classes as $class)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $class->class_name }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $class->class_code ?: '—' }}</td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::badge :color="$class->is_active ? 'green' : 'gray'">
                                {{ $class->is_active ? 'Active' : 'Inactive' }}
                            </x-core-package::badge>
                        </td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::btn :href="route('classes.show', $class)" color="primary" size="sm">View</x-core-package::btn>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No classes linked to this year.</p>
            @endif
        </x-core-package::card>

        {{-- Batches --}}
        <x-core-package::card title="Batches in this Year" :noPadding="true">
            @if($academicYear->batches->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batch</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Section</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($academicYear->batches as $batch)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $batch->batch_name }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $batch->schoolClass->class_name ?? '—' }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $batch->section->section_name ?? '—' }}</td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::btn :href="route('batches.show', $batch)" color="primary" size="sm">View</x-core-package::btn>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No batches created for this year.</p>
            @endif
        </x-core-package::card>

    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <x-core-package::card title="Details">
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                @php
                function ayInfoRow(string $label, $value): void {
                    echo '<div>'
                       . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                       . '<p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                       . '</div>';
                }
                @endphp
                @php ayInfoRow('Year Range', $academicYear->year_range); @endphp
                @php ayInfoRow('Start Date', optional($academicYear->start_date)->format('M d, Y')); @endphp
                @php ayInfoRow('End Date', optional($academicYear->end_date)->format('M d, Y')); @endphp
                @if($academicYear->description)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Description</p>
                    <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $academicYear->description }}</p>
                </div>
                @endif
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Status</p>
                    @if($academicYear->is_current)
                    <x-core-package::badge color="green">Current Year</x-core-package::badge>
                    @else
                    <x-core-package::badge color="gray">Past Year</x-core-package::badge>
                    @endif
                </div>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Quick Stats">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#4f46e5;margin:0;">{{ $academicYear->classes->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Classes</p>
                </div>
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#4f46e5;margin:0;">{{ $academicYear->batches->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Batches</p>
                </div>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('academic-years.destroy', $academicYear) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                    Permanently delete this academic year. Classes and batches linked to it will lose this association.
                </p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete academic year {{ $academicYear->year_range }}? This cannot be undone.')">
                    Delete Academic Year
                </x-core-package::btn>
            </form>
        </x-core-package::card>

    </div>

</div>
@endsection
