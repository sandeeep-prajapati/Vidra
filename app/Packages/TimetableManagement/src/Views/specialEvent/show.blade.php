@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('specialEvent.index') }}" style="color:#64748b;text-decoration:none;">Special Events</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $specialEvent->event_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $specialEvent->event_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $specialEvent->event_date?->format('d M Y') }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('specialEvent.edit', $specialEvent)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('specialEvent.destroy', $specialEvent) }}" onsubmit="return confirm('Delete this event?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
    <x-core-package::card title="Event Details">
        <dl style="display:grid;gap:.875rem;">
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Event Name</dt>
                <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $specialEvent->event_name }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Date</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">{{ $specialEvent->event_date?->format('d M Y') }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Time</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">{{ $specialEvent->start_time }} – {{ $specialEvent->end_time }}</dd>
            </div>
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Venue</dt>
                <dd style="font-size:.9375rem;color:#475569;margin:.25rem 0 0;">
                    @if($specialEvent->room)
                    {{ $specialEvent->room->room_name }}
                    <x-core-package::badge color="blue">{{ $specialEvent->room->room_type }}</x-core-package::badge>
                    @else
                    <span style="color:#94a3b8;">Not specified</span>
                    @endif
                </dd>
            </div>
            @if($specialEvent->description)
            <div>
                <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Description</dt>
                <dd style="font-size:.875rem;color:#475569;margin:.25rem 0 0;line-height:1.5;">{{ $specialEvent->description }}</dd>
            </div>
            @endif
        </dl>
    </x-core-package::card>
</div>

@endsection
