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

        <x-core-package::card title="Room Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.select name="hostel_id" label="Hostel" required>
                    <option value="">— Select Hostel —</option>
                    @foreach($hostels as $h)
                    <option value="{{ $h->hostel_id }}"
                        @selected((string) old('hostel_id', $room->hostel_id ?? '') === (string) $h->hostel_id)>
                        {{ $h->hostel_name }} ({{ $h->hostel_type }})
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="room_number" label="Room Number" required
                    placeholder="e.g. 101"
                    value="{{ old('room_number', $room->room_number ?? '') }}" />

                <x-core-package::form.select name="room_type" label="Room Type" required>
                    <option value="">— Select Type —</option>
                    @foreach(['Single','Double','Triple','Quad'] as $t)
                    <option value="{{ $t }}" @selected(old('room_type', $room->room_type ?? '') === $t)>{{ $t }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="capacity" label="Capacity" type="number" required
                    placeholder="Number of beds"
                    value="{{ old('capacity', $room->capacity ?? '') }}" />

                <x-core-package::form.input
                    name="occupied" label="Occupied" type="number"
                    placeholder="Currently occupied beds"
                    value="{{ old('occupied', $room->occupied ?? 0) }}" />
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($room) ? 'Update Room' : 'Create Room' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('hostel-rooms.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
