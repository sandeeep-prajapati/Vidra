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

        <x-core-package::card title="Transport Assignment">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="student_id" label="Student ID" type="number" required
                    placeholder="Enter student ID"
                    value="{{ old('student_id', $studentTransport->student_id ?? '') }}" />

                <x-core-package::form.select name="transport_id" label="Transport Service" required>
                    <option value="">— Select Service —</option>
                    @foreach($transports as $t)
                    <option value="{{ $t->transport_id }}"
                        @selected((string) old('transport_id', $studentTransport->transport_id ?? '') === (string) $t->transport_id)>
                        {{ $t->transport_name }} ({{ $t->transport_type }})
                    </option>
                    @endforeach
                </x-core-package::form.select>

                <x-core-package::form.input
                    name="pickup_location" label="Pickup Location"
                    placeholder="e.g. Main Gate"
                    value="{{ old('pickup_location', $studentTransport->pickup_location ?? '') }}" />

                <x-core-package::form.input
                    name="drop_location" label="Drop Location"
                    placeholder="e.g. Academic Block"
                    value="{{ old('drop_location', $studentTransport->drop_location ?? '') }}" />

                <x-core-package::form.input
                    name="assigned_date" label="Assigned Date" type="date" required
                    value="{{ old('assigned_date', isset($studentTransport) ? $studentTransport->assigned_date?->format('Y-m-d') : '') }}" />

                <x-core-package::form.input
                    name="leave_date" label="Leave Date" type="date"
                    hint="Leave empty if still using the service"
                    value="{{ old('leave_date', isset($studentTransport) ? $studentTransport->leave_date?->format('Y-m-d') : '') }}" />
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($studentTransport) ? 'Update Assignment' : 'Create Assignment' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('student-transport.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
