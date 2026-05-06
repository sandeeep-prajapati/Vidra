@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Alumni / Directory</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Alumni Directory</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Search and connect with graduates</p>
    </div>
    @can('create_alumni-management_item')
    <x-core-package::btn :href="route('alumni.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Alumni
    </x-core-package::btn>
    @endcan
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    @foreach([
        ['label'=>'Total Alumni','value'=>$stats['total'],'color'=>'#4f46e5'],
        ['label'=>'Active','value'=>$stats['active'],'color'=>'#059669'],
        ['label'=>'Verified','value'=>$stats['verified'],'color'=>'#0891b2'],
        ['label'=>'Class of '.date('Y'),'value'=>$stats['this_year'],'color'=>'#d97706'],
    ] as $stat)
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.25rem;">{{ $stat['label'] }}</div>
        <div style="font-size:1.5rem;font-weight:700;color:{{ $stat['color'] }};">{{ $stat['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('alumni.directory') }}" style="display:flex;gap:.75rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:2;min-width:160px;">
            <x-core-package::form.input name="search" label="Search" type="text"
                :value="request('search')" placeholder="Name or email..." />
        </div>
        <div style="flex:1;min-width:120px;">
            <x-core-package::form.select name="year" label="Batch Year">
                <option value="">All Years</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </x-core-package::form.select>
        </div>
        <div style="flex:1;min-width:120px;">
            <x-core-package::form.input name="city" label="City" type="text"
                :value="request('city')" placeholder="e.g. Mumbai" />
        </div>
        <div style="flex:1;min-width:120px;">
            <x-core-package::form.input name="industry" label="Industry" type="text"
                :value="request('industry')" placeholder="e.g. Technology" />
        </div>
        <div style="padding-top:1.375rem;">
            <x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn>
        </div>
        @if(request()->hasAny(['search','year','city','industry']))
        <div style="padding-top:1.375rem;">
            <x-core-package::btn :href="route('alumni.directory')" color="secondary">Clear</x-core-package::btn>
        </div>
        @endif
    </form>
</x-core-package::card>

{{-- Grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
@forelse($alumni as $person)
<div style="background:#fff;border-radius:.75rem;border:1px solid #e2e8f0;padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;">
    <div style="display:flex;align-items:center;gap:.875rem;">
        <div style="width:3rem;height:3rem;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="color:#fff;font-weight:700;font-size:1.125rem;">{{ strtoupper(substr($person->full_name,0,1)) }}</span>
        </div>
        <div>
            <div style="font-weight:600;color:#1e293b;font-size:.9375rem;">{{ $person->full_name }}</div>
            <div style="font-size:.75rem;color:#64748b;">Class of {{ $person->graduation_year }}</div>
        </div>
    </div>
    @if($person->employment->first())
    <div style="font-size:.8125rem;color:#475569;">
        <span style="font-weight:500;">{{ $person->employment->first()->designation }}</span>
        <span style="color:#94a3b8;"> at </span>
        {{ $person->employment->first()->company_name }}
    </div>
    @endif
    @if($person->current_city)
    <div style="font-size:.75rem;color:#94a3b8;">
        <svg style="width:.75rem;height:.75rem;display:inline;vertical-align:middle;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
        {{ $person->current_city }}@if($person->current_country), {{ $person->current_country }}@endif
    </div>
    @endif
    <div style="display:flex;gap:.5rem;margin-top:auto;">
        <x-core-package::btn :href="route('alumni.show', $person)" color="secondary" size="sm">View Profile</x-core-package::btn>
        @if($person->linkedin_url)
        <a href="{{ $person->linkedin_url }}" target="_blank"
            style="display:inline-flex;align-items:center;gap:.375rem;padding:.25rem .75rem;background:#0a66c2;color:#fff;border-radius:.375rem;font-size:.75rem;text-decoration:none;">
            LinkedIn
        </a>
        @endif
    </div>
</div>
@empty
<div style="grid-column:1/-1;padding:3rem;text-align:center;color:#94a3b8;font-size:.875rem;">
    No alumni found matching your filters.
    @can('create_alumni-management_item')
    <a href="{{ route('alumni.create') }}" style="color:#4f46e5;">Add the first alumni</a>
    @endcan
</div>
@endforelse
</div>

<div style="margin-top:1.5rem;">{{ $alumni->withQueryString()->links() }}</div>

@endsection
