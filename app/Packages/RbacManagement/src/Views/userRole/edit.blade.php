@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('user-roles.index') }}" style="color:#64748b;text-decoration:none;">User Role Assignment</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $user->name }}</span>
</nav>
@endsection

@section('content')

<div style="max-width:680px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Assign Roles — {{ $user->name }}</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $user->email }}</p>
</div>

@if($errors->any())
<x-core-package::alert type="error" style="margin-bottom:1rem;">
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</x-core-package::alert>
@endif

<x-core-package::card>
    <form method="POST" action="{{ route('user-roles.update', $user) }}">
        @csrf @method('PUT')
        <p style="font-size:.8125rem;color:#64748b;margin:0 0 1.25rem;">
            Select roles to assign to this user. All permissions from the selected roles will apply.
        </p>
        <div style="display:flex;flex-direction:column;gap:.625rem;">
            @forelse($roles as $role)
            @php $checked = $user->roles->contains('id', $role->id); @endphp
            <label style="display:flex;align-items:flex-start;gap:.75rem;padding:.875rem 1rem;border-radius:.5rem;border:1px solid {{ $checked ? '#a5b4fc' : '#e2e8f0' }};background:{{ $checked ? '#eef2ff' : '#fff' }};cursor:pointer;">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                    {{ $checked ? 'checked' : '' }}
                    style="width:1.125rem;height:1.125rem;margin-top:.1rem;accent-color:#4f46e5;flex-shrink:0;">
                <div>
                    <div style="font-weight:600;font-size:.875rem;color:#1e293b;">{{ $role->name }}</div>
                    @if($role->description)
                    <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;">{{ $role->description }}</div>
                    @endif
                </div>
            </label>
            @empty
            <p style="color:#94a3b8;font-size:.875rem;text-align:center;padding:1rem 0;">
                No roles defined. <a href="{{ route('roles.create') }}" style="color:#4f46e5;">Create a role</a>
            </p>
            @endforelse
        </div>

        <div style="padding-top:1.25rem;border-top:1px solid #e2e8f0;margin-top:1rem;display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">Save Roles</x-core-package::btn>
            <x-core-package::btn :href="route('user-roles.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

</div>

<script>
document.querySelectorAll('[name="roles[]"]').forEach(function (cb) {
    cb.addEventListener('change', function () {
        var label = this.closest('label');
        label.style.borderColor = this.checked ? '#a5b4fc' : '#e2e8f0';
        label.style.background  = this.checked ? '#eef2ff' : '#fff';
    });
});
</script>

@endsection
