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

        <x-core-package::card title="Class-Subject Assignment">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.select name="class_id" label="Class" required>
                    <option value="">— Select Class —</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->class_id }}"
                        @selected((string) old('class_id', $classSubject->class_id ?? '') === (string) $class->class_id)>
                        {{ $class->class_name }}
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.select name="subject_id" label="Subject" required>
                    <option value="">— Select Subject —</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->subject_id }}"
                        @selected((string) old('subject_id', $classSubject->subject_id ?? '') === (string) $subject->subject_id)>
                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                    </option>
                    @endforeach
                </x-core-package::form.select>
                <div style="display:flex;align-items:center;gap:.625rem;padding-top:1.5rem;">
                    <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                        <input type="checkbox" name="is_mandatory" value="1"
                               style="width:1rem;height:1rem;accent-color:#4f46e5;"
                               {{ old('is_mandatory', $classSubject->is_mandatory ?? true) ? 'checked' : '' }}>
                        <span style="font-size:.875rem;font-weight:500;color:#374151;">Mandatory for this class</span>
                    </label>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($classSubject) ? 'Update Assignment' : 'Assign Subject' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('class-subjects.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
