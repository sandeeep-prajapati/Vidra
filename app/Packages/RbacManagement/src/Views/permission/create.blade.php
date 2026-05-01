@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('permissions.index') }}" style="color:#64748b;text-decoration:none;">Permissions</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">New Permission</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Create Permission</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Define a new granular permission</p>
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
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.input name="name" label="Permission Name" type="text" required
                :value="old('name')" placeholder="e.g. view students, edit grades, manage fees" />

            <x-core-package::form.select name="module_name" label="Module">
                <option value="">— Select Module —</option>
                @foreach($modules as $mod)
                <option value="{{ $mod }}" @selected(old('module_name') === $mod)>{{ $mod }}</option>
                @endforeach
                <option value="__new__">+ Add new module...</option>
            </x-core-package::form.select>

            <div id="new-module-field" style="display:none;">
                <x-core-package::form.input name="module_name_new" label="New Module Name" type="text"
                    hint="Type a new module name if not listed above" />
            </div>

            <x-core-package::form.textarea name="description" label="Description" rows="3"
                hint="Optional: describe what this permission allows">{{ old('description') }}</x-core-package::form.textarea>

            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Create Permission</x-core-package::btn>
                <x-core-package::btn :href="route('permissions.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var sel = document.querySelector('[name="module_name"]');
    var newField = document.getElementById('new-module-field');
    var newInput = document.querySelector('[name="module_name_new"]');
    if (!sel) return;
    sel.addEventListener('change', function () {
        if (this.value === '__new__') {
            newField.style.display = 'block';
            sel.value = '';
        } else {
            newField.style.display = 'none';
        }
    });
    document.querySelector('form').addEventListener('submit', function () {
        if (newInput && newInput.value.trim()) {
            sel.value = newInput.value.trim();
        }
    });
});
</script>

@endsection
