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

        <x-core-package::card title="Facility Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="facility_name" label="Facility Name" required
                    placeholder="e.g. Gym, Library, Lab A"
                    value="{{ old('facility_name', $facility->facility_name ?? '') }}" />

                <x-core-package::form.select name="facility_type" label="Facility Type" required>
                    <option value="">— Select Type —</option>
                    @foreach(['Sports','Library','Cafeteria','Lab'] as $t)
                    <option value="{{ $t }}" @selected(old('facility_type', $facility->facility_type ?? '') === $t)>{{ $t }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="capacity" label="Total Capacity" type="number" required
                    placeholder="Maximum occupancy"
                    value="{{ old('capacity', $facility->capacity ?? '') }}" />

                <x-core-package::form.input
                    name="available_capacity" label="Available Capacity" type="number" required
                    placeholder="Currently available slots"
                    value="{{ old('available_capacity', $facility->available_capacity ?? '') }}" />

                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="location" label="Location"
                        placeholder="e.g. Block C, Ground Floor"
                        value="{{ old('location', $facility->location ?? '') }}" />
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($facility) ? 'Update Facility' : 'Create Facility' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('facilities.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
