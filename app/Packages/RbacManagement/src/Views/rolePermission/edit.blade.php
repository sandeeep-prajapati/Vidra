@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('role-permissions.index') }}" style="color:#64748b;text-decoration:none;">Role-Permission Matrix</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">{{ $role->name }}</span>
</nav>
@endsection

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Permissions — {{ $role->name }}</h1>
    @if($role->description)
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $role->description }}</p>
    @endif
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

<form method="POST" action="{{ route('role-permissions.update', $role) }}">
    @csrf @method('PUT')

    <x-core-package::card>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <p style="font-size:.8125rem;color:#64748b;margin:0;">
                Select permissions for the <strong>{{ $role->name }}</strong> role.
            </p>
            <button type="button" id="toggle-all" style="font-size:.8125rem;color:#4f46e5;background:none;border:none;cursor:pointer;padding:0;">Select All</button>
        </div>

        @forelse($allPermissions as $module => $perms)
        <div style="margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.625rem;padding-bottom:.375rem;border-bottom:1px solid #e2e8f0;">
                <span style="font-size:.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.06em;">
                    {{ $module ?: 'General' }}
                </span>
                <button type="button" class="toggle-module" style="font-size:.75rem;color:#64748b;background:none;border:none;cursor:pointer;padding:0;">
                    Toggle all
                </button>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(240px, 1fr));gap:.5rem;">
                @foreach($perms as $permission)
                @php $checked = $role->permissions->contains('id', $permission->id); @endphp
                <label style="display:flex;align-items:flex-start;gap:.5rem;font-size:.8125rem;color:#374151;cursor:pointer;padding:.5rem;border-radius:.375rem;border:1px solid {{ $checked ? '#a5b4fc' : '#e2e8f0' }};background:{{ $checked ? '#eef2ff' : '#fff' }};">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        {{ $checked ? 'checked' : '' }}
                        style="width:1rem;height:1rem;margin-top:.1rem;accent-color:#4f46e5;flex-shrink:0;">
                    <div>
                        <div style="font-weight:500;">{{ $permission->name }}</div>
                        @if($permission->description)
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:.125rem;">{{ $permission->description }}</div>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @empty
        <p style="color:#94a3b8;font-size:.875rem;text-align:center;padding:1.5rem 0;">
            No permissions defined yet. <a href="{{ route('permissions.create') }}" style="color:#4f46e5;">Create permissions</a>
        </p>
        @endforelse

        <div style="padding-top:1rem;border-top:1px solid #e2e8f0;display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">Save Permissions</x-core-package::btn>
            <x-core-package::btn :href="route('role-permissions.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </x-core-package::card>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var allChecked = false;
    document.getElementById('toggle-all').addEventListener('click', function () {
        allChecked = !allChecked;
        document.querySelectorAll('[name="permissions[]"]').forEach(function (cb) {
            cb.checked = allChecked;
            updateStyle(cb);
        });
        this.textContent = allChecked ? 'Deselect All' : 'Select All';
    });

    document.querySelectorAll('.toggle-module').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var section = this.closest('div').parentElement;
            var cbs = section.querySelectorAll('[name="permissions[]"]');
            var anyUnchecked = Array.from(cbs).some(function (cb) { return !cb.checked; });
            cbs.forEach(function (cb) { cb.checked = anyUnchecked; updateStyle(cb); });
        });
    });

    document.querySelectorAll('[name="permissions[]"]').forEach(function (cb) {
        cb.addEventListener('change', function () { updateStyle(this); });
    });

    function updateStyle(cb) {
        var label = cb.closest('label');
        if (!label) return;
        label.style.borderColor = cb.checked ? '#a5b4fc' : '#e2e8f0';
        label.style.background  = cb.checked ? '#eef2ff' : '#fff';
    }
});
</script>

@endsection
