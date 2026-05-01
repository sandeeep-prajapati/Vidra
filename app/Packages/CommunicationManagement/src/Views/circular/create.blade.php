@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('circular.index') }}" style="color:#64748b;text-decoration:none;">Circulars</a>
    <span style="margin:0 .375rem;">/</span>
    <span style="color:#374151;font-weight:500;">New Circular</span>
</nav>
@endsection

@section('content')

<div style="max-width:720px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">New Circular</h1>
        <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">Issue a formal notice or announcement</p>
    </div>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <x-core-package::card>
        <form method="POST" action="{{ route('circular.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:1rem;">
                <x-core-package::form.input name="title" label="Title" type="text" required value="{{ old('title') }}" />
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.textarea name="content" label="Content" required rows="6" />
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <x-core-package::form.select name="target_audience" label="Target Audience" required>
                    @foreach(['All','Students','Parents','Teachers','Staff'] as $a)
                    <option value="{{ $a }}" @selected(old('target_audience', 'All') === $a)>{{ $a }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input name="issued_date" label="Issue Date" type="date" required
                    value="{{ old('issued_date', date('Y-m-d')) }}" />
            </div>

            <div style="margin-bottom:1rem;">
                <x-core-package::form.select name="issued_by" label="Issued By">
                    <option value="">— Select issuer —</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('issued_by') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>

            <div style="margin-bottom:1.5rem;">
                <x-core-package::form.file-upload name="attachment" label="Attachment" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" hint="PDF or Word document, max 5 MB" />
            </div>

            <div style="display:flex;gap:.75rem;">
                <x-core-package::btn type="submit" color="primary">Publish Circular</x-core-package::btn>
                <x-core-package::btn :href="route('circular.index')" color="secondary">Cancel</x-core-package::btn>
            </div>
        </form>
    </x-core-package::card>
</div>

@endsection
