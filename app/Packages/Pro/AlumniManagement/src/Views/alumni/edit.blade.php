@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('alumni.directory') }}" style="color:#64748b;text-decoration:none;">Alumni</a>
    <span style="margin:0 .375rem;">›</span>
    <a href="{{ route('alumni.show', $alumni) }}" style="color:#64748b;text-decoration:none;">{{ $alumni->full_name }}</a>
    <span style="margin:0 .375rem;">›</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')

<div style="max-width:720px;margin:0 auto;">

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Edit Alumni Profile</h1>
    <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">{{ $alumni->full_name }}</p>
</div>

<x-core-package::card>
    <form method="POST" action="{{ route('alumni.update', $alumni) }}">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <x-core-package::form.input name="full_name" label="Full Name" :value="old('full_name', $alumni->full_name)" required />
                @error('full_name')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="email" label="Email" type="email" :value="old('email', $alumni->email)" required />
                @error('email')<div style="color:#dc2626;font-size:.75rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div>
                <x-core-package::form.input name="phone" label="Phone" type="tel" :value="old('phone', $alumni->phone)" />
            </div>
            <div>
                <x-core-package::form.select name="graduation_year" label="Graduation Year" required>
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ old('graduation_year', $alumni->graduation_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.input name="graduation_class" label="Class / Section" :value="old('graduation_class', $alumni->graduation_class)" />
            </div>
            <div>
                <x-core-package::form.select name="status" label="Status" required>
                    @foreach(['active','inactive','deceased'] as $s)
                    <option value="{{ $s }}" {{ old('status', $alumni->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </x-core-package::form.select>
            </div>
            <div>
                <x-core-package::form.input name="current_city" label="Current City" :value="old('current_city', $alumni->current_city)" />
            </div>
            <div>
                <x-core-package::form.input name="current_country" label="Current Country" :value="old('current_country', $alumni->current_country)" />
            </div>
            <div>
                <x-core-package::form.input name="linkedin_url" label="LinkedIn URL" type="url" :value="old('linkedin_url', $alumni->linkedin_url)" />
            </div>
            <div>
                <x-core-package::form.input name="website_url" label="Website URL" type="url" :value="old('website_url', $alumni->website_url)" />
            </div>
        </div>

        <div style="margin-bottom:1.25rem;">
            <x-core-package::form.textarea name="bio" label="Bio" rows="3" :value="old('bio', $alumni->bio)" />
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <x-core-package::btn :href="route('alumni.show', $alumni)" color="secondary">Cancel</x-core-package::btn>
            <x-core-package::btn type="submit" color="primary">Save Changes</x-core-package::btn>
        </div>
    </form>
</x-core-package::card>

</div>
@endsection
