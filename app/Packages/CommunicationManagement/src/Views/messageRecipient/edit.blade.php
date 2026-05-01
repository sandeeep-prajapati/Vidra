@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('messageRecipient.index') }}" style="color:#64748b;text-decoration:none;">Recipients</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Recipient #{{ $messageRecipient->recipient_id }}</span>
</nav>
@endsection

@section('content')

<div style="max-width:600px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Recipient</h1>
    </div>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <x-core-package::card>
        <form method="POST" action="{{ route('messageRecipient.update', $messageRecipient) }}">
            @csrf @method('PUT')

            <div style="margin-bottom:1rem;">
                <x-core-package::form.select name="message_id" label="Message" required>
                    <option value="">Select message…</option>
                    @foreach($messages as $msg)
                    <option value="{{ $msg->message_id }}" @selected(old('message_id', $messageRecipient->message_id) == $msg->message_id)>
                        #{{ $msg->message_id }} — {{ Str::limit($msg->title ?: $msg->content, 60) }}
                    </option>
                    @endforeach
                </x-core-package::form.select>
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.select name="user_id" label="Recipient User" required>
                    <option value="">Select user…</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $messageRecipient->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </x-core-package::form.select>
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.select name="status" label="Delivery Status" required>
                    @foreach(['Pending','Sent','Failed','Read'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $messageRecipient->status) === $s)>{{ $s }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <x-core-package::form.input name="delivered_at" label="Delivered At" type="datetime-local"
                    value="{{ old('delivered_at', $messageRecipient->delivered_at?->format('Y-m-d\TH:i')) }}" />
                <x-core-package::form.input name="read_at" label="Read At" type="datetime-local"
                    value="{{ old('read_at', $messageRecipient->read_at?->format('Y-m-d\TH:i')) }}" />
            </div>

            <div style="display:flex;gap:.75rem;">
                <x-core-package::btn type="submit" color="primary">Update Recipient</x-core-package::btn>
                <x-core-package::btn :href="route('messageRecipient.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
