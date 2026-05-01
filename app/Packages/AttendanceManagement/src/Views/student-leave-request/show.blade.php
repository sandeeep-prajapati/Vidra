@extends('core-package::layouts.app')

@section('breadcrumb')
<nav style="font-size:.8125rem;color:#64748b;">
    <a href="{{ route('studentLeaveRequest.index') }}" style="color:#94a3b8;text-decoration:none;"
       onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#94a3b8'">Student Leave Requests</a>
    <span style="margin:0 .375rem;color:#cbd5e1;">/</span>
    <span style="color:#374151;font-weight:500;">Request #{{ $studentLeaveRequest->leave_request_id }}</span>
</nav>
@endsection

@section('content')

<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#f59e0b,#fcd34d);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:1.375rem;height:1.375rem;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div style="display:flex;align-items:center;gap:.625rem;">
                <h1 style="font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;">Leave Request #{{ $studentLeaveRequest->leave_request_id }}</h1>
                <x-core-package::badge color="{{ $studentLeaveRequest->status === 'Approved' ? 'green' : ($studentLeaveRequest->status === 'Rejected' ? 'red' : 'yellow') }}">
                    {{ $studentLeaveRequest->status }}
                </x-core-package::badge>
            </div>
            <p style="font-size:.8125rem;color:#64748b;margin:.25rem 0 0;">
                Applied: {{ $studentLeaveRequest->applied_on->format('d M Y') }}
            </p>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;flex-shrink:0;padding-top:.25rem;">
        <x-core-package::btn :href="route('studentLeaveRequest.edit', $studentLeaveRequest)" color="primary" size="sm">Edit</x-core-package::btn>
        <x-core-package::btn :href="route('studentLeaveRequest.index')" color="secondary" size="sm">Back</x-core-package::btn>
    </div>
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 22rem;gap:1.25rem;align-items:start;">

    <x-core-package::card title="Leave Details">
        <div style="display:flex;flex-direction:column;gap:.875rem;">
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Student</p>
                <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">
                    {{ $studentLeaveRequest->student ? $studentLeaveRequest->student->first_name.' '.$studentLeaveRequest->student->last_name : 'Student #'.$studentLeaveRequest->student_id }}
                </p>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Start Date</p>
                    <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">{{ $studentLeaveRequest->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">End Date</p>
                    <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">{{ $studentLeaveRequest->end_date->format('d M Y') }}</p>
                </div>
            </div>
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Duration</p>
                <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">
                    {{ $studentLeaveRequest->start_date->diffInDays($studentLeaveRequest->end_date) + 1 }} day(s)
                </p>
            </div>
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Reason</p>
                <p style="font-size:.875rem;color:#475569;margin:0;line-height:1.5;">{{ $studentLeaveRequest->reason }}</p>
            </div>
            @if($studentLeaveRequest->approvedBy)
            <div>
                <p style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .25rem;">Approved / Rejected By</p>
                <p style="font-size:.875rem;font-weight:500;color:#1e293b;margin:0;">
                    {{ $studentLeaveRequest->approvedBy->first_name.' '.$studentLeaveRequest->approvedBy->last_name }}
                </p>
            </div>
            @endif
        </div>
    </x-core-package::card>

    <x-core-package::card title="Danger Zone">
        <form method="POST" action="{{ route('studentLeaveRequest.destroy', $studentLeaveRequest) }}">
            @csrf @method('DELETE')
            <p style="font-size:.75rem;color:#64748b;margin:0 0 .875rem;">
                Permanently delete this leave request.
            </p>
            <x-core-package::btn type="submit" color="danger"
                onclick="return confirm('Delete this leave request? This cannot be undone.')">
                Delete Request
            </x-core-package::btn>
        </form>
    </x-core-package::card>

</div>

@endsection
