@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('feeStructure.index') }}" style="color:#64748b;text-decoration:none;">Fee Structures</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Details</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Fee Structure Details</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $feeStructure->schoolClass?->class_name }} — {{ $feeStructure->feeCategory?->category_name }}</p>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('feeStructure.edit', $feeStructure)" color="secondary">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('feeStructure.index')" color="ghost">Back</x-core-package::btn>
    </div>
</div>

<x-core-package::card title="Structure Info" style="max-width:36rem;">
    <dl style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Class</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:0;">{{ $feeStructure->schoolClass?->class_name ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Category</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feeStructure->feeCategory?->category_name ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Academic Year</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feeStructure->academicYear?->year_range ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Due Date</dt>
            <dd style="font-size:.875rem;color:#475569;margin:0;">{{ $feeStructure->due_date?->format('d M Y') ?? '—' }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:.2rem;">Amount</dt>
            <dd style="font-size:1.25rem;font-weight:700;color:#0f766e;margin:0;">₹{{ number_format($feeStructure->amount, 2) }}</dd>
        </div>
    </dl>
</x-core-package::card>

@endsection
