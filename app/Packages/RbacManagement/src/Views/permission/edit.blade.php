@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('permissions.index') }}" style="color:#64748b;text-decoration:none;">Permissions</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Edit {{ $permission->name }}</span>
</nav>
@endsection

@section('content')

<div style="max-width:640px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Permission</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Update permission details</p>
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
    <form method="POST" action="{{ route('permissions.update', $permission) }}">
        @csrf @method('PUT')
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.input name="name" label="Permission Name" type="text" required
                :value="old('name', $permission->name)" />

            <x-core-package::form.select name="module_name" label="Module">
                <option value="">— Select Module —</option>
                @foreach($modules as $mod)
                <option value="{{ $mod }}" @selected(old('module_name', $permission->module_name) === $mod)>{{ $mod }}</option>
                @endforeach
                @if($permission->module_name && !$modules->contains($permission->module_name))
                <option value="{{ $permission->module_name }}" selected>{{ $permission->module_name }}</option>
                @endif
            </x-core-package::form.select>

            <x-core-package::form.textarea name="description" label="Description" rows="3"
                hint="Optional: describe what this permission allows">{{ old('description', $permission->description) }}</x-core-package::form.textarea>

            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Update Permission</x-core-package::btn>
                <x-core-package::btn :href="route('permissions.show', $permission)" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
