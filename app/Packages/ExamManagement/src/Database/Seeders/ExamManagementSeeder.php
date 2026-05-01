<?php

namespace App\Packages\ExamManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Exams
        DB::table('exams')->insert([
            [
                'exam_name'        => 'Mid-Term Examination 2024-25',
                'academic_year_id' => 1,
                'start_date'       => '2024-09-16',
                'end_date'         => '2024-09-25',
                'description'      => 'Mid-term assessment for all classes',
                'is_final'         => false,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'exam_name'        => 'Final Examination 2024-25',
                'academic_year_id' => 1,
                'start_date'       => '2025-02-10',
                'end_date'         => '2025-02-25',
                'description'      => 'Annual final examination for all classes',
                'is_final'         => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'exam_name'        => 'Unit Test 1 — 2024-25',
                'academic_year_id' => 1,
                'start_date'       => '2024-06-10',
                'end_date'         => '2024-06-14',
                'description'      => 'First unit test of the academic year',
                'is_final'         => false,
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        // Exam Schedules — for Mid-Term (exam_id=1), Class 1 (class_id=1), 5 subjects
        $schedules = [
            [1, 1, 1, '2024-09-16', '09:00:00', '11:00:00', 100, 40],  // Math
            [1, 1, 2, '2024-09-17', '09:00:00', '11:00:00', 100, 40],  // English
            [1, 1, 3, '2024-09-18', '09:00:00', '11:00:00', 100, 40],  // Science
            [1, 1, 4, '2024-09-19', '09:00:00', '11:00:00', 100, 40],  // Hindi
            [1, 1, 5, '2024-09-20', '09:00:00', '11:00:00', 100, 40],  // Social Studies
            // Final Exam (exam_id=2), Class 1
            [2, 1, 1, '2025-02-10', '09:00:00', '12:00:00', 100, 35],
            [2, 1, 2, '2025-02-12', '09:00:00', '12:00:00', 100, 35],
            [2, 1, 3, '2025-02-14', '09:00:00', '12:00:00', 100, 35],
            [2, 1, 4, '2025-02-17', '09:00:00', '12:00:00', 100, 35],
            [2, 1, 5, '2025-02-19', '09:00:00', '12:00:00', 100, 35],
        ];

        foreach ($schedules as [$examId, $classId, $subjectId, $date, $start, $end, $total, $passing]) {
            DB::table('exam_schedules')->insert([
                'exam_id'       => $examId,
                'class_id'      => $classId,
                'subject_id'    => $subjectId,
                'exam_date'     => $date,
                'start_time'    => $start,
                'end_time'      => $end,
                'total_marks'   => $total,
                'passing_marks' => $passing,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // Grading Schemes
        $grades = [
            [90.00, 100.00, 'A+', 'Outstanding'],
            [80.00, 89.99,  'A',  'Excellent'],
            [70.00, 79.99,  'B+', 'Very Good'],
            [60.00, 69.99,  'B',  'Good'],
            [50.00, 59.99,  'C',  'Average'],
            [40.00, 49.99,  'D',  'Below Average'],
            [0.00,  39.99,  'F',  'Fail'],
        ];

        foreach ($grades as [$min, $max, $grade, $remarks]) {
            DB::table('grading_schemes')->insert([
                'min_percentage' => $min,
                'max_percentage' => $max,
                'grade'          => $grade,
                'remarks'        => $remarks,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // Student Marks — students 1-4 for Mid-Term schedules 1-5 (schedule_ids 1-5)
        $markData = [
            // [student_id, schedule_id, marks]
            [1, 1, 85.00], [1, 2, 78.00], [1, 3, 92.00], [1, 4, 70.00], [1, 5, 88.00],
            [2, 1, 72.00], [2, 2, 65.00], [2, 3, 80.00], [2, 4, 55.00], [2, 5, 74.00],
            [3, 1, 95.00], [3, 2, 89.00], [3, 3, 91.00], [3, 4, 87.00], [3, 5, 93.00],
            [4, 1, 60.00], [4, 2, 58.00], [4, 3, 67.00], [4, 4, 50.00], [4, 5, 63.00],
        ];

        foreach ($markData as [$studentId, $scheduleId, $marks]) {
            $percentage = $marks;
            $grade = match(true) {
                $percentage >= 90 => 'A+',
                $percentage >= 80 => 'A',
                $percentage >= 70 => 'B+',
                $percentage >= 60 => 'B',
                $percentage >= 50 => 'C',
                $percentage >= 40 => 'D',
                default           => 'F',
            };

            DB::table('student_marks')->insertOrIgnore([
                'student_id'     => $studentId,
                'schedule_id'    => $scheduleId,
                'marks_obtained' => $marks,
                'grade'          => $grade,
                'remarks'        => 'Marks recorded after verification',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // Student Report Cards — students 1-4 for Mid-Term (exam_id=1)
        $reportCards = [
            [1, 1, 413.00, 500.00, 82.60, 'A',  2],
            [2, 1, 346.00, 500.00, 69.20, 'B+', 3],
            [3, 1, 455.00, 500.00, 91.00, 'A+', 1],
            [4, 1, 298.00, 500.00, 59.60, 'C',  4],
        ];

        foreach ($reportCards as [$studentId, $examId, $total, $max, $pct, $grade, $rank]) {
            DB::table('student_report_cards')->insertOrIgnore([
                'student_id'         => $studentId,
                'exam_id'            => $examId,
                'total_marks'        => $total,
                'maximum_marks'      => $max,
                'overall_percentage' => $pct,
                'overall_grade'      => $grade,
                'rank_in_class'      => $rank,
                'remarks'            => 'Performance summary for Mid-Term 2024-25',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }
    }
}
