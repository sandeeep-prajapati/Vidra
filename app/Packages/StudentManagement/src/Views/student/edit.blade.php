@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('students.index') }}" style="color:#94a3b8;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Students</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <a href="{{ route('students.show', $student) }}" style="color:#94a3b8;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">{{ $student->first_name }} {{ $student->last_name }}</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Edit</span>
</nav>
@endsection

@section('content')

<x-core-package::page-header
    title="Edit Student"
    :subtitle="$student->first_name . ' ' . $student->last_name . ' — ' . $student->admission_number"
    :back="route('students.show', $student)"
/>

@if($errors->any())
<x-core-package::alert type="error" title="Please fix the following errors before saving:">
    <ul style="margin:.375rem 0 0;padding-left:1rem;list-style:disc;">
        @foreach($errors->all() as $error)
        <li style="font-size:.75rem;margin-bottom:.125rem;">{{ $error }}</li>
        @endforeach
    </ul>
</x-core-package::alert>
@endif

<form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ── 1. Basic Information ── --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Basic Information" description="Student's personal and contact details.">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <x-core-package::form.input name="first_name" label="First Name" required :value="old('first_name', $student->first_name)" placeholder="e.g. Ravi" />
            <x-core-package::form.input name="last_name"  label="Last Name"  required :value="old('last_name',  $student->last_name)"  placeholder="e.g. Kumar" />

            <x-core-package::form.input name="date_of_birth" label="Date of Birth" required type="date" :value="old('date_of_birth', $student->date_of_birth)" />

            <x-core-package::form.select name="gender" label="Gender" required>
                @foreach(['Male','Female','Other'] as $g)
                <option value="{{ $g }}" {{ old('gender', $student->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </x-core-package::form.select>

            <x-core-package::form.select name="blood_group" label="Blood Group" placeholder="Select Blood Group">
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </x-core-package::form.select>

            <x-core-package::form.input name="nationality" label="Nationality" :value="old('nationality', $student->nationality)" placeholder="e.g. Indian" />
            <x-core-package::form.input name="religion"    label="Religion"    :value="old('religion', $student->religion)"    placeholder="e.g. Hindu" />
            <x-core-package::form.input name="phone_number" label="Phone Number" :value="old('phone_number', $student->phone_number)" />
            <x-core-package::form.input name="email" label="Email Address" type="email" :value="old('email', $student->email)" />

            {{-- Profile Photo --}}
            <x-core-package::form.file-upload
                name="profile_photo"
                label="Profile Photo"
                accept="image/jpeg,image/png,image/gif,image/webp"
                preview="{{ old('profile_photo', $student->profile_photo) }}" />

            <div style="grid-column:1/-1;">
                <x-core-package::form.textarea name="current_address"   label="Current Address"   required rows="2">{{ old('current_address',   $student->current_address) }}</x-core-package::form.textarea>
            </div>
            <div style="grid-column:1/-1;">
                <x-core-package::form.textarea name="permanent_address" label="Permanent Address" required rows="2">{{ old('permanent_address', $student->permanent_address) }}</x-core-package::form.textarea>
            </div>
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── 2. Admission Information ── --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Admission Information" description="Enrollment and status details.">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <x-core-package::form.input name="date_of_admission" label="Date of Admission" required type="date" :value="old('date_of_admission', $student->date_of_admission)" />
            <x-core-package::form.input name="admission_number"  label="Admission Number"  required :value="old('admission_number', $student->admission_number)" />
            <x-core-package::form.select name="status" label="Status">
                @foreach(['Active','Inactive','Graduated','Transferred'] as $s)
                <option value="{{ $s }}" {{ old('status', $student->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </x-core-package::form.select>
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── 3. Parent / Guardian ── --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Parent / Guardian Information">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <x-core-package::form.input name="father_name"       label="Father Name"       :value="old('father_name',       $student->parentInfo->father_name       ?? '')" />
            <x-core-package::form.input name="father_phone"      label="Father Phone"      :value="old('father_phone',      $student->parentInfo->father_phone      ?? '')" />
            <x-core-package::form.input name="father_email"      label="Father Email"      type="email" :value="old('father_email',      $student->parentInfo->father_email      ?? '')" />
            <x-core-package::form.input name="father_occupation" label="Father Occupation" :value="old('father_occupation', $student->parentInfo->father_occupation ?? '')" />
            <x-core-package::form.input name="mother_name"       label="Mother Name"       :value="old('mother_name',       $student->parentInfo->mother_name       ?? '')" />
            <x-core-package::form.input name="mother_phone"      label="Mother Phone"      :value="old('mother_phone',      $student->parentInfo->mother_phone      ?? '')" />
            <x-core-package::form.input name="mother_email"      label="Mother Email"      type="email" :value="old('mother_email',      $student->parentInfo->mother_email      ?? '')" />
            <x-core-package::form.input name="mother_occupation" label="Mother Occupation" :value="old('mother_occupation', $student->parentInfo->mother_occupation ?? '')" />
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="guardian_name" label="Guardian Name" :value="old('guardian_name', $student->parentInfo->guardian_name ?? '')" />
            </div>
            <x-core-package::form.input name="guardian_relationship" label="Guardian Relationship" :value="old('guardian_relationship', $student->parentInfo->guardian_relationship ?? '')" />
            <x-core-package::form.input name="guardian_phone"        label="Guardian Phone"        :value="old('guardian_phone',        $student->parentInfo->guardian_phone        ?? '')" />
            <div style="grid-column:1/-1;">
                <x-core-package::form.textarea name="guardian_address" label="Guardian Address" rows="2">{{ old('guardian_address', $student->parentInfo->guardian_address ?? '') }}</x-core-package::form.textarea>
            </div>
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── 4. Previous Education ── --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Previous Education">
        <x-slot:action>
            <x-core-package::btn type="button" color="success" size="sm" onclick="addEducation()">
                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Row
            </x-core-package::btn>
        </x-slot:action>

        <div id="education-container" style="display:flex;flex-direction:column;gap:.875rem;">
            @forelse($student->previousEducations as $index => $edu)
            <div class="edu-row" style="border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;position:relative;">
                <button type="button" onclick="this.closest('.edu-row').remove()"
                    style="position:absolute;top:.625rem;right:.625rem;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;line-height:1;"
                    onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#94a3b8'">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <input type="hidden" name="previous_educations_index[]" value="{{ $index }}">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.875rem;">
                    <x-core-package::form.input :name="'previous_educations['.$index.'][school_name]'"    label="School Name"    :value="$edu->school_name"    placeholder="e.g. ABC High School" />
                    <x-core-package::form.input :name="'previous_educations['.$index.'][board]'"          label="Board"          :value="$edu->board"          placeholder="e.g. CBSE" />
                    <x-core-package::form.input :name="'previous_educations['.$index.'][class_completed]'" label="Class Completed" :value="$edu->class_completed" placeholder="e.g. 10th" />
                    <x-core-package::form.input :name="'previous_educations['.$index.'][percentage]'"     label="Percentage (%)" :value="$edu->percentage"     type="number" step="0.01" />
                    <x-core-package::form.input :name="'previous_educations['.$index.'][year_of_passing]'" label="Year of Passing" :value="$edu->year_of_passing" type="number" />
                </div>
            </div>
            @empty
            <div class="edu-row" style="border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;">
                <input type="hidden" name="previous_educations_index[]" value="0">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.875rem;">
                    <x-core-package::form.input name="previous_educations[0][school_name]"    label="School Name"    placeholder="e.g. ABC High School" />
                    <x-core-package::form.input name="previous_educations[0][board]"          label="Board"          placeholder="e.g. CBSE" />
                    <x-core-package::form.input name="previous_educations[0][class_completed]" label="Class Completed" placeholder="e.g. 10th" />
                    <x-core-package::form.input name="previous_educations[0][percentage]"     label="Percentage (%)" type="number" step="0.01" />
                    <x-core-package::form.input name="previous_educations[0][year_of_passing]" label="Year of Passing" type="number" />
                </div>
            </div>
            @endforelse
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── 5. Health Information ── --}}
<x-core-package::card style="margin-bottom:1.25rem;">
    <x-core-package::form.section title="Health Information">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">
            <x-core-package::form.input name="height_cm" label="Height (cm)" type="number" step="0.1" :value="old('height_cm', $student->healthRecord->height_cm ?? '')" />
            <x-core-package::form.input name="weight_kg" label="Weight (kg)" type="number" step="0.1" :value="old('weight_kg', $student->healthRecord->weight_kg ?? '')" />
            <x-core-package::form.input name="allergies" label="Allergies"   :value="old('allergies', $student->healthRecord->allergies ?? '')" placeholder="e.g. Peanuts" />
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="medical_conditions" label="Medical Conditions" :value="old('medical_conditions', $student->healthRecord->medical_conditions ?? '')" />
            </div>
            <div style="grid-column:1/-1;">
                <x-core-package::form.input name="vaccination_status" label="Vaccination Status"  :value="old('vaccination_status', $student->healthRecord->vaccination_status ?? '')" placeholder="e.g. Up to date" />
            </div>
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── 6. Additional Contacts ── --}}
<x-core-package::card style="margin-bottom:1.5rem;">
    <x-core-package::form.section title="Additional Contacts" description="Update or replace all additional contact numbers.">
        <x-slot:action>
            <x-core-package::btn type="button" color="success" size="sm" onclick="addContact()">
                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Contact
            </x-core-package::btn>
        </x-slot:action>

        <div id="contact-container" style="display:flex;flex-direction:column;gap:.875rem;">
            @forelse($student->contacts as $ci => $contact)
            <div class="contact-row" style="border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;position:relative;">
                <button type="button" onclick="this.closest('.contact-row').remove()"
                    style="position:absolute;top:.625rem;right:.625rem;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;line-height:1;"
                    onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#94a3b8'">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.875rem;align-items:end;">
                    <x-core-package::form.select :name="'contacts['.$ci.'][contact_type]'" label="Type">
                        @foreach(['phone','mobile','emergency','work','fax','other'] as $t)
                        <option value="{{ $t }}" {{ $contact->contact_type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </x-core-package::form.select>
                    <x-core-package::form.input :name="'contacts['.$ci.'][contact_value]'" label="Number / Value" :value="$contact->contact_value" />
                    <x-core-package::form.input :name="'contacts['.$ci.'][label]'"         label="Label"         :value="$contact->label" placeholder="e.g. Home" />
                    <label style="display:flex;align-items:center;gap:.375rem;font-size:.8125rem;color:#374151;padding-bottom:.5rem;cursor:pointer;">
                        <input type="checkbox" name="contacts[{{ $ci }}][is_primary]" value="1" {{ $contact->is_primary ? 'checked' : '' }} style="width:1rem;height:1rem;accent-color:#4f46e5;">
                        Primary
                    </label>
                </div>
            </div>
            @empty
            <div class="contact-row" style="border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.875rem;align-items:end;">
                    <x-core-package::form.select name="contacts[0][contact_type]" label="Type">
                        @foreach(['phone','mobile','emergency','work','fax','other'] as $t)
                        <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                        @endforeach
                    </x-core-package::form.select>
                    <x-core-package::form.input name="contacts[0][contact_value]" label="Number / Value" placeholder="e.g. +91-9876543210" />
                    <x-core-package::form.input name="contacts[0][label]"         label="Label"         placeholder="e.g. Home" />
                    <label style="display:flex;align-items:center;gap:.375rem;font-size:.8125rem;color:#374151;padding-bottom:.5rem;cursor:pointer;">
                        <input type="checkbox" name="contacts[0][is_primary]" value="1" style="width:1rem;height:1rem;accent-color:#4f46e5;">
                        Primary
                    </label>
                </div>
            </div>
            @endforelse
        </div>
    </x-core-package::form.section>
</x-core-package::card>

{{-- ── Actions ── --}}
<div style="display:flex;gap:.75rem;padding:.25rem 0 1rem;">
    <x-core-package::btn type="submit" color="primary">
        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Update Student
    </x-core-package::btn>
    <x-core-package::btn href="{{ route('students.show', $student) }}" color="secondary">Cancel</x-core-package::btn>
</div>

</form>
@endsection

@push('scripts')
<script>
let eduCount = {{ $student->previousEducations->count() ?: 1 }};
let contactCount = {{ $student->contacts->count() ?: 1 }};

function addEducation() {
    const c = document.getElementById('education-container');
    const i = eduCount++;
    const d = document.createElement('div');
    d.className = 'edu-row';
    d.style.cssText = 'border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;position:relative;';
    d.innerHTML = `
        <button type="button" onclick="this.closest('.edu-row').remove()"
            style="position:absolute;top:.625rem;right:.625rem;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;line-height:1;"
            onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#94a3b8'">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <input type="hidden" name="previous_educations_index[]" value="${i}">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.875rem;">
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">School Name</label><input type="text" name="previous_educations[${i}][school_name]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Board</label><input type="text" name="previous_educations[${i}][board]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Class Completed</label><input type="text" name="previous_educations[${i}][class_completed]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Percentage (%)</label><input type="number" step="0.01" name="previous_educations[${i}][percentage]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Year of Passing</label><input type="number" name="previous_educations[${i}][year_of_passing]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
        </div>`;
    c.appendChild(d);
}

function addContact() {
    const c = document.getElementById('contact-container');
    const i = contactCount++;
    const d = document.createElement('div');
    d.className = 'contact-row';
    d.style.cssText = 'border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#fafafa;position:relative;';
    d.innerHTML = `
        <button type="button" onclick="this.closest('.contact-row').remove()"
            style="position:absolute;top:.625rem;right:.625rem;background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;line-height:1;"
            onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#94a3b8'">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.875rem;align-items:end;">
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Type</label>
            <select name="contacts[${i}][contact_type]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;">
                <option value="phone">Phone</option><option value="mobile">Mobile</option><option value="emergency">Emergency</option><option value="work">Work</option><option value="fax">Fax</option><option value="other">Other</option>
            </select></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Number / Value</label><input type="text" name="contacts[${i}][contact_value]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.8125rem;font-weight:500;color:#374151;margin-bottom:.375rem;">Label</label><input type="text" name="contacts[${i}][label]" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;outline:none;box-sizing:border-box;"></div>
            <label style="display:flex;align-items:center;gap:.375rem;font-size:.8125rem;color:#374151;padding-bottom:.5rem;cursor:pointer;"><input type="checkbox" name="contacts[${i}][is_primary]" value="1" style="width:1rem;height:1rem;accent-color:#4f46e5;"> Primary</label>
        </div>`;
    c.appendChild(d);
}
</script>
@endpush
