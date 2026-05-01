<div style="max-width:880px;margin:0 auto;">

    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0 0 1.5rem;">{{ $title }}</h1>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1.25rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <form method="POST" action="{{ $action }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        {{-- Basic Info --}}
        <x-core-package::card title="Batch Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="batch_name" label="Batch Name" required
                    placeholder="e.g. 2024-2025 Morning"
                    value="{{ old('batch_name', $batch->batch_name ?? '') }}" />
                <x-core-package::form.input
                    name="batch_code" label="Batch Code"
                    placeholder="Optional unique code"
                    value="{{ old('batch_code', $batch->batch_code ?? '') }}" />
                <x-core-package::form.select name="class_id" label="Class" required placeholder="Select Class" id="batch_class_id">
                    <option value="">— Select Class —</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->class_id }}"
                        @selected((string) old('class_id', $batch->class_id ?? request('class_id', '')) === (string) $class->class_id)>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="section_id" label="Section" required placeholder="Select Section" id="batch_section_id">
                    <option value="">— Select Section —</option>
                    @foreach($sections as $section)
                    @php
                    $selectedClassId = old('class_id', $batch->class_id ?? request('class_id', ''));
                    @endphp
                    @if(!$selectedClassId || (string)$section->class_id === (string)$selectedClassId)
                    <option value="{{ $section->section_id }}"
                        @selected((string) old('section_id', $batch->section_id ?? request('section_id', '')) === (string) $section->section_id)>
                        {{ $section->section_name }}
                        @if(!$selectedClassId && $section->schoolClass) ({{ $section->schoolClass->class_name }}) @endif
                    </option>
                    @endif
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="academic_year_id" label="Academic Year" placeholder="Select Academic Year">
                    <option value="">— None —</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->academic_year_id }}"
                        @selected((string) old('academic_year_id', $batch->academic_year_id ?? '') === (string) $year->academic_year_id)>
                        {{ $year->year_range }}
                        @if($year->is_current) (Current) @endif
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <div style="display:flex;align-items:center;gap:.625rem;padding-top:1.5rem;">
                    <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1"
                               style="width:1rem;height:1rem;accent-color:#4f46e5;"
                               {{ old('is_active', $batch->is_active ?? true) ? 'checked' : '' }}>
                        <span style="font-size:.875rem;font-weight:500;color:#374151;">Active</span>
                    </label>
                </div>
            </div>
        </x-core-package::card>

        {{-- Schedule --}}
        <x-core-package::card title="Schedule & Timing">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="start_date" label="Start Date" type="date"
                    value="{{ old('start_date', optional($batch?->start_date)->format('Y-m-d')) }}" />
                <x-core-package::form.input
                    name="end_date" label="End Date" type="date"
                    value="{{ old('end_date', optional($batch?->end_date)->format('Y-m-d')) }}" />
                <x-core-package::form.input
                    name="start_time" label="Start Time" type="time"
                    value="{{ old('start_time', optional($batch?->start_time)->format('H:i')) }}" />
                <x-core-package::form.input
                    name="end_time" label="End Time" type="time"
                    value="{{ old('end_time', optional($batch?->end_time)->format('H:i')) }}" />
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="description" label="Description" rows="3"
                        placeholder="Optional additional information">{{ old('description', $batch->description ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($batch) ? 'Update Batch' : 'Create Batch' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('batches.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>

<script>
(function() {
    const classSelect   = document.getElementById('batch_class_id');
    const sectionSelect = document.getElementById('batch_section_id');
    if (!classSelect || !sectionSelect) return;

    const allSections = Array.from(sectionSelect.options).slice(1).map(o => ({
        value: o.value,
        text:  o.text,
        classId: o.getAttribute('data-class') || '',
    }));

    function filterSections(classId) {
        const current = sectionSelect.value;
        while (sectionSelect.options.length > 1) sectionSelect.remove(1);
        allSections.forEach(s => {
            if (!classId || s.classId === String(classId)) {
                const opt = new Option(s.text, s.value);
                if (s.value === current) opt.selected = true;
                sectionSelect.add(opt);
            }
        });
    }

    classSelect.addEventListener('change', () => filterSections(classSelect.value));
})();
</script>
