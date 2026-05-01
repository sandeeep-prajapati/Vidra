@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('substituteAssignment.index') }}" style="color:#64748b;text-decoration:none;">Substitutions</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Edit Substitution</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Substitute Assignment</h1>
    </div>

    <x-core-package::card>
        <form method="POST" action="{{ route('substituteAssignment.update', $substituteAssignment) }}">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:1.25rem;">
                <x-core-package::form.select name="timetable_id" label="Timetable Slot" required>
                    <option value="">Select Slot</option>
                    @foreach($timetables as $tt)
                    <option value="{{ $tt->timetable_id }}" @selected(old('timetable_id', $substituteAssignment->timetable_id) == $tt->timetable_id)>
                        {{ $tt->schoolClass?->class_name }} – {{ $tt->section?->section_name }} |
                        {{ $tt->day?->day_name }} |
                        {{ $tt->subject?->subject_name }} |
                        {{ $tt->period?->start_time }} – {{ $tt->period?->end_time }}
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.select name="original_teacher_id" label="Original Teacher" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->staff_id }}" @selected(old('original_teacher_id', $substituteAssignment->original_teacher_id) == $teacher->staff_id)>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.select name="substitute_teacher_id" label="Substitute Teacher" required>
                    <option value="">Select Substitute</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->staff_id }}" @selected(old('substitute_teacher_id', $substituteAssignment->substitute_teacher_id) == $teacher->staff_id)>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input name="date_of_substitution" label="Date of Substitution" type="date" required
                    value="{{ old('date_of_substitution', $substituteAssignment->date_of_substitution?->format('Y-m-d')) }}" />

                @if($errors->any())
                <x-core-package::alert type="error">
                    <ul style="margin:0;padding-left:1rem;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-core-package::alert>
                @endif

                <div style="display:flex;gap:.75rem;">
                    <x-core-package::btn type="submit" color="primary">Update Substitution</x-core-package::btn>
                    <x-core-package::btn :href="route('substituteAssignment.index')" color="secondary">Cancel</x-core-package::btn>
                </div>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
