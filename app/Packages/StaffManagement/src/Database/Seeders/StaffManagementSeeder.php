<?php

namespace App\Packages\StaffManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();
        $dummyImage = 'dummy/dummy-image.jpg';

        // Departments
        DB::table('departments')->insertOrIgnore([
            ['department_name' => 'Mathematics & Science', 'description' => 'Handles all science and mathematics subjects', 'created_at' => $now, 'updated_at' => $now],
            ['department_name' => 'Languages & Arts',      'description' => 'Covers languages, arts and social studies', 'created_at' => $now, 'updated_at' => $now],
            ['department_name' => 'Physical Education',    'description' => 'Sports, health and physical activities', 'created_at' => $now, 'updated_at' => $now],
            ['department_name' => 'Administration',        'description' => 'School administration and management', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Staff
        $staff = [
            ['first_name' => 'Rajesh',   'last_name' => 'Kumar',   'dob' => '1980-05-15', 'gender' => 'Male',   'phone' => '9876543201', 'email' => 'rajesh.kumar@school.com',   'dept_id' => 1, 'designation' => 'Senior Teacher',     'employment_type' => 'Permanent'],
            ['first_name' => 'Priya',    'last_name' => 'Singh',   'dob' => '1985-08-22', 'gender' => 'Female', 'phone' => '9876543202', 'email' => 'priya.singh@school.com',    'dept_id' => 1, 'designation' => 'Teacher',             'employment_type' => 'Permanent'],
            ['first_name' => 'Anjali',   'last_name' => 'Sharma',  'dob' => '1988-03-10', 'gender' => 'Female', 'phone' => '9876543203', 'email' => 'anjali.sharma@school.com',  'dept_id' => 2, 'designation' => 'Senior Teacher',     'employment_type' => 'Permanent'],
            ['first_name' => 'Vikas',    'last_name' => 'Mehta',   'dob' => '1979-11-30', 'gender' => 'Male',   'phone' => '9876543204', 'email' => 'vikas.mehta@school.com',    'dept_id' => 2, 'designation' => 'Teacher',             'employment_type' => 'Permanent'],
            ['first_name' => 'Sunita',   'last_name' => 'Rao',     'dob' => '1982-07-18', 'gender' => 'Female', 'phone' => '9876543205', 'email' => 'sunita.rao@school.com',     'dept_id' => 1, 'designation' => 'Teacher',             'employment_type' => 'Permanent'],
            ['first_name' => 'Mohan',    'last_name' => 'Das',     'dob' => '1975-12-05', 'gender' => 'Male',   'phone' => '9876543206', 'email' => 'mohan.das@school.com',      'dept_id' => 3, 'designation' => 'PE Teacher',          'employment_type' => 'Permanent'],
            ['first_name' => 'Kavita',   'last_name' => 'Patel',   'dob' => '1990-04-25', 'gender' => 'Female', 'phone' => '9876543207', 'email' => 'kavita.patel@school.com',   'dept_id' => 2, 'designation' => 'Teacher',             'employment_type' => 'Contract'],
            ['first_name' => 'Arjun',    'last_name' => 'Nair',    'dob' => '1986-09-14', 'gender' => 'Male',   'phone' => '9876543208', 'email' => 'arjun.nair@school.com',     'dept_id' => 1, 'designation' => 'Teacher',             'employment_type' => 'Permanent'],
            ['first_name' => 'Deepa',    'last_name' => 'Joshi',   'dob' => '1983-01-20', 'gender' => 'Female', 'phone' => '9876543209', 'email' => 'deepa.joshi@school.com',    'dept_id' => 4, 'designation' => 'Administrative Staff', 'employment_type' => 'Permanent'],
            ['first_name' => 'Suresh',   'last_name' => 'Iyer',    'dob' => '1978-06-08', 'gender' => 'Male',   'phone' => '9876543210', 'email' => 'suresh.iyer@school.com',    'dept_id' => 4, 'designation' => 'Principal',           'employment_type' => 'Permanent'],
        ];

        foreach ($staff as $s) {
            DB::table('staff')->insertOrIgnore([
                'first_name'      => $s['first_name'],
                'last_name'       => $s['last_name'],
                'date_of_birth'   => $s['dob'],
                'gender'          => $s['gender'],
                'phone_number'    => $s['phone'],
                'email'           => $s['email'],
                'address'         => '123 School Colony, City, India',
                'nationality'     => 'Indian',
                'joining_date'    => '2020-06-01',
                'department_id'   => $s['dept_id'],
                'designation'     => $s['designation'],
                'employment_type' => $s['employment_type'],
                'status'          => 'Active',
                'photo'           => $dummyImage,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // Staff Department Assignments (primary assignment)
        foreach (range(1, 10) as $staffId) {
            $deptMap = [1=>1, 2=>1, 3=>2, 4=>2, 5=>1, 6=>3, 7=>2, 8=>1, 9=>4, 10=>4];
            DB::table('staff_department_assignments')->insertOrIgnore([
                'staff_id'      => $staffId,
                'department_id' => $deptMap[$staffId],
                'is_primary'    => true,
                'assigned_date' => '2020-06-01',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // Qualifications
        $qualifications = [
            [1, 'M.Sc', 'Mathematics', 'Delhi University', 2005],
            [2, 'B.Sc', 'Physics', 'Mumbai University', 2008],
            [3, 'M.A', 'English Literature', 'Pune University', 2011],
            [4, 'B.A', 'Hindi', 'Allahabad University', 2003],
            [5, 'M.Sc', 'Chemistry', 'Bangalore University', 2006],
            [6, 'B.P.Ed', 'Physical Education', 'Sports University', 2000],
            [7, 'M.A', 'History', 'JNU', 2014],
            [8, 'M.Sc', 'Computer Science', 'IIT Delhi', 2010],
            [9, 'MBA', 'Human Resources', 'IGNOU', 2007],
            [10, 'Ph.D', 'Education Management', 'Delhi University', 2002],
        ];

        foreach ($qualifications as [$staffId, $degree, $spec, $university, $year]) {
            DB::table('qualifications')->insertOrIgnore([
                'staff_id'           => $staffId,
                'degree'             => $degree,
                'specialization'     => $spec,
                'university_name'    => $university,
                'year_of_completion' => $year,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // Teacher Assignments
        $assignments = [
            [1, 'Class 1', 'Mathematics'],
            [2, 'Class 2', 'Science'],
            [3, 'Class 1', 'English'],
            [4, 'Class 3', 'Hindi'],
            [5, 'Class 4', 'Chemistry'],
            [6, 'Class 5', 'Physical Education'],
            [7, 'Class 2', 'Social Studies'],
            [8, 'Class 3', 'Computer Science'],
            [9, 'Class 4', 'Art & Craft'],
            [10, 'Class 5', 'General Knowledge'],
        ];

        foreach ($assignments as [$staffId, $class, $subject]) {
            DB::table('teacher_assignments')->insertOrIgnore([
                'staff_id'   => $staffId,
                'class_name' => $class,
                'subject'    => $subject,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Salary Details
        $salaries = [
            [1, 45000, 8000, 2000, 51000],
            [2, 38000, 6000, 1500, 42500],
            [3, 42000, 7000, 1800, 47200],
            [4, 36000, 5500, 1400, 40100],
            [5, 40000, 6500, 1600, 44900],
            [6, 32000, 5000, 1200, 35800],
            [7, 35000, 5500, 1300, 39200],
            [8, 44000, 7500, 1900, 49600],
            [9, 30000, 4500, 1100, 33400],
            [10, 70000, 15000, 3000, 82000],
        ];

        foreach ($salaries as [$staffId, $basic, $allowance, $deduction, $net]) {
            DB::table('salary_details')->insert([
                'staff_id'     => $staffId,
                'basic_salary' => $basic,
                'allowances'   => $allowance,
                'deductions'   => $deduction,
                'net_salary'   => $net,
                'payment_date' => '2024-04-30',
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // Performance Reviews
        $reviews = [
            [1, 4.5, 'Excellent performance in teaching Mathematics'],
            [2, 4.0, 'Good classroom management and student engagement'],
            [3, 4.8, 'Outstanding English teaching methodology'],
            [4, 3.8, 'Good work, needs improvement in creative teaching'],
            [5, 4.2, 'Excellent lab management and practical demonstrations'],
        ];

        foreach ($reviews as [$staffId, $rating, $comment]) {
            DB::table('performance_reviews')->insert([
                'staff_id'            => $staffId,
                'review_period_start' => '2024-04-01',
                'review_period_end'   => '2024-09-30',
                'rating'              => $rating,
                'comments'            => $comment,
                'reviewed_by'         => 'Suresh Iyer',
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);
        }

        // Staff Leave Requests
        $leaveRequests = [
            [1, 'Casual', '2024-05-10', '2024-05-11', 'Personal work', 'Approved', 'Suresh Iyer'],
            [2, 'Medical', '2024-06-05', '2024-06-07', 'Medical treatment', 'Approved', 'Suresh Iyer'],
            [3, 'Casual', '2024-07-15', '2024-07-15', 'Family function', 'Pending', null],
            [4, 'Sick', '2024-08-01', '2024-08-02', 'Fever', 'Approved', 'Suresh Iyer'],
            [5, 'Casual', '2024-09-20', '2024-09-20', 'Personal work', 'Rejected', 'Suresh Iyer'],
        ];

        foreach ($leaveRequests as [$staffId, $type, $start, $end, $reason, $status, $approvedBy]) {
            DB::table('staff_leave_requests')->insert([
                'staff_id'    => $staffId,
                'leave_type'  => $type,
                'start_date'  => $start,
                'end_date'    => $end,
                'reason'      => $reason,
                'status'      => $status,
                'approved_by' => $approvedBy,
                'approved_at' => $status !== 'Pending' ? $now : null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
