<div style="max-width:760px;margin:0 auto;">

    <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0 0 1.5rem;">{{ $title }}</h1>

    @if($errors->any())
    <x-core-package::alert type="error" style="margin-bottom:1.25rem;">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-core-package::alert>
    @endif

    <form method="POST" action="{{ $action }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        <x-core-package::card title="Hostel Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="hostel_name" label="Hostel Name" required
                    placeholder="e.g. Boys Hostel A"
                    value="{{ old('hostel_name', $hostel->hostel_name ?? '') }}" />

                <x-core-package::form.select name="hostel_type" label="Hostel Type" required>
                    <option value="">— Select Type —</option>
                    @foreach(['Boys','Girls','Co-ed'] as $t)
                    <option value="{{ $t }}" @selected(old('hostel_type', $hostel->hostel_type ?? '') === $t)>{{ $t }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="total_capacity" label="Total Capacity" type="number" required
                    placeholder="e.g. 100"
                    value="{{ old('total_capacity', $hostel->total_capacity ?? '') }}" />

                <x-core-package::form.input
                    name="available_capacity" label="Available Capacity" type="number" required
                    placeholder="e.g. 50"
                    value="{{ old('available_capacity', $hostel->available_capacity ?? '') }}" />

                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="location" label="Location"
                        placeholder="e.g. North Campus Block A"
                        value="{{ old('location', $hostel->location ?? '') }}" />
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($hostel) ? 'Update Hostel' : 'Create Hostel' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('hostels.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
