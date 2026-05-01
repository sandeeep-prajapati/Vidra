<?php

namespace App\Packages\AttendanceManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Student Attendance — 5 working days for students 1-10, batch_id 1
        $attendanceDates = ['2024-04-08', '2024-04-09', '2024-04-10', '2024-04-11', '2024-04-12'];
        $statuses = ['Present', 'Present', 'Absent', 'Present', 'Leave'];

        foreach (range(1, 10) as $studentId) {
            foreach ($attendanceDates as $idx => $date) {
                $status = $idx === 2 && $studentId <= 3 ? 'Absent' : ($idx === 4 && $studentId === 2 ? 'Leave' : 'Present');
                DB::table('student_attendance')->insertOrIgnore([
                    'student_id' => $studentId,
                    'date'       => $date,
                    'status'     => $status,
                    'batch_id'   => (int) ceil($studentId / 2),
                    'remarks'    => $status !== 'Present' ? 'Recorded by class teacher' : null,
                    'marked_by'  => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Teacher Attendance — 5 days for staff 1-8
        foreach (range(1, 8) as $staffId) {
            foreach ($attendanceDates as $idx => $date) {
                $status = $idx === 2 && $staffId === 3 ? 'Leave' : 'Present';
                DB::table('teacher_attendance')->insertOrIgnore([
                    'staff_id'   => $staffId,
                    'date'       => $date,
                    'status'     => $status,
                    'remarks'    => $status !== 'Present' ? 'Pre-approved leave' : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Staff Attendance (StaffManagement table)
        foreach (range(1, 8) as $staffId) {
            foreach ($attendanceDates as $idx => $date) {
                $status = $idx === 3 && $staffId === 5 ? 'Late' : 'Present';
                DB::table('staff_attendance')->insertOrIgnore([
                    'staff_id'   => $staffId,
                    'date'       => $date,
                    'status'     => $status,
                    'remarks'    => $status === 'Late' ? 'Arrived 30 minutes late' : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Student Leave Requests
        $studentLeaves = [
            [1, '2024-04-10', '2024-04-10', 'Fever and cold', 'Approved', '2024-04-09', 1],
            [2, '2024-04-12', '2024-04-12', 'Family function', 'Approved', '2024-04-11', 1],
            [3, '2024-04-15', '2024-04-16', 'Out of station',  'Pending',  '2024-04-14', null],
            [5, '2024-04-18', '2024-04-18', 'Medical appointment', 'Approved', '2024-04-17', 1],
            [7, '2024-04-20', '2024-04-22', 'Religious ceremony', 'Rejected', '2024-04-19', 1],
        ];

        foreach ($studentLeaves as [$studentId, $start, $end, $reason, $status, $appliedOn, $approvedBy]) {
            DB::table('student_leave_requests')->insert([
                'student_id'  => $studentId,
                'start_date'  => $start,
                'end_date'    => $end,
                'reason'      => $reason,
                'status'      => $status,
                'applied_on'  => $appliedOn,
                'approved_by' => $approvedBy,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // Teacher Leave Requests
        $teacherLeaves = [
            [1, '2024-05-05', '2024-05-06', 'Medical treatment', 'Approved', '2024-05-04'],
            [3, '2024-04-10', '2024-04-10', 'Personal emergency', 'Approved', '2024-04-09'],
            [5, '2024-06-01', '2024-06-03', 'Family event', 'Pending', '2024-05-28'],
            [7, '2024-05-20', '2024-05-20', 'Exam duty elsewhere', 'Approved', '2024-05-18'],
        ];

        foreach ($teacherLeaves as [$staffId, $start, $end, $reason, $status, $appliedOn]) {
            DB::table('teacher_leave_requests')->insert([
                'staff_id'   => $staffId,
                'start_date' => $start,
                'end_date'   => $end,
                'reason'     => $reason,
                'status'     => $status,
                'applied_on' => $appliedOn,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Holidays
        $holidays = [
            ['Independence Day',   '2024-08-15', 'National holiday — Independence Day of India', true],
            ['Gandhi Jayanti',     '2024-10-02', 'National holiday — Gandhi Jayanti',           true],
            ['Diwali',             '2024-11-01', 'Festival of lights',                          true],
            ['Christmas',          '2024-12-25', 'Christmas Day',                               true],
            ['Republic Day',       '2025-01-26', 'National holiday — Republic Day of India',   true],
            ['Holi',               '2025-03-14', 'Festival of colours',                         true],
            ['School Anniversary', '2024-09-10', 'Annual School Foundation Day',               false],
        ];

        foreach ($holidays as [$title, $date, $description, $recurring]) {
            DB::table('holidays')->insert([
                'title'        => $title,
                'date'         => $date,
                'description'  => $description,
                'is_recurring' => $recurring,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}
