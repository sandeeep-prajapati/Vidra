@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('message.index') }}" style="color:#64748b;text-decoration:none;">Messages</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Message #{{ $message->message_id }}</span>
</nav>
@endsection

@section('content')

<div style="max-width:720px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Message</h1>
    </div>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <x-core-package::card>
        <form method="POST" action="{{ route('message.update', $message) }}">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <x-core-package::form.select name="message_type" label="Message Type" required>
                    <option value="">Select type…</option>
                    @foreach(['SMS','Email','App Notification','Circular','Announcement'] as $t)
                    <option value="{{ $t }}" @selected(old('message_type', $message->message_type) === $t)>{{ $t }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.select name="priority" label="Priority" required>
                    @foreach(['Low','Normal','High','Urgent'] as $p)
                    <option value="{{ $p }}" @selected(old('priority', $message->priority) === $p)>{{ $p }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.input name="title" label="Title / Subject" type="text" hint="Optional for SMS"
                    value="{{ old('title', $message->title) }}" />
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.textarea name="content" label="Message Content" required rows="5">{{ old('content', $message->content) }}</x-core-package::form.textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <x-core-package::form.select name="created_by" label="Sender">
                    <option value="">— System —</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('created_by', $message->created_by) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input name="scheduled_at" label="Schedule At" type="datetime-local"
                    value="{{ old('scheduled_at', $message->scheduled_at?->format('Y-m-d\TH:i')) }}" />
            </div>

            <div style="margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem;">
                <input type="hidden" name="is_sent" value="0">
                <input type="checkbox" id="is_sent" name="is_sent" value="1"
                    {{ old('is_sent', $message->is_sent) ? 'checked' : '' }}
                    style="width:1rem;height:1rem;accent-color:#4f46e5;cursor:pointer;">
                <label for="is_sent" style="font-size:.875rem;color:#374151;cursor:pointer;">Mark as sent</label>
            </div>

            <div style="display:flex;gap:.75rem;">
                <x-core-package::btn type="submit" color="primary">Update Message</x-core-package::btn>
                <x-core-package::btn :href="route('message.show', $message)" color="secondary">Cancel</x-core-package::btn>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
