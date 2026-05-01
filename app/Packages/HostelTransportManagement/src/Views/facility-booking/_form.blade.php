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

        <x-core-package::card title="Booking Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="student_id" label="Student ID" type="number" required
                    placeholder="Enter student ID"
                    value="{{ old('student_id', $facilityBooking->student_id ?? '') }}" />

                <x-core-package::form.select name="facility_id" label="Facility" required>
                    <option value="">— Select Facility —</option>
                    @foreach($facilities as $f)
                    <option value="{{ $f->facility_id }}"
                        @selected((string) old('facility_id', $facilityBooking->facility_id ?? '') === (string) $f->facility_id)>
                        {{ $f->facility_name }} ({{ $f->facility_type }})
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="booking_date" label="Booking Date" type="date" required
                    value="{{ old('booking_date', isset($facilityBooking) ? $facilityBooking->booking_date?->format('Y-m-d') : '') }}" />

                <div></div>

                <x-core-package::form.input
                    name="start_time" label="Start Time" type="time" required
                    value="{{ old('start_time', $facilityBooking->start_time ?? '') }}" />

                <x-core-package::form.input
                    name="end_time" label="End Time" type="time" required
                    value="{{ old('end_time', $facilityBooking->end_time ?? '') }}" />
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($facilityBooking) ? 'Update Booking' : 'Create Booking' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('facility-bookings.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
