@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.issues.index') }}" style="color:#64748b;text-decoration:none;">Issues</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Overdue</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Overdue Books</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Books past their due date</p>
    </div>
    <x-core-package::btn :href="route('library.issues.index')" color="secondary">All Issues</x-core-package::btn>
</div>

@if(session('success'))
<x-core-package::alert type="success" style="margin-bottom:1rem;">{{ session('success') }}</x-core-package::alert>
@endif

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#fff7ed;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #fed7aa;">Book</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #fed7aa;">Member</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #fed7aa;">Due Date</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #fed7aa;">Days Overdue</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #fed7aa;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($issues as $issue)
            <tr style="border-bottom:1px solid #f1f5f9;background:#fffbf5;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $issue->book->title ?? 'N/A' }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $issue->book->author ?? '' }}</div>
                </td>
                <td style="padding:.75rem 1rem;">
                    <div style="font-weight:500;color:#1e293b;">{{ $issue->member->name ?? 'N/A' }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $issue->member->email ?? '' }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#dc2626;font-weight:600;">
                    {{ $issue->due_date ? \Carbon\Carbon::parse($issue->due_date)->format('d M Y') : '—' }}
                </td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    @if($issue->due_date)
                    <x-core-package::badge color="red">
                        {{ \Carbon\Carbon::parse($issue->due_date)->diffInDays(now()) }} days
                    </x-core-package::badge>
                    @endif
                </td>
                <td style="padding:.75rem 1rem;">
                    @can('edit_library-management_item')
                    <form method="POST" action="{{ route('library.issues.return', $issue) }}" onsubmit="return confirm('Mark as returned?')">
                        @csrf
                        <x-core-package::btn type="submit" color="primary" size="sm">Return</x-core-package::btn>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No overdue books.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

@endsection
