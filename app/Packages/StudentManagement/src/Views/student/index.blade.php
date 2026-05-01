@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <span style="color:#94a3b8;">Home</span>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Students</span>
</nav>
@endsection

@section('content')

<x-core-package::page-header title="Students" subtitle="Manage all student records, enrollments and profiles.">
    <x-slot:actions>
        <x-core-package::btn href="{{ route('students.create') }}" color="primary">
            <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Student
        </x-core-package::btn>
    </x-slot:actions>
</x-core-package::page-header>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <x-core-package::stats-card label="Total Students" :value="$students->total()" color="indigo" />
    <x-core-package::stats-card label="Active" :value="$students->getCollection()->where('status','Active')->count()" color="green" />
    <x-core-package::stats-card label="Graduated" :value="$students->getCollection()->where('status','Graduated')->count()" color="blue" />
    <x-core-package::stats-card label="Inactive / Transferred" :value="$students->getCollection()->whereIn('status',['Inactive','Transferred'])->count()" color="yellow" />
</div>

{{-- Filter bar + table --}}
<x-core-package::card :noPadding="true">

    {{-- Filters --}}
    <form method="GET" action="{{ route('students.index') }}" style="padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;background:#fafafa;">
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <div style="flex:1;min-width:14rem;">
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:.25rem;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Name, admission no, phone, email…"
                    style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.4375rem .75rem;font-size:.8125rem;color:#111827;background:#fff;outline:none;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,.12)'"
                    onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
            </div>
            <div style="min-width:10rem;">
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:.25rem;">Status</label>
                <select name="status" style="display:block;width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.4375rem .75rem;font-size:.8125rem;color:#111827;background:#fff;outline:none;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#d1d5db'">
                    <option value="">All Statuses</option>
                    @foreach(['Active', 'Inactive', 'Graduated', 'Transferred'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:.5rem;">
                <x-core-package::btn type="submit" color="primary" size="sm">Filter</x-core-package::btn>
                <x-core-package::btn href="{{ route('students.index') }}" color="secondary" size="sm">Reset</x-core-package::btn>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:.625rem 1.25rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Student</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Admission No.</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Gender</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Admission Date</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Contact</th>
                    <th style="padding:.625rem 1rem;text-align:left;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Status</th>
                    <th style="padding:.625rem 1rem;text-align:right;font-size:.7rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                    $initials = strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1));
                    $avatarColors = ['#4f46e5','#7c3aed','#2563eb','#0891b2','#16a34a','#d97706','#dc2626'];
                    $avatarColor = $avatarColors[$student->student_id % count($avatarColors)];
                    $statusColor = match($student->status) {
                        'Active'      => 'green',
                        'Inactive'    => 'yellow',
                        'Graduated'   => 'blue',
                        'Transferred' => 'orange',
                        default       => 'gray',
                    };
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;transition:background .1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">

                    {{-- Student name + avatar --}}
                    <td style="padding:.875rem 1.25rem;">
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            @if($student->profile_photo)
                            <img src="{{ $student->profile_photo }}" alt="{{ $student->first_name }}"
                                style="width:2.25rem;height:2.25rem;border-radius:50%;object-fit:cover;flex-shrink:0;">
                            @else
                            <div style="width:2.25rem;height:2.25rem;background:{{ $avatarColor }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="font-size:.6875rem;font-weight:700;color:#fff;">{{ $initials }}</span>
                            </div>
                            @endif
                            <div>
                                <p style="font-weight:600;color:#1e293b;margin:0;font-size:.8125rem;">{{ $student->first_name }} {{ $student->last_name }}</p>
                                <p style="font-size:.7rem;color:#94a3b8;margin:.125rem 0 0;">#{{ $student->student_id }}</p>
                            </div>
                        </div>
                    </td>

                    <td style="padding:.875rem 1rem;">
                        <span style="font-size:.8125rem;color:#374151;font-weight:500;">{{ $student->admission_number }}</span>
                    </td>

                    <td style="padding:.875rem 1rem;">
                        <span style="font-size:.8125rem;color:#64748b;">{{ $student->gender ?? '—' }}</span>
                    </td>

                    <td style="padding:.875rem 1rem;">
                        <span style="font-size:.8125rem;color:#64748b;">
                            {{ optional($student->date_of_admission)->format('M d, Y') ?? '—' }}
                        </span>
                    </td>

                    <td style="padding:.875rem 1rem;">
                        <span style="font-size:.8125rem;color:#64748b;">{{ $student->phone_number ?: ($student->email ?: '—') }}</span>
                    </td>

                    <td style="padding:.875rem 1rem;">
                        <x-core-package::badge :color="$statusColor">{{ $student->status }}</x-core-package::badge>
                    </td>

                    <td style="padding:.875rem 1rem;text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:.375rem;">
                            <a href="{{ route('students.show', $student) }}"
                               style="display:inline-flex;align-items:center;gap:.25rem;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#4f46e5;background:#eef2ff;border-radius:.375rem;text-decoration:none;transition:background .15s;"
                               onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">View</a>
                            <a href="{{ route('students.edit', $student) }}"
                               style="display:inline-flex;align-items:center;gap:.25rem;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#374151;background:#f1f5f9;border-radius:.375rem;text-decoration:none;transition:background .15s;"
                               onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Edit</a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Delete {{ $student->first_name }} {{ $student->last_name }}? This action cannot be undone.')"
                                    style="display:inline-flex;align-items:center;padding:.3125rem .625rem;font-size:.7rem;font-weight:600;color:#dc2626;background:#fef2f2;border:none;border-radius:.375rem;cursor:pointer;transition:background .15s;"
                                    onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:3rem;text-align:center;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:.75rem;">
                            <div style="width:3rem;height:3rem;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <svg style="width:1.5rem;height:1.5rem;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p style="font-size:.875rem;font-weight:500;color:#64748b;margin:0;">No students found</p>
                            <p style="font-size:.75rem;color:#94a3b8;margin:0;">Try adjusting your search or filter criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
    <div style="padding:.875rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:between;">
        <div style="font-size:.75rem;color:#64748b;margin-bottom:.5rem;">
            Showing {{ $students->firstItem() }}–{{ $students->lastItem() }} of {{ $students->total() }} students
        </div>
        {{ $students->links() }}
    </div>
    @endif

</x-core-package::card>

@endsection
