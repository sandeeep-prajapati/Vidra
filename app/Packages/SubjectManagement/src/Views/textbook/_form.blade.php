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

        <x-core-package::card title="Textbook Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <div style="grid-column:1/-1;">
                    <x-core-package::form.select name="subject_id" label="Subject" required>
                        <option value="">— Select Subject —</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->subject_id }}"
                            @selected((string) old('subject_id', $textbook->subject_id ?? request('subject_id')) === (string) $subject->subject_id)>
                            {{ $subject->subject_name }} ({{ $subject->subject_code }})
                        </option>
                        @endforeach
                    </x-core-package::form.select>
                </div>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="title" label="Title" required
                        placeholder="e.g. Mathematics for Class 10"
                        value="{{ old('title', $textbook->title ?? '') }}" />
                </div>
                <x-core-package::form.input
                    name="author" label="Author"
                    placeholder="e.g. R.D. Sharma"
                    value="{{ old('author', $textbook->author ?? '') }}" />
                <x-core-package::form.input
                    name="publisher" label="Publisher"
                    placeholder="e.g. NCERT"
                    value="{{ old('publisher', $textbook->publisher ?? '') }}" />
                <x-core-package::form.input
                    name="edition" label="Edition"
                    placeholder="e.g. 2024 Edition"
                    value="{{ old('edition', $textbook->edition ?? '') }}" />
                <x-core-package::form.input
                    name="isbn" label="ISBN"
                    placeholder="e.g. 978-0-06-112008-4"
                    value="{{ old('isbn', $textbook->isbn ?? '') }}" />
                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="textbook_file_path" label="Digital Copy Path"
                        placeholder="e.g. /uploads/textbooks/math10.pdf"
                        value="{{ old('textbook_file_path', $textbook->textbook_file_path ?? '') }}" />
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($textbook) ? 'Update Textbook' : 'Add Textbook' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('textbooks.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
