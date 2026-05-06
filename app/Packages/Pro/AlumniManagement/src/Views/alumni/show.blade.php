@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.directory') }}" style="color:#64748b;text-decoration:none;">Alumni</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $alumni->full_name }}</span>
</nav>
@endsection

@section('content')

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

{{-- Profile header --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <div style="display:flex;align-items:flex-start;gap:1.5rem;flex-wrap:wrap;">
        <div style="width:4.5rem;height:4.5rem;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="color:#fff;font-weight:700;font-size:1.5rem;">{{ strtoupper(substr($alumni->full_name,0,1)) }}</span>
        </div>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                <h1 style="font-size:1.25rem;font-weight:700;color:#1e293b;margin:0;">{{ $alumni->full_name }}</h1>
                <x-core-package::badge color="{{ $alumni->is_verified ? 'green' : 'gray' }}">
                    {{ $alumni->is_verified ? 'Verified' : 'Unverified' }}
                </x-core-package::badge>
                <x-core-package::badge color="{{ $alumni->status === 'active' ? 'indigo' : 'red' }}">
                    {{ ucfirst($alumni->status) }}
                </x-core-package::badge>
            </div>
            <div style="font-size:.8125rem;color:#64748b;margin-top:.375rem;">Class of {{ $alumni->graduation_year }}@if($alumni->graduation_class) — {{ $alumni->graduation_class }}@endif</div>
            <div style="font-size:.8125rem;color:#64748b;margin-top:.125rem;">{{ $alumni->email }}@if($alumni->phone) · {{ $alumni->phone }}@endif</div>
            @if($alumni->current_city)
            <div style="font-size:.8125rem;color:#64748b;margin-top:.125rem;">
                {{ $alumni->current_city }}@if($alumni->current_country), {{ $alumni->current_country }}@endif
            </div>
            @endif
            <div style="display:flex;gap:.5rem;margin-top:.875rem;">
                @if($alumni->linkedin_url)
                <a href="{{ $alumni->linkedin_url }}" target="_blank"
                    style="display:inline-block;padding:.25rem .75rem;background:#0a66c2;color:#fff;border-radius:.375rem;font-size:.75rem;text-decoration:none;">LinkedIn</a>
                @endif
                @if($alumni->website_url)
                <a href="{{ $alumni->website_url }}" target="_blank"
                    style="display:inline-block;padding:.25rem .75rem;background:#475569;color:#fff;border-radius:.375rem;font-size:.75rem;text-decoration:none;">Website</a>
                @endif
                @can('edit_alumni-management_item')
                <x-core-package::btn :href="route('alumni.edit', $alumni)" color="secondary" size="sm">Edit Profile</x-core-package::btn>
                @endcan
            </div>
        </div>
    </div>
    @if($alumni->bio)
    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;font-size:.875rem;color:#475569;line-height:1.6;">{{ $alumni->bio }}</div>
    @endif
</x-core-package::card>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

{{-- Employment --}}
<x-core-package::card>
    <h2 style="font-size:.9375rem;font-weight:700;color:#1e293b;margin:0 0 1rem;">Employment</h2>
    @forelse($alumni->employment->sortByDesc('is_current') as $job)
    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:.5rem;">
            <span style="font-weight:600;color:#1e293b;font-size:.875rem;">{{ $job->designation }}</span>
            @if($job->is_current)<x-core-package::badge color="green">Current</x-core-package::badge>@endif
        </div>
        <div style="font-size:.8125rem;color:#64748b;">{{ $job->company_name }}@if($job->industry) · {{ $job->industry }}@endif</div>
        <div style="font-size:.75rem;color:#94a3b8;">{{ $job->start_year }} – {{ $job->is_current ? 'Present' : $job->end_year }}</div>
    </div>
    @empty
    <p style="color:#94a3b8;font-size:.875rem;">No employment records.</p>
    @endforelse
</x-core-package::card>

{{-- Education --}}
<x-core-package::card>
    <h2 style="font-size:.9375rem;font-weight:700;color:#1e293b;margin:0 0 1rem;">Higher Education</h2>
    @forelse($alumni->education->sortByDesc('is_current') as $edu)
    <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:.5rem;">
            <span style="font-weight:600;color:#1e293b;font-size:.875rem;">{{ $edu->degree }}</span>
            @if($edu->is_current)<x-core-package::badge color="blue">Ongoing</x-core-package::badge>@endif
        </div>
        <div style="font-size:.8125rem;color:#64748b;">{{ $edu->institution_name }}@if($edu->field_of_study) · {{ $edu->field_of_study }}@endif</div>
        <div style="font-size:.75rem;color:#94a3b8;">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</div>
    </div>
    @empty
    <p style="color:#94a3b8;font-size:.875rem;">No education records.</p>
    @endforelse
</x-core-package::card>

</div>

@endsection
