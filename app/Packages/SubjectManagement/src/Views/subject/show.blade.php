@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('subjects.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Subjects</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $subject->subject_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.375rem;height:1.375rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $subject->subject_name }}</h1>
                <x-core-package::badge :color="$subject->is_optional ? 'gray' : 'green'">
                    {{ $subject->is_optional ? 'Optional' : 'Mandatory' }}
                </x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                Code: {{ $subject->subject_code }} &middot; {{ $subject->subject_type }}
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('subjects.edit', $subject)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('subjects.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Curricula --}}
        <x-core-package::card :noPadding="true">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">Curriculum</p>
                <x-core-package::btn :href="route('curriculums.create').'?subject_id='.$subject->subject_id" color="primary" size="sm">+ Add</x-core-package::btn>
            </div>
            @if($subject->curriculums->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Academic Year</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Description</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subject->curriculums as $curriculum)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $curriculum->academicYear->year_range ?? '—' }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ Str::limit($curriculum->description, 60) ?: '—' }}</td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::btn :href="route('curriculums.show', $curriculum)" color="primary" size="sm">View</x-core-package::btn>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No curriculum yet.</p>
            @endif
        </x-core-package::card>

        {{-- Textbooks --}}
        <x-core-package::card :noPadding="true">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">Textbooks</p>
                <x-core-package::btn :href="route('textbooks.create').'?subject_id='.$subject->subject_id" color="primary" size="sm">+ Add</x-core-package::btn>
            </div>
            @if($subject->textbooks->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Title</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Author</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">ISBN</th>
                            <th style="padding:.5rem 1rem;border-bottom:1px solid #e2e8f0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subject->textbooks as $textbook)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">{{ $textbook->title }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $textbook->author ?: '—' }}</td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $textbook->isbn ?: '—' }}</td>
                        <td style="padding:.625rem 1rem;">
                            <x-core-package::btn :href="route('textbooks.show', $textbook)" color="primary" size="sm">View</x-core-package::btn>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No textbooks yet.</p>
            @endif
        </x-core-package::card>

        {{-- Teacher Assignments --}}
        <x-core-package::card :noPadding="true">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">Teacher Assignments</p>
                <x-core-package::btn :href="route('teacher-subject-mappings.create').'?subject_id='.$subject->subject_id" color="primary" size="sm">+ Assign</x-core-package::btn>
            </div>
            @if($subject->teacherMappings->count())
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.5rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Teacher</th>
                            <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Class</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subject->teacherMappings as $mapping)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.625rem 1.25rem;font-weight:600;color:#1e293b;">
                            {{ $mapping->teacher ? $mapping->teacher->first_name.' '.$mapping->teacher->last_name : '—' }}
                        </td>
                        <td style="padding:.625rem 1rem;color:#64748b;">{{ $mapping->schoolClass->class_name ?? '—' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No teacher assignments yet.</p>
            @endif
        </x-core-package::card>

    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <x-core-package::card title="Subject Details">
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                @php
                function subjectInfoRow(string $label, $value): void {
                    echo '<div>'
                       . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                       . '<p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                       . '</div>';
                }
                @endphp
                @php subjectInfoRow('Name', $subject->subject_name); @endphp
                @php subjectInfoRow('Code', $subject->subject_code); @endphp
                @php subjectInfoRow('Type', $subject->subject_type); @endphp
                @if($subject->description)
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Description</p>
                    <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $subject->description }}</p>
                </div>
                @endif
            </div>
        </x-core-package::card>

        <x-core-package::card title="Quick Stats">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#7c3aed;margin:0;">{{ $subject->classSubjects->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Classes</p>
                </div>
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#7c3aed;margin:0;">{{ $subject->textbooks->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Textbooks</p>
                </div>
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#7c3aed;margin:0;">{{ $subject->curriculums->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Curricula</p>
                </div>
                <div style="text-align:center;background:#f8fafc;border-radius:.5rem;padding:.875rem;">
                    <p style="font-size:1.5rem;font-weight:700;color:#7c3aed;margin:0;">{{ $subject->teacherMappings->count() }}</p>
                    <p style="font-size:.7rem;color:#64748b;margin:.25rem 0 0;">Teachers</p>
                </div>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('subjects.destroy', $subject) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                    Permanently delete this subject and all related data.
                </p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Delete subject {{ $subject->subject_name }}? This cannot be undone.')">
                    Delete Subject
                </x-core-package::btn>
            </form>
        </x-core-package::card>

    </div>

</div>
@endsection
