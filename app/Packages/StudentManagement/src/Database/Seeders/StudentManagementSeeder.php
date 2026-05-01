<?php

namespace App\Packages\StudentManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();
        $dummyImage = 'dummy/dummy-image.jpg';

        // Students (20 students across classes)
        $students = [
            ['Aarav',    'Sharma',    '2014-03-12', 'Male',   'B+',  '2024-04-01', 'ADM-2024-001'],
            ['Ananya',   'Patel',     '2013-07-25', 'Female', 'O+',  '2024-04-01', 'ADM-2024-002'],
            ['Rohan',    'Gupta',     '2014-01-08', 'Male',   'A+',  '2024-04-01', 'ADM-2024-003'],
            ['Ishaan',   'Verma',     '2013-11-14', 'Male',   'AB+', '2024-04-01', 'ADM-2024-004'],
            ['Priya',    'Nair',      '2013-05-30', 'Female', 'B-',  '2024-04-01', 'ADM-2024-005'],
            ['Aryan',    'Singh',     '2012-09-18', 'Male',   'O+',  '2024-04-01', 'ADM-2024-006'],
            ['Kavya',    'Reddy',     '2012-04-22', 'Female', 'A+',  '2024-04-01', 'ADM-2024-007'],
            ['Dev',      'Joshi',     '2012-12-03', 'Male',   'B+',  '2024-04-01', 'ADM-2024-008'],
            ['Riya',     'Mehta',     '2011-08-16', 'Female', 'O-',  '2024-04-01', 'ADM-2024-009'],
            ['Kabir',    'Malhotra',  '2011-02-27', 'Male',   'A-',  '2024-04-01', 'ADM-2024-010'],
            ['Aadhya',   'Khanna',    '2010-06-10', 'Female', 'B+',  '2024-04-01', 'ADM-2024-011'],
            ['Vivaan',   'Bose',      '2010-10-05', 'Male',   'O+',  '2024-04-01', 'ADM-2024-012'],
            ['Diya',     'Iyer',      '2009-01-19', 'Female', 'AB-', '2024-04-01', 'ADM-2024-013'],
            ['Siddharth','Roy',       '2009-07-31', 'Male',   'A+',  '2024-04-01', 'ADM-2024-014'],
            ['Myra',     'Das',       '2013-04-15', 'Female', 'B+',  '2024-04-01', 'ADM-2024-015'],
            ['Rehan',    'Sheikh',    '2013-08-09', 'Male',   'O+',  '2024-04-01', 'ADM-2024-016'],
            ['Sneha',    'Pillai',    '2012-11-28', 'Female', 'A-',  '2024-04-01', 'ADM-2024-017'],
            ['Dhruv',    'Agarwal',   '2012-03-06', 'Male',   'B-',  '2024-04-01', 'ADM-2024-018'],
            ['Tanvi',    'Chaudhary', '2011-09-22', 'Female', 'O+',  '2024-04-01', 'ADM-2024-019'],
            ['Mihir',    'Shah',      '2010-05-13', 'Male',   'AB+', '2024-04-01', 'ADM-2024-020'],
        ];

        foreach ($students as $i => [$first, $last, $dob, $gender, $blood, $admission, $admNo]) {
            DB::table('students')->insert([
                'first_name'        => $first,
                'last_name'         => $last,
                'date_of_birth'     => $dob,
                'gender'            => $gender,
                'blood_group'       => $blood,
                'nationality'       => 'Indian',
                'religion'          => 'Hindu',
                'current_address'   => ($i + 1).', Student Colony, City, India',
                'permanent_address' => ($i + 1).', Student Colony, City, India',
                'phone_number'      => '9800000'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'email'             => strtolower($first.'.'.$last).'@student.school.com',
                'profile_photo'     => $dummyImage,
                'date_of_admission' => $admission,
                'admission_number'  => $admNo,
                'status'            => 'Active',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }

        // Parents (one per student)
        $parentData = [
            [1,  'Ravi Sharma',    '9810000001', 'ravi.sharma@email.com',    'Engineer',    'Sunita Sharma',   '9810000002', 'sunita.sharma@email.com',   'Teacher'],
            [2,  'Manoj Patel',    '9810000003', 'manoj.patel@email.com',    'Businessman', 'Geeta Patel',     '9810000004', 'geeta.patel@email.com',     'Homemaker'],
            [3,  'Sunil Gupta',    '9810000005', 'sunil.gupta@email.com',    'Doctor',      'Rekha Gupta',     '9810000006', 'rekha.gupta@email.com',     'Nurse'],
            [4,  'Ajay Verma',     '9810000007', 'ajay.verma@email.com',     'Lawyer',      'Pooja Verma',     '9810000008', 'pooja.verma@email.com',     'Teacher'],
            [5,  'Vinod Nair',     '9810000009', 'vinod.nair@email.com',     'Manager',     'Meena Nair',      '9810000010', 'meena.nair@email.com',      'Homemaker'],
            [6,  'Rajiv Singh',    '9810000011', 'rajiv.singh@email.com',    'Officer',     'Anita Singh',     '9810000012', 'anita.singh@email.com',     'Accountant'],
            [7,  'Kumar Reddy',    '9810000013', 'kumar.reddy@email.com',    'Engineer',    'Latha Reddy',     '9810000014', 'latha.reddy@email.com',     'Doctor'],
            [8,  'Harish Joshi',   '9810000015', 'harish.joshi@email.com',   'Professor',   'Nisha Joshi',     '9810000016', 'nisha.joshi@email.com',     'Lecturer'],
            [9,  'Pankaj Mehta',   '9810000017', 'pankaj.mehta@email.com',   'Architect',   'Sweta Mehta',     '9810000018', 'sweta.mehta@email.com',     'Designer'],
            [10, 'Sanjay Malhotra','9810000019', 'sanjay.m@email.com',       'Banker',      'Kaveri Malhotra', '9810000020', 'kaveri.m@email.com',        'Homemaker'],
            [11, 'Ashok Khanna',   '9810000021', 'ashok.khanna@email.com',   'Pilot',       'Neeta Khanna',    '9810000022', 'neeta.khanna@email.com',    'Air Hostess'],
            [12, 'Biplab Bose',    '9810000023', 'biplab.bose@email.com',    'Writer',      'Mitali Bose',     '9810000024', 'mitali.bose@email.com',     'Artist'],
            [13, 'Shankar Iyer',   '9810000025', 'shankar.iyer@email.com',   'CA',          'Radha Iyer',      '9810000026', 'radha.iyer@email.com',      'Homemaker'],
            [14, 'Tapan Roy',      '9810000027', 'tapan.roy@email.com',      'Scientist',   'Priti Roy',       '9810000028', 'priti.roy@email.com',       'Researcher'],
            [15, 'Imran Sheikh',   '9810000029', 'imran.sheikh@email.com',   'Trader',      'Fatima Sheikh',   '9810000030', 'fatima.sheikh@email.com',   'Teacher'],
            [16, 'Suresh Pillai',  '9810000031', 'suresh.pillai@email.com',  'Engineer',    'Sindhu Pillai',   '9810000032', 'sindhu.pillai@email.com',   'Nurse'],
            [17, 'Ramesh Agarwal', '9810000033', 'ramesh.agarwal@email.com', 'Businessman', 'Seema Agarwal',   '9810000034', 'seema.agarwal@email.com',   'Homemaker'],
            [18, 'Naresh Chaudhary','9810000035','naresh.c@email.com',       'Farmer',      'Sarla Chaudhary', '9810000036', 'sarla.c@email.com',         'Teacher'],
            [19, 'Dinesh Shah',    '9810000037', 'dinesh.shah@email.com',    'Jeweller',    'Hina Shah',       '9810000038', 'hina.shah@email.com',       'Homemaker'],
            [20, 'Pramod Das',     '9810000039', 'pramod.das@email.com',     'IAS Officer', 'Renu Das',        '9810000040', 'renu.das@email.com',        'Homemaker'],
        ];

        foreach ($parentData as [$studentId, $fName, $fPhone, $fEmail, $fOcc, $mName, $mPhone, $mEmail, $mOcc]) {
            DB::table('parents')->insert([
                'student_id'         => $studentId,
                'father_name'        => $fName,
                'father_phone'       => $fPhone,
                'father_email'       => $fEmail,
                'father_occupation'  => $fOcc,
                'mother_name'        => $mName,
                'mother_phone'       => $mPhone,
                'mother_email'       => $mEmail,
                'mother_occupation'  => $mOcc,
                'guardian_name'      => null,
                'guardian_relationship' => null,
                'guardian_phone'     => null,
                'guardian_address'   => null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // Student Enrollments
        // Students 1-2 → batch 1 (Class 1A), 3-4 → batch 2 (Class 1B)
        // Students 5-6 → batch 3 (Class 2A), 7-8 → batch 4 (Class 2B)
        // Students 9-10 → batch 5 (Class 3A), 11-12 → batch 6 (Class 3B)
        // Students 13-14 → batch 7 (Class 4A), 15-16 → batch 8 (Class 4B)
        // Students 17-18 → batch 9 (Class 5A), 19-20 → batch 10 (Class 5B)
        $enrollments = [
            [1,  1],  [2,  1],
            [3,  2],  [4,  2],
            [5,  3],  [6,  3],
            [7,  4],  [8,  4],
            [9,  5],  [10, 5],
            [11, 6],  [12, 6],
            [13, 7],  [14, 7],
            [15, 8],  [16, 8],
            [17, 9],  [18, 9],
            [19, 10], [20, 10],
        ];

        foreach ($enrollments as [$studentId, $batchId]) {
            DB::table('student_enrollments')->insert([
                'student_id'       => $studentId,
                'batch_id'         => $batchId,
                'academic_year_id' => 1,
                'enrollment_date'  => '2024-04-01',
                'status'           => 'Active',
                'remarks'          => 'Regular enrollment for academic year 2024-25',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // Previous Educations
        $prevEducations = [
            [1, 'Sunrise Public School', 'CBSE', 'KG', 95.00, 2023],
            [2, 'Greenfield Academy',    'CBSE', 'KG', 88.50, 2023],
            [5, 'Holy Cross School',     'ICSE', 'Class 1', 90.00, 2023],
            [9, 'St. Mary\'s School',   'CBSE', 'Class 2', 85.00, 2023],
            [13, 'Delhi Public School',  'CBSE', 'Class 3', 92.00, 2023],
        ];

        foreach ($prevEducations as [$studentId, $school, $board, $class, $pct, $year]) {
            DB::table('previous_educations')->insert([
                'student_id'      => $studentId,
                'school_name'     => $school,
                'board'           => $board,
                'class_completed' => $class,
                'percentage'      => $pct,
                'year_of_passing' => $year,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // Health Records
        foreach (range(1, 10) as $studentId) {
            DB::table('health_records')->insert([
                'student_id'         => $studentId,
                'height_cm'          => round(110 + $studentId * 3.5, 2),
                'weight_kg'          => round(22 + $studentId * 1.5, 2),
                'blood_group'        => ['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-', 'A+', 'B+'][$studentId - 1],
                'allergies'          => $studentId % 3 === 0 ? 'Dust, Pollen' : null,
                'medical_conditions' => null,
                'vaccination_status' => 'Complete',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // Student Documents
        $documentTypes = ['birth_certificate', 'photograph', 'id_proof', 'address_proof'];
        foreach (range(1, 5) as $studentId) {
            DB::table('student_documents')->insert([
                'student_id'    => $studentId,
                'document_type' => $documentTypes[$studentId % 4],
                'document_name' => ucfirst($documentTypes[$studentId % 4]).' - Student '.$studentId,
                'file_path'     => 'dummy/dummy-image.jpg',
                'file_size'     => 204800,
                'mime_type'     => 'image/jpeg',
                'uploaded_by'   => 1,
                'notes'         => 'Uploaded during admission',
                'expiry_date'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // Student Contacts
        foreach (range(1, 10) as $studentId) {
            DB::table('student_contacts')->insert([
                'student_id'    => $studentId,
                'contact_type'  => 'mobile',
                'contact_value' => '9900000'.str_pad($studentId, 3, '0', STR_PAD_LEFT),
                'is_primary'    => true,
                'label'         => 'Parent Mobile',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
