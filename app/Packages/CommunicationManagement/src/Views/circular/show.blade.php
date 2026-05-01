@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('circular.index') }}" style="color:#64748b;text-decoration:none;">Circulars</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $circular->title }}</span>
</nav>
@endsection

@section('content')

@php $audColors = ['All'=>'indigo','Students'=>'blue','Parents'=>'purple','Teachers'=>'green','Staff'=>'orange']; @endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;max-width:600px;">{{ $circular->title }}</h1>
    <div style="display:flex;gap:.5rem;flex-shrink:0;">
        <x-core-package::btn :href="route('circular.edit', $circular)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('circular.destroy', $circular) }}" onsubmit="return confirm('Delete this circular?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;">
    <div>
        <x-core-package::card title="Circular Content" style="margin-bottom:1.25rem;">
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:.5rem;padding:1rem;font-size:.875rem;color:#374151;white-space:pre-wrap;line-height:1.7;">{{ $circular->content }}</div>
        </x-core-package::card>
    </div>

    <div>
        <x-core-package::card title="Details">
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Target Audience</p>
                    <x-core-package::badge color="{{ $audColors[$circular->target_audience] ?? 'gray' }}">{{ $circular->target_audience }}</x-core-package::badge>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Issue Date</p>
                    <p style="font-size:.875rem;color:#1e293b;margin:0;font-weight:600;">{{ $circular->issued_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Issued By</p>
                    <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $circular->issuer?->name ?? '—' }}</p>
                </div>
                @if($circular->attachment_url)
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Attachment</p>
                    <a href="{{ $circular->attachment_url }}" target="_blank"
                       style="display:inline-flex;align-items:center;gap:.375rem;font-size:.8125rem;color:#4f46e5;text-decoration:none;">
                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </a>
                </div>
                @endif
                <div style="padding-top:.5rem;border-top:1px solid #f1f5f9;">
                    <x-core-package::btn :href="route('circular.index')" color="secondary" size="sm">Back to List</x-core-package::btn>
                </div>
            </div>
        </x-core-package::card>
    </div>
</div>

@endsection
