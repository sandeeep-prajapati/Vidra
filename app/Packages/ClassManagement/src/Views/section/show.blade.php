@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('sections.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Sections</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Section {{ $section->section_name }}</span>
</nav>
@endsection

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#10b981,#34d399);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:1.125rem;font-weight:700;color:#fff;">{{ strtoupper(substr($section->section_name, 0, 2)) }}</span>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">
                    Section {{ $section->section_name }}
                    @if($section->schoolClass) — {{ $section->schoolClass->class_name }} @endif
                </h1>
                <x-core-package::badge :color="$section->is_active ? 'green' : 'gray'">
                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                </x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                @if($section->classTeacher)
                    Class Teacher: {{ $section->classTeacher->first_name }} {{ $section->classTeacher->last_name }}
                @else
                    No class teacher assigned
                @endif
                @if($section->capacity) &middot; Capacity: {{ $section->capacity }} @endif
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('sections.edit', $section)" color="primary" size="sm">
            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </x-core-package::btn>
        <x-core-package::btn :href="route('sections.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Batches --}}
        <x-core-package::card :noPadding="true">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">Batches</p>
                <x-core-package::btn :href="route('batches.create').'?section_id='.$section->section_id" color="primary" size="sm">
                    + Add Batch
                </x-core-package::btn>
            </div>
            @if($section->batches->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batch</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Academic Year</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Dates</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($section->batches as $batch)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $batch->batch_name }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $batch->academicYear->year_range ?? '—' }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">
                            {{ optional($batch->start_date)->format('M d, Y') ?: '—' }}
                        </td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::badge :color="$batch->is_active ? 'green' : 'gray'">
                                {{ $batch->is_active ? 'Active' : 'Inactive' }}
                            </x-core-package::badge>
                        </td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::btn :href="route('batches.show', $batch)" color="primary" size="sm">View</x-core-package::btn>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No batches for this section yet.</p>
            @endif
        </x-core-package::card>

    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <x-core-package::card title="Section Details">
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                @php
                function secInfoRow(string $label, $value): void {
                    echo '<div>'
                       . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                       . '<p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                       . '</div>';
                }
                @endphp
                @php secInfoRow('Section Name', $section->section_name); @endphp
                @php secInfoRow('Class', $section->schoolClass->class_name ?? null); @endphp
                @php secInfoRow('Capacity', $section->capacity ? $section->capacity.' students' : null); @endphp
                @php
                $teacher = $section->classTeacher ? $section->classTeacher->first_name.' '.$section->classTeacher->last_name : null;
                secInfoRow('Class Teacher', $teacher);
                @endphp
                @if($section->description)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Description</p>
                    <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $section->description }}</p>
                </div>
                @endif
            </div>
        </x-core-package::card>

        <x-core-package::card title="Quick Stats">
            <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                <p style="font-size:1.5rem;font-weight:700;color:#10b981;margin:0;">{{ $section->batches->count() }}</p>
                <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Batches</p>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('sections.destroy', $section) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                    Permanently delete this section. All linked batches will lose this section reference.
                </p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete Section {{ $section->section_name }}? This cannot be undone.')">
                    Delete Section
                </x-core-package::btn>
            </form>
        </x-core-package::card>

    </div>

</div>
@endsection
