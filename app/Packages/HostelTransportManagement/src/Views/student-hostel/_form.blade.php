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

        <x-core-package::card title="Assignment Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="student_id" label="Student ID" type="number" required
                    placeholder="Enter student ID"
                    value="{{ old('student_id', $studentHostel->student_id ?? '') }}" />

                <x-core-package::form.select name="hostel_id" label="Hostel" required>
                    <option value="">— Select Hostel —</option>
                    @foreach($hostels as $h)
                    <option value="{{ $h->hostel_id }}"
                        @selected((string) old('hostel_id', $studentHostel->hostel_id ?? '') === (string) $h->hostel_id)>
                        {{ $h->hostel_name }}
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.select name="room_id" label="Room" required>
                    <option value="">— Select Room —</option>
                    @foreach($rooms as $r)
                    <option value="{{ $r->room_id }}"
                        @selected((string) old('room_id', $studentHostel->room_id ?? '') === (string) $r->room_id)>
                        {{ $r->hostel->hostel_name ?? '' }} - Room {{ $r->room_number }} ({{ $r->room_type }})
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <div></div>

                <x-core-package::form.input
                    name="assigned_date" label="Assigned Date" type="date" required
                    value="{{ old('assigned_date', isset($studentHostel) ? $studentHostel->assigned_date?->format('Y-m-d') : '') }}" />

                <x-core-package::form.input
                    name="checkout_date" label="Checkout Date" type="date"
                    hint="Leave empty if still active"
                    value="{{ old('checkout_date', isset($studentHostel) ? $studentHostel->checkout_date?->format('Y-m-d') : '') }}" />
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($studentHostel) ? 'Update Assignment' : 'Create Assignment' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('student-hostels.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
