@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Messages</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Messages</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Manage SMS, Email, and App Notifications</p>
    </div>
    <x-core-package::btn :href="route('message.create')" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Message
    </x-core-package::btn>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('message.index') }}"
          style="display:grid;grid-template-columns:180px 160px 160px auto auto;gap:.75rem;align-items:end;">
        <x-core-package::form.select name="message_type" label="Type">
            <option value="">All Types</option>
            @foreach(['SMS','Email','App Notification','Circular','Announcement'] as $t)
            <option value="{{ $t }}" @selected(request('message_type') === $t)>{{ $t }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="priority" label="Priority">
            <option value="">All Priorities</option>
            @foreach(['Low','Normal','High','Urgent'] as $p)
            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
            @endforeach
        </x-core-package::form.select>
        <x-core-package::form.select name="is_sent" label="Status">
            <option value="">All</option>
            <option value="1" @selected(request('is_sent') === '1')>Sent</option>
            <option value="0" @selected(request('is_sent') === '0')>Pending</option>
        </x-core-package::form.select>
        <div style="padding-top:1.375rem;"><x-core-package::btn type="submit" color="primary">Filter</x-core-package::btn></div>
        <div style="padding-top:1.375rem;"><x-core-package::btn :href="route('message.index')" color="secondary">Reset</x-core-package::btn></div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">#</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Title / Content</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Type</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Priority</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Sender</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Sent</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Created</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
            @php
                $typeColors = ['SMS'=>'blue','Email'=>'indigo','App Notification'=>'purple','Circular'=>'orange','Announcement'=>'green'];
                $prioColors = ['Low'=>'gray','Normal'=>'blue','High'=>'yellow','Urgent'=>'red'];
            @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;color:#94a3b8;font-size:.75rem;">{{ $item->message_id }}</td>
                <td style="padding:.75rem 1rem;max-width:220px;">
                    @if($item->title)
                    <p style="font-weight:600;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</p>
                    @endif
                    <p style="color:#64748b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.75rem;">{{ Str::limit($item->content, 60) }}</p>
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $typeColors[$item->message_type] ?? 'gray' }}">{{ $item->message_type }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $prioColors[$item->priority] ?? 'gray' }}">{{ $item->priority }}</x-core-package::badge>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $item->sender?->name ?? '—' }}</td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    @if($item->is_sent)
                        <x-core-package::badge color="green">Sent</x-core-package::badge>
                    @else
                        <x-core-package::badge color="gray">Pending</x-core-package::badge>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;white-space:nowrap;">{{ $item->created_at->format('d M Y') }}</td>
                <td style="padding:.75rem 1rem;">
                    <div style="display:flex;gap:.5rem;">
                        <x-core-package::btn :href="route('message.show', $item)" color="primary" size="sm">View</x-core-package::btn>
                        <x-core-package::btn :href="route('message.edit', $item)" color="secondary" size="sm">Edit</x-core-package::btn>
                        <form method="POST" action="{{ route('message.destroy', $item) }}" onsubmit="return confirm('Delete this message?')">
                            @csrf @method('DELETE')
                            <x-core-package::btn type="submit" color="danger" size="sm">Delete</x-core-package::btn>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No messages found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $items->links() }}</div>

@endsection
