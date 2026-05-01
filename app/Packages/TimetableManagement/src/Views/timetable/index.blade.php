@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Timetable</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Timetable</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Weekly class schedule by class &amp; section</p>
    </div>
    <x-core-package::btn :href="route('timetable.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Entry
    </x-core-package::btn>
</div>

{{-- Filter bar --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('timetable.index') }}"
          style="display:grid;grid-template-columns:1fr 1fr 1fr auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="class_id" label="Class" id="filter-class">
            <option value="">Select Class</option>
            @foreach($classes as $class)
            <option value="{{ $class->class_id }}" @selected($selectedClassId == $class->class_id)>{{ $class->class_name }}</option>
            @endforeach
        </x-core-package::form.select>

        <x-core-package::form.select name="section_id" label="Section" id="filter-section">
            <option value="">Select Section</option>
            @foreach($sections as $section)
            <option value="{{ $section->section_id }}" @selected($selectedSectionId == $section->section_id)>{{ $section->section_name }}</option>
            @endforeach
        </x-core-package::form.select>

        <x-core-package::form.select name="academic_year_id" label="Academic Year">
            <option value="">Select Year</option>
            @foreach($academicYears as $year)
            <option value="{{ $year->academic_year_id }}" @selected($selectedAcademicYearId == $year->academic_year_id)>{{ $year->year_range }}</option>
            @endforeach
        </x-core-package::form.select>

        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">View</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('timetable.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

@if($selectedClassId && $selectedSectionId && $selectedAcademicYearId)
    {{-- Timetable Grid --}}
    <x-core-package::card :noPadding="true">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:.8125rem;min-width:600px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:.75rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #e2e8f0;width:120px;">Period / Day</th>
                        @foreach($days as $day)
                        <th style="padding:.75rem 1rem;text-align:center;font-size:.75rem;font-weight:600;color:#374151;border-bottom:2px solid #e2e8f0;border-left:1px solid #e2e8f0;">{{ $day->day_name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($periods as $period)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:.75rem 1.25rem;border-right:1px solid #e2e8f0;background:#f8fafc;">
                        <div style="font-size:.75rem;font-weight:600;color:#374151;">{{ \Carbon\Carbon::createFromFormat('H:i:s', $period->start_time)->format('h:i A') }}</div>
                        <div style="font-size:.65rem;color:#94a3b8;">{{ \Carbon\Carbon::createFromFormat('H:i:s', $period->end_time)->format('h:i A') }}</div>
                    </td>
                    @foreach($days as $day)
                    @php $entry = $timetableGrid[$day->day_id][$period->period_id] ?? null; @endphp
                    <td style="padding:.5rem .75rem;text-align:center;border-left:1px solid #f1f5f9;vertical-align:middle;">
                        @if($entry)
                        <div style="background:#eef2ff;border-radius:.5rem;padding:.5rem .75rem;text-align:left;">
                            <div style="font-size:.75rem;font-weight:600;color:#3730a3;">{{ $entry->subject?->subject_name ?? '—' }}</div>
                            <div style="font-size:.65rem;color:#6366f1;margin-top:.125rem;">{{ $entry->teacher?->first_name }} {{ $entry->teacher?->last_name }}</div>
                            <div style="font-size:.625rem;color:#94a3b8;margin-top:.125rem;">{{ $entry->room?->room_name }}</div>
                            <div style="display:flex;gap:.25rem;margin-top:.375rem;justify-content:flex-end;">
                                <a href="{{ route('timetable.edit', $entry) }}" style="font-size:.6rem;color:#6366f1;text-decoration:none;">Edit</a>
                                <span style="color:#cbd5e1;">·</span>
                                <form method="POST" action="{{ route('timetable.destroy', $entry) }}" onsubmit="return confirm('Remove this slot?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;font-size:.6rem;color:#ef4444;cursor:pointer;padding:0;">Del</button>
                                </form>
                            </div>
                        </div>
                        @else
                        <span style="color:#e2e8f0;font-size:.75rem;">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
                @if($periods->isEmpty())
                <tr>
                    <td colspan="{{ $days->count() + 1 }}" style="padding:2.5rem;text-align:center;color:#94a3b8;">No periods configured. <a href="{{ route('period.create') }}" style="color:#6366f1;">Add periods</a> first.</td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </x-core-package::card>
@else
    <x-core-package::card>
        <div style="text-align:center;padding:2rem 1rem;color:#94a3b8;">
            <svg style="width:3rem;height:3rem;margin:0 auto 1rem;color:#cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p style="font-size:.9375rem;font-weight:500;color:#64748b;margin:0;">Select a class, section and academic year to view the timetable</p>
        </div>
    </x-core-package::card>
@endif

<script>
document.getElementById('filter-class').addEventListener('change', function () {
    const classId = this.value;
    const sectionSelect = document.getElementById('filter-section');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        return;
    }
    fetch('/api/section?class_id=' + classId)
        .then(r => r.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            const items = data.data ?? data;
            (Array.isArray(items) ? items : []).forEach(s => {
                sectionSelect.innerHTML += `<option value="${s.section_id}">${s.section_name}</option>`;
            });
        })
        .catch(() => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
        });
});
</script>

@endsection
