@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('library.books.index') }}" style="color:#64748b;text-decoration:none;">Books</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Search Results</span>
</nav>
@endsection

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Search Results</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Results for "{{ $query }}"</p>
</div>

<x-core-package::card style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('library.books.search') }}" style="display:flex;gap:.75rem;align-items:flex-end;">
        <div style="flex:1;">
            <x-core-package::form.input name="query" label="Search Books" type="text" :value="$query" placeholder="Title, author or ISBN..." />
        </div>
        <div style="padding-top:1.375rem;display:flex;gap:.5rem;">
            <x-core-package::btn type="submit" color="primary">Search</x-core-package::btn>
            <x-core-package::btn :href="route('library.books.index')" color="secondary">All Books</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

<x-core-package::card :noPadding="true">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Title / Author</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">ISBN</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Category</th>
                    <th style="padding:.625rem 1rem;text-align:center;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Available</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($books as $book)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.75rem 1.25rem;">
                    <div style="font-weight:600;color:#1e293b;">{{ $book->title }}</div>
                    <div style="font-size:.75rem;color:#64748b;">{{ $book->author }}</div>
                </td>
                <td style="padding:.75rem 1rem;color:#64748b;">{{ $book->isbn ?? '—' }}</td>
                <td style="padding:.75rem 1rem;">
                    @if($book->category)<x-core-package::badge color="indigo">{{ $book->category->name }}</x-core-package::badge>@else<span style="color:#94a3b8;">—</span>@endif
                </td>
                <td style="padding:.75rem 1rem;text-align:center;">
                    <span style="font-weight:600;color:{{ $book->available_copies > 0 ? '#059669' : '#dc2626' }};">{{ $book->available_copies }}</span>
                </td>
                <td style="padding:.75rem 1rem;">
                    @can('edit_library-management_item')
                    <x-core-package::btn :href="route('library.books.edit', $book)" color="secondary" size="sm">Edit</x-core-package::btn>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem;text-align:center;color:#94a3b8;">No books found for "{{ $query }}".</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-core-package::card>

<div style="margin-top:1rem;">{{ $books->links() }}</div>

@endsection
