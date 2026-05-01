@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('notificationSetting.index') }}" style="color:#64748b;text-decoration:none;">Notification Preferences</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $notificationSetting->user?->name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $notificationSetting->user?->name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $notificationSetting->user?->email }}</p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('notificationSetting.edit', $notificationSetting)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('notificationSetting.destroy', $notificationSetting) }}" onsubmit="return confirm('Delete these preferences?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="max-width:500px;">
    <x-core-package::card title="Notification Channels">
        @php
            $prefs = [
                'allow_sms'           => ['label'=>'SMS Notifications',      'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                'allow_email'         => ['label'=>'Email Notifications',     'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                'allow_app'           => ['label'=>'App Push Notifications',  'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                'allow_announcements' => ['label'=>'School Announcements',    'icon'=>'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                'allow_circulars'     => ['label'=>'Circulars',               'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ];
        @endphp
        <div style="display:flex;flex-direction:column;gap:.75rem;">
            @foreach($prefs as $key => $cfg)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem .875rem;background:#f8fafc;border-radius:.5rem;border:1px solid #e2e8f0;">
                <div style="display:flex;align-items:center;gap:.625rem;">
                    <svg style="width:1rem;height:1rem;color:#64748b;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/>
                    </svg>
                    <span style="font-size:.875rem;color:#374151;">{{ $cfg['label'] }}</span>
                </div>
                @if($notificationSetting->$key)
                    <x-core-package::badge color="green">Enabled</x-core-package::badge>
                @else
                    <x-core-package::badge color="red">Disabled</x-core-package::badge>
                @endif
            </div>
            @endforeach
        </div>
    </x-core-package::card>
</div>

@endsection
