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

        <x-core-package::card title="Academic Year Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="year_range" label="Year Range" required
                        placeholder="e.g. 2024-2025"
                        value="{{ old('year_range', $academicYear->year_range ?? '') }}" />
                </div>
                <x-core-package::form.input
                    name="start_date" label="Start Date" type="date" required
                    value="{{ old('start_date', optional($academicYear?->start_date)->format('Y-m-d')) }}" />
                <x-core-package::form.input
                    name="end_date" label="End Date" type="date" required
                    value="{{ old('end_date', optional($academicYear?->end_date)->format('Y-m-d')) }}" />
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="description" label="Description" rows="3"
                        placeholder="Optional additional information">{{ old('description', $academicYear->description ?? '') }}</x-core-package::form.textarea>
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                        <input type="checkbox" name="is_current" value="1"
                               style="width:1rem;height:1rem;accent-color:#4f46e5;"
                               {{ old('is_current', $academicYear->is_current ?? false) ? 'checked' : '' }}>
                        <span style="font-size:.875rem;font-weight:500;color:#374151;">Mark as Current Academic Year</span>
                    </label>
                    <p style="font-size:.75rem;color:#94a3b8;margin:.25rem 0 0 1.625rem;">
                        Only one year can be current at a time. Enabling this will unset the previous current year.
                    </p>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($academicYear) ? 'Update Academic Year' : 'Create Academic Year' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('academic-years.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>
