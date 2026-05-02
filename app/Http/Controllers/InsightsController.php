<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\PythonBridgeService;

class InsightsController extends Controller
{
    public function index()
    {
        // ── Students ──────────────────────────────────────────────
        $totalStudents = DB::table('students')->count();

        $genderBreakdown = DB::table('students')
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $bloodGroups = DB::table('students')
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->orderByDesc('total')
            ->get();

        $studentsPerClass = DB::table('student_enrollments as se')
            ->join('batches as b',  'se.batch_id',  '=', 'b.batch_id')
            ->join('classes as c',  'b.class_id',   '=', 'c.class_id')
            ->select('c.class_name', DB::raw('count(*) as total'))
            ->groupBy('c.class_name')
            ->orderBy('c.class_name')
            ->get();

        // ── Staff ─────────────────────────────────────────────────
        $totalStaff = DB::table('staff')->count();

        $staffByDept = DB::table('staff as s')
            ->join('departments as d', 's.department_id', '=', 'd.department_id')
            ->select('d.department_name', DB::raw('count(*) as total'))
            ->groupBy('d.department_name')
            ->orderByDesc('total')
            ->get();

        $employmentTypes = DB::table('staff')
            ->select('employment_type', DB::raw('count(*) as total'))
            ->groupBy('employment_type')
            ->pluck('total', 'employment_type');

        $salaryStats = DB::table('salary_details')
            ->selectRaw('MIN(net_salary) as min_sal, MAX(net_salary) as max_sal, AVG(net_salary) as avg_sal, SUM(net_salary) as total_payroll')
            ->first();

        $topRatedStaff = DB::table('performance_reviews as pr')
            ->join('staff as s', 'pr.staff_id', '=', 's.staff_id')
            ->select(
                DB::raw("CONCAT(s.first_name, ' ', s.last_name) as name"),
                'pr.rating',
                'pr.comments'
            )
            ->orderByDesc('pr.rating')
            ->limit(5)
            ->get();

        // ── Academics ────────────────────────────────────────────
        $reportCards = DB::table('student_report_cards as rc')
            ->join('students as s', 'rc.student_id', '=', 's.student_id')
            ->join('exams as e',    'rc.exam_id',    '=', 'e.exam_id')
            ->select(
                DB::raw("CONCAT(s.first_name, ' ', s.last_name) as name"),
                'rc.overall_percentage',
                'rc.overall_grade',
                'rc.rank_in_class',
                'e.exam_name'
            )
            ->orderBy('rc.rank_in_class')
            ->get();

        $subjectAvgs = DB::table('student_marks as sm')
            ->join('exam_schedules as es', 'sm.schedule_id', '=', 'es.schedule_id')
            ->join('subjects as sub',      'es.subject_id',  '=', 'sub.subject_id')
            ->select('sub.subject_name', DB::raw('AVG(sm.marks_obtained) as avg_marks'))
            ->groupBy('sub.subject_name')
            ->orderByDesc('avg_marks')
            ->get();

        $classAvgPct = $reportCards->avg('overall_percentage');

        // ── Attendance ───────────────────────────────────────────
        $attendanceSummary = DB::table('student_attendance')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalAttendanceRecords = $attendanceSummary->sum();
        $presentCount = $attendanceSummary->get('Present', 0);
        $overallAttendancePct = $totalAttendanceRecords > 0
            ? round($presentCount / $totalAttendanceRecords * 100, 1)
            : 0;

        $holidays = DB::table('holidays')->count();

        // ── Fee & Finance ─────────────────────────────────────────
        $feeByStatus = DB::table('student_fees')
            ->select('payment_status', DB::raw('count(*) as total, SUM(total_payable) as amount'))
            ->groupBy('payment_status')
            ->get();

        $totalExpenses = DB::table('expenses')->sum('amount');

        $expenseByCategory = DB::table('expenses')
            ->select('expense_category', DB::raw('SUM(amount) as total'))
            ->groupBy('expense_category')
            ->orderByDesc('total')
            ->get();

        $financialReports = DB::table('financial_reports')
            ->select('report_type', DB::raw('SUM(total_amount) as total'))
            ->groupBy('report_type')
            ->pluck('total', 'report_type');

        $income  = $financialReports->get('Income', 0);
        $expense = $financialReports->get('Expense', 0);

        // ── Classes ───────────────────────────────────────────────
        $classCapacity = DB::table('batches as b')
            ->join('classes as c',    'b.class_id',    '=', 'c.class_id')
            ->join('sections as sec', 'b.section_id',  '=', 'sec.section_id')
            ->leftJoin('student_enrollments as se', 'b.batch_id', '=', 'se.batch_id')
            ->select(
                'c.class_name',
                'sec.section_name',
                'sec.capacity',
                DB::raw('count(se.enrollment_id) as enrolled')
            )
            ->groupBy('c.class_name', 'sec.section_name', 'sec.capacity', 'b.batch_id')
            ->orderBy('c.class_name')
            ->orderBy('sec.section_name')
            ->get();

        // ── Django / Python-powered analytics ─────────────────────
        $python = new PythonBridgeService();
        $djangoStats = $python->get('api/python/analytics/attendance/school-overview/');

        return view('insights.index', compact(
            'totalStudents', 'genderBreakdown', 'bloodGroups', 'studentsPerClass',
            'totalStaff', 'staffByDept', 'employmentTypes', 'salaryStats', 'topRatedStaff',
            'reportCards', 'subjectAvgs', 'classAvgPct',
            'attendanceSummary', 'overallAttendancePct', 'holidays',
            'feeByStatus', 'totalExpenses', 'expenseByCategory', 'income', 'expense',
            'classCapacity',
            'djangoStats'
        ));
    }
}
