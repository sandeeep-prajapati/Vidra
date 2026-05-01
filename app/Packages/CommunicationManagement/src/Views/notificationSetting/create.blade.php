@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('notificationSetting.index') }}" style="color:#64748b;text-decoration:none;">Notification Preferences</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Add Preferences</span>
</nav>
@endsection

@section('content')

<div style="max-width:560px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add Notification Preferences</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Set communication channel preferences for a user</p>
    </div>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <x-core-package::card>
        <form method="POST" action="{{ route('notificationSetting.store') }}">
            @csrf

            <div style="margin-bottom:1.5rem;">
                <x-core-package::form.select name="user_id" label="User" required>
                    <option value="">Select user…</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </x-core-package::form.select>
                @if($users->isEmpty())
                <p style="font-size:.75rem;color:#f59e0b;margin:.5rem 0 0;">All users already have preferences configured.</p>
                @endif
            </div>

            <p style="font-size:.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .75rem;">Notification Channels</p>

            @php
                $prefs = [
                    'allow_sms'           => 'SMS Notifications',
                    'allow_email'         => 'Email Notifications',
                    'allow_app'           => 'App Push Notifications',
                    'allow_announcements' => 'School Announcements',
                    'allow_circulars'     => 'Circulars',
                ];
            @endphp

            <div style="display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.5rem;">
                @foreach($prefs as $name => $label)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.625rem .875rem;background:#f8fafc;border-radius:.5rem;border:1px solid #e2e8f0;">
                    <label for="{{ $name }}" style="font-size:.875rem;color:#374151;cursor:pointer;">{{ $label }}</label>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <input type="hidden" name="{{ $name }}" value="0">
                        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1"
                            {{ old($name, '1') ? 'checked' : '' }}
                            style="width:1rem;height:1rem;accent-color:#4f46e5;cursor:pointer;">
                    </div>
                </div>
                @endforeach
            </div>

            <div style="display:flex;gap:.75rem;">
                <x-core-package::btn type="submit" color="primary">Save Preferences</x-core-package::btn>
                <x-core-package::btn :href="route('notificationSetting.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
