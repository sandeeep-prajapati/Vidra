@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.events.index') }}" style="color:#64748b;text-decoration:none;">Events</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $event->title }}</span>
</nav>
@endsection

@section('content')

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card style="margin-bottom:1.25rem;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
        <div>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.5rem;">
                <h1 style="font-size:1.25rem;font-weight:700;color:#1e293b;margin:0;">{{ $event->title }}</h1>
                <x-core-package::badge color="{{ match($event->status) { 'upcoming' => 'yellow', 'ongoing' => 'blue', 'completed' => 'green', default => 'red' } }}">
                    {{ ucfirst($event->status) }}
                </x-core-package::badge>
                <x-core-package::badge color="indigo">{{ ucfirst($event->event_type) }}</x-core-package::badge>
            </div>
            <div style="font-size:.875rem;color:#64748b;">
                {{ $event->event_date->format('l, d F Y \a\t g:i A') }}
            </div>
            @if($event->venue)
            <div style="font-size:.875rem;color:#64748b;margin-top:.25rem;">{{ $event->venue }}</div>
            @endif
        </div>
        <x-core-package::btn :href="route('alumni.events.registrations', $event)" color="secondary">
            View RSVPs ({{ $event->registrations_count }})
        </x-core-package::btn>
    </div>

    @if($event->description)
    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;font-size:.875rem;color:#475569;line-height:1.6;">
        {{ $event->description }}
    </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.25rem;padding-top:1rem;border-top:1px solid #f1f5f9;">
        <div>
            <div style="font-size:.75rem;color:#94a3b8;">Registrations</div>
            <div style="font-size:1.25rem;font-weight:700;color:#4f46e5;">{{ $event->registrations_count }}</div>
        </div>
        <div>
            <div style="font-size:.75rem;color:#94a3b8;">Max Attendees</div>
            <div style="font-size:1.25rem;font-weight:700;color:#1e293b;">{{ $event->max_attendees ?? '∞' }}</div>
        </div>
        <div>
            <div style="font-size:.75rem;color:#94a3b8;">Registration Deadline</div>
            <div style="font-size:.875rem;font-weight:600;color:#1e293b;">{{ $event->registration_deadline ? $event->registration_deadline->format('d M Y') : 'Open' }}</div>
        </div>
    </div>
</x-core-package::card>

{{-- Quick register form --}}
@if($event->status === 'upcoming')
<x-core-package::card>
    <h2 style="font-size:.9375rem;font-weight:700;color:#1e293b;margin:0 0 1rem;">Register an Alumni</h2>
    <form method="POST" action="{{ route('alumni.events.register', $event) }}" style="display:flex;gap:.75rem;align-items:flex-end;">
        @csrf
        <div style="flex:1;">
            <x-core-package::form.input name="alumni_id" label="Alumni ID" type="number" placeholder="Enter alumni ID" required />
        </div>
        <div style="padding-top:1.375rem;">
            <x-core-package::btn type="submit" color="primary">Register</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>
@endif

@endsection
