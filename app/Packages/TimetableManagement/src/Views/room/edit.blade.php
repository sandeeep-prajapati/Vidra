@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('room.index') }}" style="color:#64748b;text-decoration:none;">Rooms</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Edit Room</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Room</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $room->room_name }}</p>
    </div>

    <x-core-package::card>
        <form method="POST" action="{{ route('room.update', $room) }}">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:1.25rem;">
                <x-core-package::form.input name="room_name" label="Room Name" required
                    value="{{ old('room_name', $room->room_name) }}" />

                <x-core-package::form.select name="room_type" label="Room Type" required>
                    <option value="">Select type</option>
                    @foreach(['Classroom','Lab','Auditorium','Office'] as $type)
                    <option value="{{ $type }}" @selected(old('room_type', $room->room_type) === $type)>{{ $type }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input name="capacity" label="Capacity" type="number" required
                    value="{{ old('capacity', $room->capacity) }}" />

                <x-core-package::form.textarea name="description" label="Description" rows="3">{{ old('description', $room->description) }}</x-core-package::form.textarea>

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
                    <x-core-package::btn type="submit" color="primary">Update Room</x-core-package::btn>
                    <x-core-package::btn :href="route('room.index')" color="secondary">Cancel</x-core-package::btn>
                </div>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
