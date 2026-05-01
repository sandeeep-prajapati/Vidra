@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('batches.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Batches</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $batch->batch_name }}</span>
</nav>
@endsection

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.375rem;height:1.375rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $batch->batch_name }}</h1>
                <x-core-package::badge :color="$batch->is_active ? 'green' : 'gray'">
                    {{ $batch->is_active ? 'Active' : 'Inactive' }}
                </x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                {{ $batch->schoolClass->class_name ?? '' }}
                @if($batch->section) &middot; Section {{ $batch->section->section_name }} @endif
                @if($batch->academicYear) &middot; {{ $batch->academicYear->year_range }} @endif
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('batches.edit', $batch)" color="primary" size="sm">
            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </x-core-package::btn>
        <x-core-package::btn :href="route('batches.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Enrolled Students --}}
        <x-core-package::card :noPadding="true">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">
                    Enrolled Students
                    @if($batch->enrollments->count())
                    <span style="margin-left:.5rem;font-size:.75rem;font-weight:500;color:#64748b;">
                        ({{ $batch->enrollments->count() }} total)
                    </span>
                    @endif
                </p>
            </div>
            @if($batch->enrollments->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Student</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Enrolled On</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($batch->enrollments as $enrollment)
                    @php
                    $eColor = match($enrollment->status) {
                        'Active'      => 'green',
                        'Graduated'   => 'indigo',
                        'Transferred' => 'yellow',
                        default       => 'gray',
                    };
                    $initials = strtoupper(
                        substr($enrollment->student->first_name ?? '?', 0, 1) .
                        substr($enrollment->student->last_name  ?? '', 0, 1)
                    );
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;">
                            <div style="display:flex;align-items:center;gap:.625rem;">
                                <div style="width:1.875rem;height:1.875rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <span style="font-size:.6rem;font-weight:700;color:#fff;">{{ $initials }}</span>
                                </div>
                                <span style="font-size:.875rem;font-weight:500;color:#1e293b;">
                                    {{ $enrollment->student->first_name ?? 'Unknown' }} {{ $enrollment->student->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td style="padding:.625rem 1rem;color:#64748b;">
                            {{ optional($enrollment->enrollment_date)->format('M d, Y') ?: '—' }}
                        </td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::badge :color="$eColor">{{ $enrollment->status }}</x-core-package::badge>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No students enrolled yet.</p>
            @endif
        </x-core-package::card>

    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <x-core-package::card title="Batch Details">
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                @php
                function batchInfoRow(string $label, $value): void {
                    echo '<div>'
                       . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                       . '<p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                       . '</div>';
                }
                @endphp
                @php batchInfoRow('Batch Name', $batch->batch_name); @endphp
                @php batchInfoRow('Batch Code', $batch->batch_code); @endphp
                @php batchInfoRow('Class', $batch->schoolClass->class_name ?? null); @endphp
                @php batchInfoRow('Section', $batch->section ? 'Section '.$batch->section->section_name : null); @endphp
                @php batchInfoRow('Academic Year', $batch->academicYear->year_range ?? null); @endphp
                @php
                $duration = null;
                if ($batch->start_date) {
                    $duration = optional($batch->start_date)->format('M d, Y');
                    if ($batch->end_date) $duration .= ' – '.optional($batch->end_date)->format('M d, Y');
                }
                batchInfoRow('Duration', $duration);
                @endphp
                @if($batch->start_time)
                @php
                $timing = optional($batch->start_time)->format('h:i A');
                if ($batch->end_time) $timing .= ' – '.optional($batch->end_time)->format('h:i A');
                batchInfoRow('Timing', $timing);
                @endphp
                @endif
                @if($batch->description)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Description</p>
                    <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $batch->description }}</p>
                </div>
                @endif
            </div>
        </x-core-package::card>

        <x-core-package::card title="Quick Stats">
            <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                <p style="font-size:1.5rem;font-weight:700;color:#f59e0b;margin:0;">{{ $batch->enrollments->count() }}</p>
                <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Enrolled Students</p>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('batches.destroy', $batch) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                    Permanently delete this batch. All student enrollments linked to it will also be removed.
                </p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete batch {{ $batch->batch_name }}? This cannot be undone.')">
                    Delete Batch
                </x-core-package::btn>
            </form>
        </x-core-package::card>

    </div>

</div>
@endsection
