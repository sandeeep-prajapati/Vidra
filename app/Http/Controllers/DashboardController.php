<?php

namespace App\Http\Controllers;

use App\Packages\StudentManagement\Models\Student;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\FeeManagement\Models\FeePayment;
use App\Packages\CommunicationManagement\Models\Message;
use App\Packages\TimetableManagement\Models\SpecialEvent;
use App\Packages\AttendanceManagement\Models\StudentAttendance;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = Student::count();
        $totalStaff = Staff::count();
        $totalClasses = SchoolClass::count();
        $totalFees = FeePayment::sum('amount_paid') ?? 0;

        $studentGrowth = $this->getStudentGrowthData();
        $feeCollection = $this->getFeeCollectionData();
        $staffDistribution = $this->getStaffDistributionData();
        $attendanceRate = $this->getAttendanceRateData();
        $recentMessages = $this->getRecentMessages();
        $upcomingEvents = $this->getUpcomingEvents();

        return view('core-package::dashboard.index', [
            'totalStudents' => $totalStudents,
            'totalStaff' => $totalStaff,
            'totalClasses' => $totalClasses,
            'totalFees' => number_format($totalFees, 2),
            'studentGrowth' => $studentGrowth,
            'feeCollection' => $feeCollection,
            'staffDistribution' => $staffDistribution,
            'attendanceRate' => $attendanceRate,
            'recentMessages' => $recentMessages,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    private function getStudentGrowthData(): array
    {
        $data = Student::selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('DATE_FORMAT(created_at, "%b")')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    private function getFeeCollectionData(): array
    {
        $data = FeePayment::selectRaw('DATE_FORMAT(created_at, "%b") as month, SUM(amount_paid) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('DATE_FORMAT(created_at, "%b")')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'data' => $data->pluck('total')->map(fn($v) => number_format($v, 2))->toArray(),
        ];
    }

    private function getStaffDistributionData(): array
    {
        $data = Staff::selectRaw('designation, COUNT(*) as count')
            ->groupBy('designation')
            ->limit(5)
            ->get();

        return [
            'labels' => $data->pluck('designation')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    private function getAttendanceRateData(): array
    {
        $present = StudentAttendance::where('status', 'Present')->count();
        $absent = StudentAttendance::where('status', 'Absent')->count();
        $late = StudentAttendance::where('status', 'Late')->count();

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $present + $absent + $late,
        ];
    }

    private function getRecentMessages(): array
    {
        return Message::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['message_id', 'title', 'content', 'created_at'])
            ->toArray();
    }

    private function getUpcomingEvents(): array
    {
        return SpecialEvent::where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->limit(5)
            ->get(['event_id', 'event_name', 'event_date', 'description'])
            ->toArray();
    }
}
