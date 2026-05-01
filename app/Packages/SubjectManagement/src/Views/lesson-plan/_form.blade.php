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

        <x-core-package::card title="Lesson Plan Details">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <div style="grid-column:1/-1;">
                    <x-core-package::form.select name="curriculum_id" label="Curriculum" required>
                        <option value="">— Select Curriculum —</option>
                        @foreach($curriculums as $curriculum)
                        <option value="{{ $curriculum->curriculum_id }}"
                            @selected((string) old('curriculum_id', $lessonPlan->curriculum_id ?? request('curriculum_id')) === (string) $curriculum->curriculum_id)>
                            {{ $curriculum->subject->subject_name ?? '?' }} — {{ $curriculum->academicYear->year_range ?? '?' }}
                        </option>
                        @endforeach
                    </x-core-package::form.select>
                </div>
                <div style="grid-column:1/-1;">
                    <x-core-package::form.input
                        name="topic_name" label="Topic / Unit Name" required
                        placeholder="e.g. Algebra Basics, Photosynthesis"
                        value="{{ old('topic_name', $lessonPlan->topic_name ?? '') }}" />
                </div>
                <x-core-package::form.input
                    type="date" name="start_date" label="Start Date"
                    value="{{ old('start_date', isset($lessonPlan->start_date) ? $lessonPlan->start_date->format('Y-m-d') : '') }}" />
                <x-core-package::form.input
                    type="date" name="end_date" label="End Date"
                    value="{{ old('end_date', isset($lessonPlan->end_date) ? $lessonPlan->end_date->format('Y-m-d') : '') }}" />
                <div style="grid-column:1/-1;">
                    <x-core-package::form.textarea
                        name="objectives" label="Learning Objectives" rows="4"
                        placeholder="List the learning objectives for this topic">{{ old('objectives', $lessonPlan->objectives ?? '') }}</x-core-package::form.textarea>
                </div>
            </div>
        </x-core-package::card>

        <div style="display:flex;gap:.75rem;">
            <x-core-package::btn type="submit" color="primary">
                {{ isset($lessonPlan) ? 'Update Lesson Plan' : 'Create Lesson Plan' }}
            </x-core-package::btn>
            <x-core-package::btn :href="route('lesson-plans.index')" color="secondary">Cancel</x-core-package::btn>
        </div>
    </form>
</div>
