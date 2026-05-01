@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Message Recipients</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Message Recipients</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Track delivery and read status per recipient</p>
    </div>
    <x-core-package::btn :href="route('messageRecipient.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Recipient
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('messageRecipient.index') }}"
          style="display:grid;grid-template-columns:220px 160px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="message_id" label="Message">
            <option value="">All Messages</option>
            @foreach($messages as $msg)
            <option value="{{ $msg->message_id }}" @selected(request('message_id') == $msg->message_id)>
                #{{ $msg->message_id }} – {{ Str::limit($msg->title ?: $msg->content, 40) }}
            </option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="status" label="Status">
            <option value="">All</option>
            @foreach(['Pending','Sent','Failed','Read'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('messageRecipient.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Message</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Recipient</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Delivered</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Read At</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            @php $statusColors = ['Pending'=>'gray','Sent'=>'blue','Failed'=>'red','Read'=>'green']; @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#94a3b8;font-size:.75rem;">{{ $item->recipient_id }}</td>
                <td style="padding:.75rem 1rem;color:#1e293b;max-width:200px;">
                    <a href="{{ route('message.show', $item->message_id) }}" style="color:#4f46e5;text-decoration:none;">
                        #{{ $item->message_id }} — {{ Str::limit($item->message?->title ?: $item->message?->content, 35) }}
                    </a>
                </td>
                <td style="padding:.75rem 1rem;color:#1e293b;">{{ $item->user?->name ?? '#'.$item->user_id }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $statusColors[$item->status] ?? 'gray' }}">{{ $item->status }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;white-space:nowrap;">{{ $item->delivered_at?->format('d M Y H:i') ?? '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;white-space:nowrap;">{{ $item->read_at?->format('d M Y H:i') ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('messageRecipient.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('messageRecipient.destroy', $item) }}" onsubmit="return confirm('Remove recipient?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No recipients found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
