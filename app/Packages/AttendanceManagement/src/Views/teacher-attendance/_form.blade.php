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

        <x-core-package::card title="Attendance Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="staff_id" label="Staff ID" required type="number"
                    placeholder="e.g. 10"
                    value="{{ old('staff_id', $record->staff_id ?? '') }}" />
                <x-core-package::form.input
                    name="date" label="Date" required type="date"
                    value="{{ old('date', isset($record) ? $record->date->format('Y-m-d') : '') }}" />
                <x-core-package::form.select name="status" label="Status" required>
                    @foreach(['Present','Absent','Leave'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $record->status ?? 'Present') === $s)>{{ $s }}</option>
                    @endforeach
                </x-core-package::form.select>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="remarks" label="Remarks" rows="3"
                        placeholder="Reason for absence or leave (optional)">{{ old('remarks', $record->remarks ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($record) ? 'Update Attendance' : 'Save Attendance' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('teacherAttendance.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>
