@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('teacher-subject-mappings.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Teacher-Subject Mappings</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Detail</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Teacher-Subject Mapping</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
            {{ $teacherSubjectMapping->teacher ? $teacherSubjectMapping->teacher->first_name.' '.$teacherSubjectMapping->teacher->last_name : '—' }}
            &middot; {{ $teacherSubjectMapping->subject->subject_name ?? '—' }}
        </p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('teacher-subject-mappings.edit', $teacherSubjectMapping)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('teacher-subject-mappings.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="max-width:760px;">
    <x-core-package::card title="Mapping Details">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:.875rem;">
            @php
            function mappingRow(string $label, $value): void {
                echo '<div>'
                   . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                   . '<p style="font-weight:500;color:#1e293b;margin:0;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                   . '</div>';
            }
            @endphp
            @php
            $teacher = $teacherSubjectMapping->teacher;
            mappingRow('Teacher', $teacher ? $teacher->first_name.' '.$teacher->last_name : null);
            mappingRow('Subject', $teacherSubjectMapping->subject->subject_name ?? null);
            mappingRow('Class', $teacherSubjectMapping->schoolClass->class_name ?? null);
            mappingRow('Section', $teacherSubjectMapping->section->section_name ?? 'All Sections');
            @endphp
        </div>
    </x-core-package::card>

    <div style="margin-top:1.25rem;">
        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('teacher-subject-mappings.destroy', $teacherSubjectMapping) }}">
                @csrf @method('DELETE')
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Remove this mapping?')">
                    Remove Mapping
                </x-core-package::btn>
            </form>
        </x-core-package::card>
    </div>
</div>
@endsection
