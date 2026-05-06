@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.issues.index') }}" style="color:#64748b;text-decoration:none;">Issues</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Issue Book</span>
</nav>
@endsection

@section('content')
<div style="max-width:560px;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Issue a Book</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Assign a book to a member</p>
</div>

@if($errors->any())
<x-core-package::alert type="error" style="margin-bottom:1rem;">
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</x-core-package::alert>
@endif

<x-core-package::card>
    <form method="POST" action="{{ route('library.issues.store') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:1rem;">

            <x-core-package::form.select name="book_id" label="Book" required>
                <option value="">— Select Available Book —</option>
                @foreach($books as $book)
                <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                    {{ $book->title }} — {{ $book->author }} ({{ $book->available_copies }} available)
                </option>
                @endforeach
            </x-core-package::form.select>

            <x-core-package::form.select name="member_id" label="Member" required>
                <option value="">— Select Active Member —</option>
                @foreach($members as $member)
                <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                    {{ $member->name }} ({{ $member->membership_number }})
                </option>
                @endforeach
            </x-core-package::form.select>

            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <x-core-package::btn type="submit" color="primary">Issue Book</x-core-package::btn>
                <x-core-package::btn :href="route('library.issues.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
