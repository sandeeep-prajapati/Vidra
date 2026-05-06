@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.members.index') }}" style="color:#64748b;text-decoration:none;">Members</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $member->name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $member->name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $member->membership_number }} · {{ ucfirst($member->member_type) }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        @can('edit_library-management_item')
        <x-core-package::btn :href="route('library.members.edit', $member)" color="secondary">Edit</x-core-package::btn>
        @endcan
        @can('delete_library-management_item')
        <form method="POST" action="{{ route('library.members.destroy', $member) }}" onsubmit="return confirm('Delete this member?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
        @endcan
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.25rem;">

    {{-- Profile card --}}
    <x-core-package::card>
        <div style="display:flex;flex-direction:column;gap:.75rem;font-size:.8125rem;">
            @foreach([['Email',$member->email??'—'],['Phone',$member->phone??'—'],['Max Books',$member->max_books_allowed],['Member ID',$member->member_id]] as [$label,$value])
            <div>
                <div style="color:#94a3b8;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">{{ $label }}</div>
                <div style="color:#1e293b;font-weight:500;">{{ $value }}</div>
            </div>
            @endforeach
            <div>
                <div style="color:#94a3b8;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Status</div>
                @php $colors = ['active'=>'green','inactive'=>'red','suspended'=>'yellow']; @endphp
                <x-core-package::badge color="{{ $colors[$member->status] ?? 'indigo' }}">{{ $member->status }}</x-core-package::badge>
            </div>
        </div>
    </x-core-package::card>

    {{-- Issue history --}}
    <div>
        <x-core-package::card>
            <div style="font-size:.9rem;font-weight:600;color:#1e293b;margin-bottom:1rem;">Issue History</div>
            @forelse($member->issues ?? [] as $issue)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:.625rem 0;border-bottom:1px solid #f1f5f9;font-size:.8125rem;">
                <div>
                    <div style="font-weight:500;color:#1e293b;">{{ $issue->book->title ?? 'N/A' }}</div>
                    <div style="color:#64748b;font-size:.75rem;">Issued: {{ $issue->issue_date ? \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') : '—' }}</div>
                </div>
                <x-core-package::badge color="{{ $issue->status === 'returned' ? 'green' : 'yellow' }}">{{ $issue->status }}</x-core-package::badge>
            </div>
            @empty
            <p style="color:#94a3b8;font-size:.875rem;margin:0;">No books issued yet.</p>
            @endforelse
        </x-core-package::card>
    </div>
</div>

@endsection
