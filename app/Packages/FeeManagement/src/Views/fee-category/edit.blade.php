@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('feeCategory.index') }}" style="color:#64748b;text-decoration:none;">Fee Categories</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">Edit Category</span>
</nav>
@endsection

@section('content')

<div style="max-width:40rem;">
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Fee Category</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $feeCategory->category_name }}</p>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('feeCategory.update', $feeCategory) }}">
        @csrf @method('PUT')
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <x-core-package::form.input name="category_name" label="Category Name" required value="{{ old('category_name', $feeCategory->category_name) }}" />
            <x-core-package::form.textarea name="description" label="Description" rows="3" value="{{ old('description', $feeCategory->description) }}" />
            @if($errors->any())
            <x-core-package::alert type="error">{{ $errors->first() }}</x-core-package::alert>
            @endif
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Update Category</x-core-package::btn>
                <x-core-package::btn :href="route('feeCategory.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>
</div>

@endsection
