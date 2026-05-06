@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.events.index') }}" style="color:#64748b;text-decoration:none;">Events</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Create Event</span>
</nav>
@endsection

@section('content')

<div style="max-width:720px;margin:0 auto;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Create Alumni Event</h1>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('alumni.events.store') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="title" label="Event Title" :value="old('title')" required />
                @error('title')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.select name="event_type" label="Event Type" required>
                    @foreach(['reunion','webinar','workshop','social'] as $type)
                    <option value="{{ $type }}" {{ old('event_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.select name="status" label="Status" required>
                    @foreach(['upcoming','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status','upcoming') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.input name="event_date" label="Event Date & Time" type="datetime-local" :value="old('event_date')" required />
                @error('event_date')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="venue" label="Venue" :value="old('venue')" placeholder="Location or online link" />
            </div>
            <div>
                <x-core-package::form.input name="max_attendees" label="Max Attendees" type="number" :value="old('max_attendees')" placeholder="Leave blank for unlimited" />
            </div>
            <div>
                <x-core-package::form.input name="registration_deadline" label="Registration Deadline" type="date" :value="old('registration_deadline')" />
            </div>
            <div>
                <x-core-package::form.select name="organizer_alumni_id" label="Organizer (Alumni)">
                    <option value="">School / Admin</option>
                    @foreach($alumni as $a)
                    <option value="{{ $a->id }}" {{ old('organizer_alumni_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->full_name }} ({{ $a->graduation_year }})
                    </option>
                    @endforeach
                </x-core-package::form.select>
            </div>
        </div>

        <div style="margin-bottom:1.25rem;">
            <x-core-package::form.textarea name="description" label="Description" rows="4" :value="old('description')" />
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <x-core-package::btn :href="route('alumni.events.index')" color="secondary">Cancel</x-core-package::btn>
            <x-core-package::btn type="submit" color="primary">Create Event</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
