@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.events.index') }}" style="color:#64748b;text-decoration:none;">Events</a>
    <span style="margin:0 .375rem;">›</span>
    <a href="{{ route('alumni.events.show', $event) }}" style="color:#64748b;text-decoration:none;">{{ $event->title }}</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">RSVPs</span>
</nav>
@endsection

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Registrations — {{ $event->title }}</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $registrations->total() }} total registrations</p>
</div>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Alumni</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Batch</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Registered At</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Attended</th>
                </tr>
            </thead>
            <tbody>
            @forelse($registrations as $reg)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $reg->alumni->full_name }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $reg->alumni->email }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $reg->alumni->graduation_year }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $reg->registered_at->format('d M Y, g:i A') }}</td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    <x-core-package::badge color="{{ $reg->attended ? 'green' : 'gray' }}">
                        {{ $reg->attended ? 'Yes' : 'No' }}
                    </x-core-package::badge>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No registrations yet.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $registrations->links() }}</div>

@endsection
