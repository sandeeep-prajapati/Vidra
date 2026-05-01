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

        <x-core-package::card title="Section Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="section_name" label="Section Name" required
                    placeholder="e.g. A, B, C"
                    value="{{ old('section_name', $section->section_name ?? '') }}" />
                <x-core-package::form.input
                    name="capacity" label="Capacity" type="number" min="0"
                    placeholder="Max students allowed"
                    value="{{ old('capacity', $section->capacity ?? '') }}" />
                <x-core-package::form.select name="class_id" label="Class" required placeholder="Select Class">
                    <option value="">— Select Class —</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->class_id }}"
                        @selected((string) old('class_id', $section->class_id ?? request('class_id', '')) === (string) $class->class_id)>
                        {{ $class->class_name }}
                        @if($class->academicYear) ({{ $class->academicYear->year_range }}) @endif
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="class_teacher_id" label="Class Teacher" placeholder="Select Teacher">
                    <option value="">— No Teacher Assigned —</option>
                    @foreach($staff as $member)
                    <option value="{{ $member->staff_id }}"
                        @selected((string) old('class_teacher_id', $section->class_teacher_id ?? '') === (string) $member->staff_id)>
                        {{ $member->first_name }} {{ $member->last_name }}
                        @if($member->designation) – {{ $member->designation }} @endif
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="description" label="Description" rows="3"
                        placeholder="Optional additional information">{{ old('description', $section->description ?? '') }}</x-core-package::form.textarea>
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1"
                               style="width:1rem;height:1rem;accent-color:#4f46e5;"
                               {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
                        <span style="font-size:.875rem;font-weight:500;color:#374151;">Active</span>
                    </label>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($section) ? 'Update Section' : 'Create Section' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('sections.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>
