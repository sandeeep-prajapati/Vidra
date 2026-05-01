@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('room.index') }}" style="color:#64748b;text-decoration:none;">Rooms</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $room->room_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $room->room_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Room Details</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('room.edit', $room)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('room.destroy', $room) }}" onsubmit="return confirm('Delete this room?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <x-core-package::card title="Room Information">
        <dl style="display:grid;gap:.875rem;">
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Room Name</dt>
                <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $room->room_name }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Type</dt>
                <dd style="margin:.25rem 0 0;">
                    @php $colors = ['Classroom'=>'blue','Lab'=>'indigo','Auditorium'=>'purple','Office'=>'gray']; @endphp
                    <x-core-package::badge color="{{ $colors[$room->room_type] ?? 'gray' }}">{{ $room->room_type }}</x-core-package::badge>
                </dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Capacity</dt>
                <dd style="font-size:.9375rem;color:#1e293b;margin:.25rem 0 0;">{{ $room->capacity }} seats</dd>
            </div>
            @if($room->description)
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Description</dt>
                <dd style="font-size:.875rem;color:#475569;margin:.25rem 0 0;">{{ $room->description }}</dd>
            </div>
            @endif
        </dl>
    </x-core-package::card>

    <x-core-package::card title="Usage">
        <p style="font-size:.8125rem;color:#64748b;margin:0;">
            This room is used in <strong>{{ $room->timetables->count() }}</strong> timetable slot(s)
            and <strong>{{ $room->specialEvents->count() }}</strong> special event(s).
        </p>
    </x-core-package::card>
</div>

@endsection
