<?php

namespace App\Packages\AttendanceManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\AttendanceManagement\Models\Holiday;
use App\Packages\AttendanceManagement\Models\StudentAttendance;
use App\Packages\AttendanceManagement\Models\StudentLeaveRequest;
use App\Packages\AttendanceManagement\Models\TeacherAttendance;
use App\Packages\AttendanceManagement\Models\TeacherLeaveRequest;
use Illuminate\View\View;

class AttendanceDashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $stats = [
            'student_present_today'  => StudentAttendance::whereDate('date', $today)->where('status', 'Present')->count(),
            'student_absent_today'   => StudentAttendance::whereDate('date', $today)->where('status', 'Absent')->count(),
            'student_leave_today'    => StudentAttendance::whereDate('date', $today)->where('status', 'Leave')->count(),
            'teacher_present_today'  => TeacherAttendance::whereDate('date', $today)->where('status', 'Present')->count(),
            'teacher_absent_today'   => TeacherAttendance::whereDate('date', $today)->where('status', 'Absent')->count(),
            'teacher_leave_today'    => TeacherAttendance::whereDate('date', $today)->where('status', 'Leave')->count(),
            'pending_student_leaves' => StudentLeaveRequest::where('status', 'Pending')->count(),
            'pending_teacher_leaves' => TeacherLeaveRequest::where('status', 'Pending')->count(),
            'total_holidays'         => Holiday::count(),
        ];

        $upcomingHolidays = Holiday::where('date', '>=', $today)
            ->orderBy('date')
            ->limit(5)
            ->get();

        $recentStudentAttendance = StudentAttendance::with(['student', 'batch'])
            ->whereDate('date', $today)
            ->orderByDesc('attendance_id')
            ->limit(8)
            ->get();

        $recentTeacherAttendance = TeacherAttendance::with('staff')
            ->whereDate('date', $today)
            ->orderByDesc('attendance_id')
            ->limit(8)
            ->get();

        $pendingLeaves = StudentLeaveRequest::with('student')
            ->where('status', 'Pending')
            ->orderByDesc('applied_on')
            ->limit(5)
            ->get();

        return view('attendance-management::attendance.index', compact(
            'stats',
            'upcomingHolidays',
            'recentStudentAttendance',
            'recentTeacherAttendance',
            'pendingLeaves'
        ));
    }
}
