@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('staff.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Staff</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">{{ $staffMember->first_name }} {{ $staffMember->last_name }}</span>
</nav>
@endsection

@section('content')

@php
$statusColor = match($staffMember->status) {
    'Active'   => 'green',
    'Inactive' => 'yellow',
    'Resigned' => 'gray',
    default    => 'gray',
};
$initials = strtoupper(substr($staffMember->first_name, 0, 1) . substr($staffMember->last_name, 0, 1));
@endphp

{{-- Page header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        @if($staffMember->photo)
        <img src="{{ $staffMember->photo }}" alt="{{ $staffMember->first_name }}"
             style="width:3.5rem;height:3.5rem;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;flex-shrink:0;">
        @else
        <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:1.125rem;font-weight:700;color:#fff;">{{ $initials }}</span>
        </div>
        @endif
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">{{ $staffMember->first_name }} {{ $staffMember->last_name }}</h1>
                <x-core-package::badge :color="$statusColor">{{ $staffMember->status }}</x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                {{ $staffMember->designation ?: 'Staff Member' }}
                @if($staffMember->department) &middot; {{ $staffMember->department->department_name }} @endif
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('staff.edit', $staffMember)" color="primary" size="sm">
            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </x-core-package::btn>
        <x-core-package::btn :href="route('staff.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

{{-- Two-column layout --}}
<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    {{-- ── Left column ── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Profile & Employment --}}
        <x-core-package::card title="Profile & Employment">
            @php
            function staffInfoRow(string $label, $value): void {
                echo '<div style="padding:.625rem .5rem;">'
                   . '<p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">' . e($label) . '</p>'
                   . '<p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;overflow-wrap:anywhere;">' . (trim((string)$value) ?: '<span style="color:#cbd5e1;">—</span>') . '</p>'
                   . '</div>';
            }
            @endphp
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem .25rem;">
                @php staffInfoRow('First Name', $staffMember->first_name); @endphp
                @php staffInfoRow('Last Name',  $staffMember->last_name); @endphp
                @php staffInfoRow('Date of Birth', optional($staffMember->date_of_birth)->format('M d, Y')); @endphp
                @php staffInfoRow('Gender', $staffMember->gender); @endphp
                @php staffInfoRow('Phone', $staffMember->phone_number); @endphp
                @php staffInfoRow('Email', $staffMember->email); @endphp
                @php staffInfoRow('Nationality', $staffMember->nationality); @endphp
                @php staffInfoRow('Joining Date', optional($staffMember->joining_date)->format('M d, Y')); @endphp
                @php staffInfoRow('Employment Type', $staffMember->employment_type); @endphp
                @php staffInfoRow('Designation', $staffMember->designation); @endphp
                @if($staffMember->address)
                <div style="grid-column:1/-1;padding:.625rem .5rem;">
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Address</p>
                    <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">{{ $staffMember->address }}</p>
                </div>
                @endif
            </div>
        </x-core-package::card>

        {{-- Department Assignments --}}
        @if($staffMember->departmentAssignments->count())
        <x-core-package::card title="Department Assignments">
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($staffMember->departmentAssignments as $assignment)
                <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e2e8f0;border-radius:.5rem;padding:.75rem 1rem;background:#fafafa;">
                    <div>
                        <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $assignment->department->department_name ?? '—' }}</p>
                        <p style="font-size:.7rem;color:#64748b;margin:.125rem 0 0;">Assigned {{ optional($assignment->assigned_date)->format('M d, Y') ?: 'N/A' }}</p>
                    </div>
                    @if($assignment->is_primary)
                    <x-core-package::badge color="indigo">Primary</x-core-package::badge>
                    @endif
                </div>
                @endforeach
            </div>
        </x-core-package::card>
        @endif

        {{-- Qualifications --}}
        @if($staffMember->qualifications->count())
        <x-core-package::card title="Qualifications">
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($staffMember->qualifications as $qual)
                <div style="border:1px solid #e2e8f0;border-radius:.5rem;padding:.875rem 1rem;background:#fafafa;">
                    <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $qual->degree }}</p>
                    <p style="font-size:.75rem;color:#64748b;margin:.25rem 0 0;">
                        {{ $qual->specialization ?: 'General' }} &middot; {{ $qual->university_name }} &middot; {{ $qual->year_of_completion }}
                    </p>
                </div>
                @endforeach
            </div>
        </x-core-package::card>
        @endif

        {{-- Teacher Assignments --}}
        @if($staffMember->teacherAssignments->count())
        <x-core-package::card title="Teacher Assignments">
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($staffMember->teacherAssignments as $ta)
                <div style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e2e8f0;border-radius:.5rem;padding:.75rem 1rem;background:#fafafa;">
                    <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $ta->class_name }}</p>
                    <span style="font-size:.75rem;color:#64748b;">{{ $ta->subject }}</span>
                </div>
                @endforeach
            </div>
        </x-core-package::card>
        @endif

        {{-- Attendance --}}
        @if($staffMember->attendances->count())
        <x-core-package::card title="Recent Attendance" :noPadding="true">
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Date</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($staffMember->attendances->take(12) as $att)
                    @php
                    $attColor = match($att->status) {
                        'Present'  => 'green',
                        'Absent'   => 'red',
                        'Late'     => 'yellow',
                        default    => 'gray',
                    };
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.75rem 1.25rem;color:#374151;">{{ optional($att->date)->format('M d, Y') }}</td>
                        <td style="padding:.75rem 1rem;"><x-core-package::badge :color="$attColor">{{ $att->status }}</x-core-package::badge></td>
                        <td style="padding:.75rem 1rem;color:#64748b;">{{ $att->remarks ?: '—' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-core-package::card>
        @endif

        {{-- Salary History --}}
        @if($staffMember->salaries->count())
        <x-core-package::card title="Salary History" :noPadding="true">
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.625rem 1.25rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Payment Date</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Basic</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Allowances</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Deductions</th>
                            <th style="padding:.625rem 1rem;text-align:left;font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Net</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($staffMember->salaries as $salary)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:.75rem 1.25rem;color:#374151;">{{ optional($salary->payment_date)->format('M d, Y') }}</td>
                        <td style="padding:.75rem 1rem;color:#64748b;">₹{{ number_format($salary->basic_salary, 2) }}</td>
                        <td style="padding:.75rem 1rem;color:#64748b;">₹{{ number_format($salary->allowances, 2) }}</td>
                        <td style="padding:.75rem 1rem;color:#64748b;">₹{{ number_format($salary->deductions, 2) }}</td>
                        <td style="padding:.75rem 1rem;font-weight:600;color:#1e293b;">₹{{ number_format($salary->net_salary, 2) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-core-package::card>
        @endif

        {{-- Performance Reviews --}}
        @if($staffMember->performanceReviews->count())
        <x-core-package::card title="Performance Reviews">
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($staffMember->performanceReviews as $review)
                <div style="border:1px solid #e2e8f0;border-radius:.5rem;padding:.875rem 1rem;background:#fafafa;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;margin-bottom:.375rem;">
                        <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">
                            {{ optional($review->review_period_start)->format('M d, Y') }} – {{ optional($review->review_period_end)->format('M d, Y') }}
                        </p>
                        @if($review->rating)
                        <x-core-package::badge color="indigo">{{ $review->rating }}/5</x-core-package::badge>
                        @endif
                    </div>
                    <p style="font-size:.75rem;color:#64748b;margin:0;">{{ $review->comments ?: 'No comments' }}</p>
                    @if($review->reviewed_by)
                    <p style="font-size:.7rem;color:#94a3b8;margin:.25rem 0 0;">Reviewed by {{ $review->reviewed_by }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </x-core-package::card>
        @endif

        {{-- Leave Requests --}}
        @if($staffMember->leaveRequests->count())
        <x-core-package::card title="Leave Requests">
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($staffMember->leaveRequests as $leave)
                @php
                $leaveColor = match($leave->status) {
                    'Approved'  => 'green',
                    'Rejected'  => 'red',
                    'Cancelled' => 'gray',
                    default     => 'yellow',
                };
                @endphp
                <div style="display:flex;align-items:flex-start;justify-content:space-between;border:1px solid #e2e8f0;border-radius:.5rem;padding:.75rem 1rem;background:#fafafa;gap:.75rem;">
                    <div>
                        <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $leave->leave_type }}</p>
                        <p style="font-size:.75rem;color:#64748b;margin:.25rem 0 0;">
                            {{ optional($leave->start_date)->format('M d, Y') }} – {{ optional($leave->end_date)->format('M d, Y') }}
                        </p>
                        @if($leave->reason)
                        <p style="font-size:.7rem;color:#94a3b8;margin:.25rem 0 0;">{{ $leave->reason }}</p>
                        @endif
                    </div>
                    <x-core-package::badge :color="$leaveColor">{{ $leave->status }}</x-core-package::badge>
                </div>
                @endforeach
            </div>
        </x-core-package::card>
        @endif

    </div>{{-- end left --}}

    {{-- ── Right sidebar ── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Quick Info --}}
        <x-core-package::card title="Quick Info">
            <div style="display:flex;flex-direction:column;gap:.875rem;">
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Department</p>
                    <p style="font-size:.875rem;font-weight:600;color:#1e293b;margin:0;">{{ $staffMember->department->department_name ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Latest Net Salary</p>
                    <p style="font-size:.9375rem;font-weight:700;color:#1e293b;margin:0;">
                        @if($staffMember->latestSalary) ₹{{ number_format($staffMember->latestSalary->net_salary, 2) }} @else — @endif
                    </p>
                </div>
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .375rem;">Status</p>
                    <x-core-package::badge :color="$statusColor">{{ $staffMember->status }}</x-core-package::badge>
                </div>
            </div>
        </x-core-package::card>

        {{-- Add Department Assignment --}}
        <x-core-package::card title="Add Department Assignment">
            <form method="POST" action="{{ route('staff.department-assignments.store', $staffMember) }}"
                  style="display:flex;flex-direction:column;gap:.75rem;">
                @csrf
                <x-core-package::form.select name="department_id" label="Department" required placeholder="Select Department">
                    @foreach($departments as $department)
                    <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                    @endforeach
                </x-core-package::form.select>
                <x-core-package::form.input name="assigned_date" label="Assigned Date" type="date" :value="date('Y-m-d')" />
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.8125rem;color:#374151;cursor:pointer;">
                    <input type="checkbox" name="is_primary" value="1" style="width:1rem;height:1rem;accent-color:#4f46e5;">
                    Primary department
                </label>
                <x-core-package::btn type="submit" color="dark">Save Assignment</x-core-package::btn>
            </form>
        </x-core-package::card>

        {{-- Mark Attendance --}}
        <x-core-package::card title="Mark Attendance">
            <form method="POST" action="{{ route('staff.attendance.store', $staffMember) }}"
                  style="display:flex;flex-direction:column;gap:.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="date" label="Date" type="date" required :value="date('Y-m-d')" />
                    <x-core-package::form.select name="status" label="Status" required>
                        @foreach(['Present','Absent','On Leave','Late'] as $s)
                        <option>{{ $s }}</option>
                        @endforeach
                    </x-core-package::form.select>
                </div>
                <x-core-package::form.textarea name="remarks" label="Remarks" rows="2" />
                <x-core-package::btn type="submit" color="success">Save Attendance</x-core-package::btn>
            </form>
        </x-core-package::card>

        {{-- Add Salary --}}
        <x-core-package::card title="Add Salary Record">
            <form method="POST" action="{{ route('staff.salary.store', $staffMember) }}"
                  style="display:flex;flex-direction:column;gap:.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="basic_salary" label="Basic Salary" type="number" step="0.01" required />
                    <x-core-package::form.input name="payment_date" label="Payment Date" type="date" required :value="date('Y-m-d')" />
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="allowances" label="Allowances" type="number" step="0.01" />
                    <x-core-package::form.input name="deductions" label="Deductions" type="number" step="0.01" />
                </div>
                <x-core-package::form.input name="net_salary" label="Net Salary" type="number" step="0.01" hint="Auto-calculated if blank" />
                <x-core-package::btn type="submit" color="warning">Save Salary</x-core-package::btn>
            </form>
        </x-core-package::card>

        {{-- Add Review --}}
        <x-core-package::card title="Add Performance Review">
            <form method="POST" action="{{ route('staff.reviews.store', $staffMember) }}"
                  style="display:flex;flex-direction:column;gap:.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="review_period_start" label="Start" type="date" required />
                    <x-core-package::form.input name="review_period_end" label="End" type="date" required />
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="rating" label="Rating (0-5)" type="number" step="0.1" min="0" max="5" />
                    <x-core-package::form.input name="reviewed_by" label="Reviewed By" />
                </div>
                <x-core-package::form.textarea name="comments" label="Comments" rows="2" />
                <x-core-package::btn type="submit" color="primary">Save Review</x-core-package::btn>
            </form>
        </x-core-package::card>

        {{-- Leave Request --}}
        <x-core-package::card title="Add Leave Request">
            <form method="POST" action="{{ route('staff.leave-requests.store', $staffMember) }}"
                  style="display:flex;flex-direction:column;gap:.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="leave_type" label="Type" :value="'Casual'" required />
                    <x-core-package::form.select name="status" label="Status">
                        @foreach(['Pending','Approved','Rejected','Cancelled'] as $s)
                        <option>{{ $s }}</option>
                        @endforeach
                    </x-core-package::form.select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <x-core-package::form.input name="start_date" label="Start Date" type="date" required />
                    <x-core-package::form.input name="end_date" label="End Date" type="date" required />
                </div>
                <x-core-package::form.textarea name="reason" label="Reason" rows="2" />
                <x-core-package::btn type="submit" color="danger">Save Leave</x-core-package::btn>
            </form>
        </x-core-package::card>

        {{-- Danger zone --}}
        <x-core-package::card title="Danger Zone">
            <form method="POST" action="{{ route('staff.destroy', $staffMember) }}">
                @csrf @method('DELETE')
                <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                    Permanently delete this staff member and all associated records. This action cannot be undone.
                </p>
                <x-core-package::btn type="submit" color="danger"
                    onclick="return confirm('Permanently delete {{ $staffMember->first_name }} {{ $staffMember->last_name }}? This cannot be undone.')">
                    Delete Staff Member
                </x-core-package::btn>
            </form>
        </x-core-package::card>

    </div>{{-- end sidebar --}}

</div>{{-- end two-column --}}

@endsection
