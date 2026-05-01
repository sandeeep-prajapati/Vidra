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

        <x-core-package::card title="Transport Information">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="transport_name" label="Transport Name" required
                    placeholder="e.g. Bus A, Van 1"
                    value="{{ old('transport_name', $transportation->transport_name ?? '') }}" />

                <x-core-package::form.select name="transport_type" label="Transport Type" required>
                    <option value="">— Select Type —</option>
                    @foreach(['Bus','Van','Shuttle'] as $t)
                    <option value="{{ $t }}" @selected(old('transport_type', $transportation->transport_type ?? '') === $t)>{{ $t }}</option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="capacity" label="Capacity" type="number" required
                    placeholder="Number of seats"
                    value="{{ old('capacity', $transportation->capacity ?? '') }}" />

                <x-core-package::form.input
                    name="departure_time" label="Departure Time" type="time"
                    value="{{ old('departure_time', $transportation->departure_time ?? '') }}" />

                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="route" label="Route"
                        placeholder="e.g. Hostel to Campus via Main Gate"
                        value="{{ old('route', $transportation->route ?? '') }}" />
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($transportation) ? 'Update Transport' : 'Create Transport' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('transportation.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
