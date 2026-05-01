<div style="max-width:760px;margin:0 auto;">
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

        <x-core-package::card title="Curriculum Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.select name="subject_id" label="Subject" required>
                    <option value="">— Select Subject —</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->subject_id }}"
                        @selected((string) old('subject_id', $curriculum->subject_id ?? request('subject_id')) === (string) $subject->subject_id)>
                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="academic_year_id" label="Academic Year" required>
                    <option value="">— Select Year —</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->academic_year_id }}"
                        @selected((string) old('academic_year_id', $curriculum->academic_year_id ?? '') === (string) $year->academic_year_id)>
                        {{ $year->year_range }}@if($year->is_current) (Current)@endif
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="syllabus_document_path" label="Syllabus Document Path"
                        placeholder="e.g. /uploads/syllabus/math_2026.pdf"
                        value="{{ old('syllabus_document_path', $curriculum->syllabus_document_path ?? '') }}" />
                </div>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="description" label="Description" rows="4"
                        placeholder="Brief syllabus outline">{{ old('description', $curriculum->description ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($curriculum) ? 'Update Curriculum' : 'Create Curriculum' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('curriculums.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
