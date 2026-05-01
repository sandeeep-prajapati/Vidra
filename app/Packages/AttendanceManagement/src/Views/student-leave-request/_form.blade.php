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

        <x-core-package::card title="Leave Request Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <x-core-package::form.input
                    name="student_id" label="Student ID" required type="number"
                    placeholder="e.g. 101"
                    value="{{ old('student_id', $record->student_id ?? '') }}" />
                <x-core-package::form.input
                    name="applied_on" label="Applied On" required type="date"
                    value="{{ old('applied_on', isset($record) ? $record->applied_on->format('Y-m-d') : now()->format('Y-m-d')) }}" />
                <x-core-package::form.input
                    name="start_date" label="Start Date" required type="date"
                    value="{{ old('start_date', isset($record) ? $record->start_date->format('Y-m-d') : '') }}" />
                <x-core-package::form.input
                    name="end_date" label="End Date" required type="date"
                    value="{{ old('end_date', isset($record) ? $record->end_date->format('Y-m-d') : '') }}" />
                <x-core-package::form.select name="status" label="Status" required>
                    @foreach(['Pending','Approved','Rejected'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $record->status ?? 'Pending') === $s)>{{ $s }}</option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.input
                    name="approved_by" label="Approved By (Staff ID)" type="number"
                    placeholder="Staff ID"
                    value="{{ old('approved_by', $record->approved_by ?? '') }}" />
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="reason" label="Reason" required rows="3"
                        placeholder="Reason for leave">{{ old('reason', $record->reason ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($record) ? 'Update Request' : 'Submit Request' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('studentLeaveRequest.index')" color="secondary">Cancel</x-core-package::btn>
        </div>

    </form>
</div>
