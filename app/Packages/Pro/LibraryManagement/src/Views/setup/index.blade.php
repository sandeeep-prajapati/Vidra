@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#374151;font-weight:500;">Library / Setup</span>
</nav>
@endsection

@section('content')

<div style="max-width:720px;margin:0 auto;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Library Setup Wizard</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Run these steps once after installation to get started</p>
</div>

<div id="setup-alerts" style="margin-bottom:1rem;"></div>

{{-- Status cards --}}
<div style="display:grid;gap:1rem;margin-bottom:1.5rem;">
    @foreach([
        ['key'=>'migrations', 'label'=>'Database Migrations', 'desc'=>'Creates library tables in the database'],
        ['key'=>'permissions', 'label'=>'Permissions',        'desc'=>'Creates RBAC permissions for library module'],
        ['key'=>'roles',       'label'=>'Roles',              'desc'=>'Creates librarian role and assigns permissions'],
        ['key'=>'categories',  'label'=>'Sample Categories',  'desc'=>'Seeds default book categories'],
    ] as $step)
    <div style="background:#fff;border-radius:.625rem;border:1px solid #e2e8f0;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:.875rem;">
            @if($status[$step['key']])
            <div style="width:2rem;height:2rem;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1rem;height:1rem;color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            @else
            <div style="width:2rem;height:2rem;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1rem;height:1rem;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            @endif
            <div>
                <div style="font-weight:600;font-size:.875rem;color:#1e293b;">{{ $step['label'] }}</div>
                <div style="font-size:.75rem;color:#64748b;">{{ $step['desc'] }}</div>
            </div>
        </div>
        @if(!$status[$step['key']])
        <button onclick="runSetup('{{ $step['key'] }}')"
            style="padding:.375rem .875rem;background:#4f46e5;color:#fff;border:none;border-radius:.375rem;font-size:.8125rem;cursor:pointer;">
            Run
        </button>
        @else
        <span style="font-size:.75rem;color:#16a34a;font-weight:500;">Done</span>
        @endif
    </div>
    @endforeach
</div>

{{-- Run all --}}
@if(!array_values($status)[0] || !array_values($status)[1] || !array_values($status)[2] || !array_values($status)[3])
<div style="text-align:center;">
    <button onclick="runSetup('all')"
        style="padding:.625rem 2rem;background:#4f46e5;color:#fff;border:none;border-radius:.5rem;font-size:.9rem;font-weight:600;cursor:pointer;">
        Run All Setup Steps
    </button>
</div>
@else
<div style="text-align:center;">
    <p style="color:#059669;font-weight:600;font-size:.9rem;margin-bottom:1rem;">✓ All setup steps completed!</p>
    <a href="{{ route('library.books.index') }}"
       style="display:inline-block;padding:.625rem 2rem;background:#059669;color:#fff;border-radius:.5rem;font-size:.875rem;font-weight:600;text-decoration:none;">
        Go to Library →
    </a>
</div>
@endif

</div>

<script>
function runSetup(command) {
    const alertBox = document.getElementById('setup-alerts');
    alertBox.innerHTML = '<div style="padding:.875rem 1rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:.5rem;color:#1e40af;font-size:.8125rem;">Running {{ $command ?? "setup" }}...</div>';

    fetch('{{ route('library.setup.run') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({ command: command })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alertBox.innerHTML = '<div style="padding:.875rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.5rem;color:#15803d;font-size:.8125rem;">' + data.message + '</div>';
            setTimeout(() => location.reload(), 1200);
        } else {
            alertBox.innerHTML = '<div style="padding:.875rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:.5rem;color:#dc2626;font-size:.8125rem;">' + (data.message || 'Setup failed') + '</div>';
        }
    })
    .catch(() => {
        alertBox.innerHTML = '<div style="padding:.875rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:.5rem;color:#dc2626;font-size:.8125rem;">Network error — please try again.</div>';
    });
}
</script>

@endsection
