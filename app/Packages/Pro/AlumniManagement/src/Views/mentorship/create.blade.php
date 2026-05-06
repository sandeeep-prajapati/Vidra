@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.mentorship.index') }}" style="color:#64748b;text-decoration:none;">Mentorship</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">New Pairing</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;margin:0 auto;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Create Mentorship Pairing</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('alumni.mentorship.store') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div style="grid-column:1/-1;">
                <x-core-package::form.select name="mentor_alumni_id" label="Mentor (Alumni)" required>
                    <option value="">Select alumni mentor...</option>
                    @foreach($alumni as $a)
                    <option value="{{ $a->id }}" {{ old('mentor_alumni_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->full_name }} ({{ $a->graduation_year }})
                    </option>
                    @endforeach
                </x-core-package::form.select>
                @error('mentor_alumni_id')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="mentee_name" label="Mentee Name" :value="old('mentee_name')" required />
                @error('mentee_name')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="mentee_student_id" label="Mentee Student ID (optional)" type="number" :value="old('mentee_student_id')" />
            </div>
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="area_of_mentorship" label="Area of Mentorship" :value="old('area_of_mentorship')" placeholder="e.g. Career Guidance, Higher Studies, Entrepreneurship" required />
            </div>
            <div>
                <x-core-package::form.input name="start_date" label="Start Date" type="date" :value="old('start_date', date('Y-m-d'))" required />
            </div>
            <div>
                <x-core-package::form.input name="end_date" label="Expected End Date (optional)" type="date" :value="old('end_date')" />
            </div>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <x-core-package::btn :href="route('alumni.mentorship.index')" color="secondary">Cancel</x-core-package::btn>
            <x-core-package::btn type="submit" color="primary">Create Pairing</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
