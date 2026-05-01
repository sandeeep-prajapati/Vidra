@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('feeCategory.index') }}" style="color:#64748b;text-decoration:none;">Fee Categories</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $feeCategory->category_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $feeCategory->category_name }}</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Fee Category Details</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('feeCategory.edit', $feeCategory)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('feeCategory.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:1.25rem;">

<x-core-package::card title="Category Info">
    <dl style="display:flex;flex-direction:column;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Category Name</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $feeCategory->category_name }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Description</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feeCategory->description ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Created</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feeCategory->created_at?->format('d M Y') }}</dd>
        </div>
    </dl>
</x-core-package::card>

<x-core-package::card title="Linked Fee Structures" :noPadding="true">
    <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
        <thead>
            <tr style="background:#f8fafc;">
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Class</th>
                <th style="padding:.5rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Academic Year</th>
                <th style="padding:.5rem 1rem;text-align:right;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Amount</th>
            </tr>
        </thead>
        <tbody>
        @forelse($feeCategory->feeStructures as $fs)
        <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:.625rem 1rem;color:#374151;">{{ $fs->schoolClass?->class_name ?? '—' }}</td>
            <td style="padding:.625rem 1rem;color:#64748b;">{{ $fs->academicYear?->year_range ?? '—' }}</td>
            <td style="padding:.625rem 1rem;text-align:right;font-weight:600;color:#1e293b;">₹{{ number_format($fs->amount, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="padding:1.5rem;text-align:center;color:#94a3b8;">No fee structures linked.</td></tr>
        @endforelse
        </tbody>
    </table>
</x-core-package::card>

</div>

@endsection
