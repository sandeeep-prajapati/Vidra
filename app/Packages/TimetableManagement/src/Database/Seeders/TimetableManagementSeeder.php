<?php

namespace App\Packages\TimetableManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimetableManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Rooms
        DB::table('rooms')->insert([
            ['room_name' => 'Room 101', 'room_type' => 'Classroom',  'capacity' => 40, 'description' => 'Standard classroom with smart board', 'created_at' => $now, 'updated_at' => $now],
            ['room_name' => 'Room 102', 'room_type' => 'Classroom',  'capacity' => 40, 'description' => 'Standard classroom',                   'created_at' => $now, 'updated_at' => $now],
            ['room_name' => 'Room 103', 'room_type' => 'Classroom',  'capacity' => 35, 'description' => 'Standard classroom',                   'created_at' => $now, 'updated_at' => $now],
            ['room_name' => 'Science Lab', 'room_type' => 'Lab',     'capacity' => 30, 'description' => 'Fully equipped science laboratory',     'created_at' => $now, 'updated_at' => $now],
            ['room_name' => 'Computer Lab', 'room_type' => 'Lab',    'capacity' => 25, 'description' => 'Computer lab with 25 workstations',     'created_at' => $now, 'updated_at' => $now],
            ['room_name' => 'Auditorium', 'room_type' => 'Auditorium','capacity' => 300,'description' => 'Main school auditorium',               'created_at' => $now, 'updated_at' => $now],
        ]);

        // Days
        DB::table('days')->insert([
            ['day_name' => 'Monday',    'created_at' => $now, 'updated_at' => $now],
            ['day_name' => 'Tuesday',   'created_at' => $now, 'updated_at' => $now],
            ['day_name' => 'Wednesday', 'created_at' => $now, 'updated_at' => $now],
            ['day_name' => 'Thursday',  'created_at' => $now, 'updated_at' => $now],
            ['day_name' => 'Friday',    'created_at' => $now, 'updated_at' => $now],
            ['day_name' => 'Saturday',  'created_at' => $now, 'updated_at' => $now],
        ]);

        // Periods
        DB::table('periods')->insert([
            ['start_time' => '08:00:00', 'end_time' => '08:45:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '08:45:00', 'end_time' => '09:30:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '09:45:00', 'end_time' => '10:30:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '10:30:00', 'end_time' => '11:15:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '12:00:00', 'end_time' => '12:45:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '12:45:00', 'end_time' => '13:30:00', 'created_at' => $now, 'updated_at' => $now],
            ['start_time' => '13:30:00', 'end_time' => '14:15:00', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Timetable for Class 1 (class_id=1), Section A (section_id=1), AY 2024-25 (academic_year_id=1)
        // Subjects 1-7, rotate through teachers 1-7, rooms 1-5
        // Days 1-5 (Mon-Fri), Periods 1-6
        // Subject rotation per day: [Math, English, Science, Hindi, SocialStudies, CS, PE]
        $subjectTeacherRoom = [
            1 => [1, 1], // Math     → teacher 1, room 1
            2 => [3, 1], // English  → teacher 3, room 1
            3 => [2, 4], // Science  → teacher 2, room 4
            4 => [4, 2], // Hindi    → teacher 4, room 2
            5 => [7, 2], // SocSt    → teacher 7, room 2
            6 => [8, 5], // CS       → teacher 8, room 5
            7 => [6, 3], // PE       → teacher 6, room 3
        ];

        // Monday schedule for class 1, section 1
        $dailySchedule = [
            // [day_id, period_id, subject_id]
            [1, 1, 1], [1, 2, 2], [1, 3, 3], [1, 4, 4], [1, 5, 5], [1, 6, 6],
            [2, 1, 2], [2, 2, 3], [2, 3, 4], [2, 4, 5], [2, 5, 6], [2, 6, 1],
            [3, 1, 3], [3, 2, 4], [3, 3, 5], [3, 4, 6], [3, 5, 1], [3, 6, 2],
            [4, 1, 4], [4, 2, 5], [4, 3, 6], [4, 4, 1], [4, 5, 2], [4, 6, 3],
            [5, 1, 7], [5, 2, 1], [5, 3, 2], [5, 4, 3], [5, 5, 4], [5, 6, 5],
        ];

        foreach ($dailySchedule as [$dayId, $periodId, $subjectId]) {
            [$teacherId, $roomId] = $subjectTeacherRoom[$subjectId];
            DB::table('timetables')->insertOrIgnore([
                'class_id'         => 1,
                'section_id'       => 1,
                'academic_year_id' => 1,
                'day_id'           => $dayId,
                'period_id'        => $periodId,
                'subject_id'       => $subjectId,
                'teacher_id'       => $teacherId,
                'room_id'          => $roomId,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // Special Events
        DB::table('special_events')->insert([
            [
                'event_name'  => 'Annual Sports Day',
                'event_date'  => '2024-11-15',
                'start_time'  => '08:00:00',
                'end_time'    => '17:00:00',
                'description' => 'Annual inter-class sports competition',
                'room_id'     => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'event_name'  => 'Annual Day Celebration',
                'event_date'  => '2024-12-20',
                'start_time'  => '10:00:00',
                'end_time'    => '14:00:00',
                'description' => 'Annual school cultural day with performances',
                'room_id'     => 6,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'event_name'  => 'Science Exhibition',
                'event_date'  => '2025-01-10',
                'start_time'  => '09:00:00',
                'end_time'    => '16:00:00',
                'description' => 'Student science project exhibition',
                'room_id'     => 4,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        // Substitute Assignments (timetable_id=1 has the first slot)
        DB::table('substitute_assignments')->insert([
            [
                'timetable_id'           => 1,
                'original_teacher_id'    => 1,
                'substitute_teacher_id'  => 5,
                'date_of_substitution'   => '2024-04-10',
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
            [
                'timetable_id'           => 7,
                'original_teacher_id'    => 3,
                'substitute_teacher_id'  => 4,
                'date_of_substitution'   => '2024-04-12',
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
        ]);
    }
}
