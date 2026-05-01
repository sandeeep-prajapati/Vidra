@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('messageRecipient.index') }}" style="color:#64748b;text-decoration:none;">Recipients</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Recipient #{{ $messageRecipient->recipient_id }}</span>
</nav>
@endsection

@section('content')

@php $statusColors = ['Pending'=>'gray','Sent'=>'blue','Failed'=>'red','Read'=>'green']; @endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Recipient #{{ $messageRecipient->recipient_id }}</h1>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('messageRecipient.edit', $messageRecipient)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('messageRecipient.destroy', $messageRecipient) }}" onsubmit="return confirm('Remove recipient?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="max-width:600px;">
    <x-core-package::card title="Delivery Details">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Recipient</p>
                <p style="font-size:.875rem;color:#1e293b;margin:0;font-weight:600;">{{ $messageRecipient->user?->name ?? '#'.$messageRecipient->user_id }}</p>
                <p style="font-size:.75rem;color:#64748b;margin:.125rem 0 0;">{{ $messageRecipient->user?->email }}</p>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Status</p>
                <x-core-package::badge color="{{ $statusColors[$messageRecipient->status] ?? 'gray' }}">{{ $messageRecipient->status }}</x-core-package::badge>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Message</p>
                <a href="{{ route('message.show', $messageRecipient->message_id) }}" style="color:#4f46e5;font-size:.875rem;">
                    #{{ $messageRecipient->message_id }} — {{ Str::limit($messageRecipient->message?->title ?: $messageRecipient->message?->content, 50) }}
                </a>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Delivered At</p>
                <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $messageRecipient->delivered_at?->format('d M Y, H:i') ?? '—' }}</p>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Read At</p>
                <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $messageRecipient->read_at?->format('d M Y, H:i') ?? '—' }}</p>
            </div>
        </div>
    </x-core-package::card>
</div>

@endsection
