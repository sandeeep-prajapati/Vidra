@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('day.index') }}" style="color:#64748b;text-decoration:none;">Days</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $day->day_name }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $day->day_name }}</h1>
    </div>
    <div style="display:flex;gap:.75rem;">
        <x-core-package::btn :href="route('day.edit', $day)" color="secondary">Edit</x-core-package::btn>
        <form method="POST" action="{{ route('day.destroy', $day) }}" onsubmit="return confirm('Delete this day?')">
            @csrf @method('DELETE')
            <x-core-package::btn type="submit" color="danger">Delete</x-core-package::btn>
        </form>
    </div>
</div>

<x-core-package::card title="Day Information" style="max-width:400px;">
    <dl style="display:grid;gap:.875rem;">
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Day ID</dt>
            <dd style="font-size:.9375rem;color:#64748b;margin:.25rem 0 0;">{{ $day->day_id }}</dd>
        </div>
        <div>
            <dt style="font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">Day Name</dt>
            <dd style="font-size:.9375rem;font-weight:600;color:#1e293b;margin:.25rem 0 0;">{{ $day->day_name }}</dd>
        </div>
    </dl>
</x-core-package::card>

@endsection
