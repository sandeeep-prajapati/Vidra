@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.books.index') }}" style="color:#64748b;text-decoration:none;">Books</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Add Book</span>
</nav>
@endsection

@section('content')
<div style="max-width:680px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Add New Book</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Add a book to the library catalog</p>
</div>

@if($errors->any())
<x-core-package::alert type="error" style="margin-bottom:1rem;">
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</x-core-package::alert>
@endif

<x-core-package::card>
    <form method="POST" action="{{ route('library.books.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">

            <x-core-package::form.input name="title" label="Title" required :value="old('title')" />
            <x-core-package::form.input name="author" label="Author" required :value="old('author')" />

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="isbn" label="ISBN" :value="old('isbn')" />
                <x-core-package::form.input name="edition" label="Edition" :value="old('edition')" />
            </div>

            <x-core-package::form.select name="category_id" label="Category" required>
                <option value="">— Select Category —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </x-core-package::form.select>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <x-core-package::form.input name="total_copies" label="Total Copies" type="number" min="1" required :value="old('total_copies', 1)" />
                <x-core-package::form.input name="rack_number" label="Rack / Shelf No." :value="old('rack_number')" />
            </div>

            <x-core-package::form.select name="status" label="Status" required>
                <option value="active" @selected(old('status','active')==='active')>Active</option>
                <option value="inactive" @selected(old('status')==='inactive')>Inactive</option>
            </x-core-package::form.select>

            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Add Book</x-core-package::btn>
                <x-core-package::btn :href="route('library.books.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
