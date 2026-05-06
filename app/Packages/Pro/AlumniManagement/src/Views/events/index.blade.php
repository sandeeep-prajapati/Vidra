@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Alumni / Events</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Alumni Events</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Reunions, webinars, and community events</p>
    </div>
    @can('create_alumni-management_item')
    <x-core-package::btn :href="route('alumni.events.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Event
    </x-core-package::btn>
    @endcan
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    @foreach([
        ['label'=>'Total Events','value'=>$stats['total'],'color'=>'#4f46e5'],
        ['label'=>'Upcoming','value'=>$stats['upcoming'],'color'=>'#d97706'],
        ['label'=>'Completed','value'=>$stats['completed'],'color'=>'#059669'],
    ] as $stat)
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.25rem;">{{ $stat['label'] }}</div>
        <div style="font-size:1.5rem;font-weight:700;color:{{ $stat['color'] }};">{{ $stat['value'] }}</div>
    </div>
    @endforeach
</div>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Event</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Registrations</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($events as $event)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $event->title }}</div>
                    @if($event->venue)<div style="font-size:.75rem;color:#64748b;">{{ $event->venue }}</div>@endif
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="indigo">{{ ucfirst($event->event_type) }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $event->event_date->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;text-align:center;font-weight:600;color:#4f46e5;">{{ $event->registrations_count }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ match($event->status) { 'upcoming' => 'yellow', 'ongoing' => 'blue', 'completed' => 'green', default => 'red' } }}">
                        {{ ucfirst($event->status) }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('alumni.events.show', $event)" color="secondary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('alumni.events.registrations', $event)" color="secondary" size="sm">RSVPs</x-core-package::btn>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No events found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $events->links() }}</div>

@endsection
