@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Alumni / Mentorship</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Mentorship Pairings</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Alumni mentoring current students</p>
    </div>
    @can('manage_alumni_mentorship')
    <x-core-package::btn :href="route('alumni.mentorship.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Pairing
    </x-core-package::btn>
    @endcan
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    @foreach([
        ['label'=>'Active','value'=>$stats['active'],'color'=>'#059669'],
        ['label'=>'Completed','value'=>$stats['completed'],'color'=>'#4f46e5'],
        ['label'=>'Total','value'=>$stats['total'],'color'=>'#64748b'],
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
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Mentor (Alumni)</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Mentee</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Area</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Duration</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($mentorships as $m)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $m->mentor->full_name }}</div>
                    <div style="font-size:.75rem;color:#64748b;">Class of {{ $m->mentor->graduation_year }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#1e293b;">{{ $m->mentee_name }}</td>
                <td style="padding:.75rem 1rem;"><x-core-package::badge color="indigo">{{ $m->area_of_mentorship }}</x-core-package::badge></td>
                <td style="padding:.75rem 1rem;color:#64748b;font-size:.75rem;">
                    {{ $m->start_date->format('M Y') }} – {{ $m->end_date ? $m->end_date->format('M Y') : 'Ongoing' }}
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ match($m->status) { 'active' => 'green', 'completed' => 'blue', default => 'red' } }}">
                        {{ ucfirst($m->status) }}
                    </x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('alumni.mentorship.show', $m)" color="secondary" size="sm">View</x-core-package::btn>
                        @can('manage_alumni_mentorship')
                        @if($m->status === 'active')
                        <form method="POST" action="{{ route('alumni.mentorship.complete', $m) }}">
                            @csrf
                            <x-core-package::btn type="submit" color="primary" size="sm">Complete</x-core-package::btn>
                        </form>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No mentorships yet.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $mentorships->links() }}</div>

@endsection
