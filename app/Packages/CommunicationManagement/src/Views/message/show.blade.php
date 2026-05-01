@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('message.index') }}" style="color:#64748b;text-decoration:none;">Messages</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Message #{{ $message->message_id }}</span>
</nav>
@endsection

@section('content')

@php
    $typeColors = ['SMS'=>'blue','Email'=>'indigo','App Notification'=>'purple','Circular'=>'orange','Announcement'=>'green'];
    $prioColors = ['Low'=>'gray','Normal'=>'blue','High'=>'yellow','Urgent'=>'red'];
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">
        {{ $message->title ?: 'Message #'.$message->message_id }}
    </h1>
    <div style="display:flex;gap:.5rem;">
        <x-core-package::btn :href="route('message.edit', $message)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('message.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;">

    <div>
        <x-core-package::card title="Message Details" style="margin-bottom:1.25rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Type</p>
                    <x-core-package::badge color="{{ $typeColors[$message->message_type] ?? 'gray' }}">{{ $message->message_type }}</x-core-package::badge>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Priority</p>
                    <x-core-package::badge color="{{ $prioColors[$message->priority] ?? 'gray' }}">{{ $message->priority }}</x-core-package::badge>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Status</p>
                    @if($message->is_sent)
                        <x-core-package::badge color="green">Sent</x-core-package::badge>
                    @else
                        <x-core-package::badge color="gray">Pending</x-core-package::badge>
                    @endif
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Sender</p>
                    <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $message->sender?->name ?? '— System —' }}</p>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Created</p>
                    <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $message->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($message->scheduled_at)
                <div>
                    <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .25rem;">Scheduled At</p>
                    <p style="font-size:.875rem;color:#1e293b;margin:0;">{{ $message->scheduled_at->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin:0 0 .5rem;">Content</p>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:.5rem;padding:1rem;font-size:.875rem;color:#374151;white-space:pre-wrap;line-height:1.6;">{{ $message->content }}</div>
            </div>
        </x-core-package::card>

        <x-core-package::card title="Recipients ({{ $message->recipients->count() }})" :noPadding="true">
            <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Recipient</th>
                        <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Status</th>
                        <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Delivered</th>
                        <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Read</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($message->recipients as $r)
                @php $statusColors = ['Pending'=>'gray','Sent'=>'blue','Failed'=>'red','Read'=>'green']; @endphp
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:.625rem 1rem;color:#1e293b;">{{ $r->user?->name ?? '#'.$r->user_id }}</td>
                    <td style="padding:.625rem 1rem;"><x-core-package::badge color="{{ $statusColors[$r->status] ?? 'gray' }}">{{ $r->status }}</x-core-package::badge></td>
                    <td style="padding:.625rem 1rem;color:#64748b;">{{ $r->delivered_at?->format('d M Y H:i') ?? '—' }}</td>
                    <td style="padding:.625rem 1rem;color:#64748b;">{{ $r->read_at?->format('d M Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:1.5rem;text-align:center;color:#94a3b8;">No recipients yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </x-core-package::card>
    </div>

    <div>
        <x-core-package::card title="Quick Actions">
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                <x-core-package::btn :href="route('messageRecipient.create').'?message_id='.$message->message_id" color="primary" size="sm">Add Recipient</x-core-package::btn>
                <x-core-package::btn :href="route('message.index')" color="secondary" size="sm">Back to List</x-core-package::btn>
            </div>
        </x-core-package::card>
    </div>
</div>

@endsection
