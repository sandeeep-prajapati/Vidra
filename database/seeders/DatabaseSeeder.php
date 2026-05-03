<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // RBAC — roles and permissions first (no dependencies)
        $this->call(\App\Packages\RbacManagement\Database\Seeders\RbacManagementSeeder::class);

        // Default admin user with super-admin role
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign super-admin role
        $superAdminUser->assignRole('super-admin');

        $this->call([
            // 2. Staff — departments first, then staff and related records
            \App\Packages\StaffManagement\Database\Seeders\StaffManagementSeeder::class,

            // 3. Class — academic years, classes, sections (needs staff for class_teacher_id), batches
            \App\Packages\ClassManagement\Database\Seeders\ClassManagementSeeder::class,

            // 4. Students — students, parents, enrollments (needs batches and academic years)
            \App\Packages\StudentManagement\Database\Seeders\StudentManagementSeeder::class,

            // 5. Subjects — subjects, class_subjects, teacher_subject_mappings, curriculums, lesson_plans, textbooks
            \App\Packages\SubjectManagement\Database\Seeders\SubjectManagementSeeder::class,

            // 6. Timetable — rooms, days, periods, timetables (needs classes, sections, AY, subjects, staff), special events
            \App\Packages\TimetableManagement\Database\Seeders\TimetableManagementSeeder::class,

            // 7. Attendance — student/teacher attendance, leave requests, holidays (needs students, staff, batches)
            \App\Packages\AttendanceManagement\Database\Seeders\AttendanceManagementSeeder::class,

            // 8. Exams — exams, schedules, grading schemes, marks, report cards (needs academic years, classes, subjects, students)
            \App\Packages\ExamManagement\Database\Seeders\ExamManagementSeeder::class,

            // 9. Fees — categories, structures, student fees, payments, discounts, expenses, financial reports
            \App\Packages\FeeManagement\Database\Seeders\FeeManagementSeeder::class,

            // 10. Communication — messages, recipients, circulars, notification settings (needs users)
            \App\Packages\CommunicationManagement\Database\Seeders\CommunicationManagementSeeder::class,

            // 11. Hostel & Transport — hostels, rooms, student assignments, transport, facility management
            \App\Packages\HostelTransportManagement\Database\Seeders\HostelTransportManagementSeeder::class,
        ]);
    }
}
