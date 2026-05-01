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

        <x-core-package::card title="Subject Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="subject_name" label="Subject Name" required
                    placeholder="e.g. Mathematics, Science"
                    value="{{ old('subject_name', $subject->subject_name ?? '') }}" />
                <x-core-package::form.input
                    name="subject_code" label="Subject Code" required
                    placeholder="e.g. MATH101"
                    value="{{ old('subject_code', $subject->subject_code ?? '') }}" />
                <x-core-package::form.select name="subject_type" label="Subject Type">
                    @foreach(['Theory', 'Practical', 'Lab'] as $type)
                    <option value="{{ $type }}" @selected(old('subject_type', $subject->subject_type ?? 'Theory') === $type)>{{ $type }}</option>
                    @endforeach
                </x-core-package::form.select>
                <div style="display:flex;align-items:center;gap:.625rem;padding-top:1.5rem;">
                    <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                        <input type="checkbox" name="is_optional" value="1"
                               style="width:1rem;height:1rem;accent-color:#4f46e5;"
                               {{ old('is_optional', $subject->is_optional ?? false) ? 'checked' : '' }}>
                        <span style="font-size:.875rem;font-weight:500;color:#374151;">Optional Subject</span>
                    </label>
                </div>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="description" label="Description" rows="3"
                        placeholder="Brief overview of this subject">{{ old('description', $subject->description ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($subject) ? 'Update Subject' : 'Create Subject' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('subjects.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>
