@extends('core-package::layouts.app')

@section('title', 'Webhook Logs')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    <x-core-package::page-header title="Webhook Logs" subtitle="History of all outbound webhook calls.">
        <x-slot:actions>
            <x-core-package::btn href="{{ route('webhook.settings.index') }}" color="secondary">
                ← Settings
            </x-core-package::btn>
        </x-slot:actions>
    </x-core-package::page-header>

    <div id="flash-msg" class="hidden mb-4 p-3 rounded-lg text-sm font-medium"></div>

    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Event</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Entity</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Triggered By</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition" id="row-{{ $log->id }}">
                        <td class="px-4 py-3 text-gray-400">{{ $log->id }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-indigo-700">{{ $log->event }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $log->entity_type }}
                            @if($log->entity_id)
                                <span class="text-gray-400">#{{ $log->entity_id }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->triggered_by ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($log->status)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Success</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Failed</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button onclick="deleteLog({{ $log->id }})"
                                class="text-red-500 hover:text-red-700 text-xs font-medium">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">No webhook logs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

<script>
function deleteLog(id) {
    if (! confirm('Delete this log entry?')) return;

    fetch(`/webhook/logs/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('row-' + id)?.remove();
        showFlash(data.message, true);
    })
    .catch(() => showFlash('Failed to delete log.', false));
}

function showFlash(msg, success) {
    const el = document.getElementById('flash-msg');
    el.textContent = msg;
    el.className = 'mb-4 p-3 rounded-lg text-sm font-medium ' +
        (success ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200');
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}
</script>
@endsection
