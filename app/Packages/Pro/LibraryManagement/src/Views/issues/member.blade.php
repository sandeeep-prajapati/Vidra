@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.members.index') }}" style="color:#64748b;text-decoration:none;">Members</a>
    <span style="margin:0 .375rem;">›</span>
    <a href="{{ route('library.members.show', $member) }}" style="color:#64748b;text-decoration:none;">{{ $member->name }}</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Issue History</span>
</nav>
@endsection

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Issue History — {{ $member->name }}</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $member->membership_number }}</p>
</div>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Book</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Issued</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Due</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Returned</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($issues as $issue)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $issue->book->title ?? 'N/A' }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $issue->book->author ?? '' }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $issue->issue_date ? \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') : '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $issue->due_date ? \Carbon\Carbon::parse($issue->due_date)->format('d M Y') : '—' }}</td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $issue->return_date ? \Carbon\Carbon::parse($issue->return_date)->format('d M Y') : '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    <x-core-package::badge color="{{ $issue->status === 'returned' ? 'green' : 'yellow' }}">{{ $issue->status }}</x-core-package::badge>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;font-size:.875rem;">No issue history for this member.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

@endsection
