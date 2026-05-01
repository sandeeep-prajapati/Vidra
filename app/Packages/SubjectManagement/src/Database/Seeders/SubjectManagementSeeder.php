<?php

namespace App\Packages\SubjectManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Subjects
        $subjects = [
            ['Mathematics',      'MATH-01', 'Theory',    false],
            ['English',          'ENG-01',  'Theory',    false],
            ['Science',          'SCI-01',  'Theory',    false],
            ['Hindi',            'HIN-01',  'Theory',    false],
            ['Social Studies',   'SS-01',   'Theory',    false],
            ['Computer Science', 'CS-01',   'Practical', false],
            ['Physical Education','PE-01',  'Practical', false],
            ['Art & Craft',      'ART-01',  'Practical', true],
            ['Music',            'MUS-01',  'Practical', true],
            ['General Knowledge','GK-01',   'Theory',    false],
        ];

        foreach ($subjects as [$name, $code, $type, $optional]) {
            DB::table('subjects')->insert([
                'subject_name' => $name,
                'subject_code' => $code,
                'subject_type' => $type,
                'description'  => "Standard {$name} curriculum",
                'is_optional'  => $optional,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // Class Subjects — assign subjects 1-7 to each class (mandatory), 8-9 optional
        $mandatorySubjects = [1, 2, 3, 4, 5, 6, 7];
        $optionalSubjects  = [8, 9, 10];

        foreach (range(1, 5) as $classId) {
            foreach ($mandatorySubjects as $subjectId) {
                DB::table('class_subjects')->insert([
                    'class_id'     => $classId,
                    'subject_id'   => $subjectId,
                    'is_mandatory' => true,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
            foreach ($optionalSubjects as $subjectId) {
                DB::table('class_subjects')->insert([
                    'class_id'     => $classId,
                    'subject_id'   => $subjectId,
                    'is_mandatory' => false,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        // Teacher Subject Mappings
        // staff_id 1-8 assigned to classes 1-5 with subjects
        $mappings = [
            [1, 1, 1, 1],  // Rajesh  → Class 1, Section 1, Math
            [2, 1, 3, 1],  // Priya   → Class 1, Section 1, Science
            [3, 1, 2, 1],  // Anjali  → Class 1, Section 1, English
            [4, 2, 4, 3],  // Vikas   → Class 2, Section 3, Hindi
            [5, 2, 3, 3],  // Sunita  → Class 2, Section 3, Science
            [6, 3, 7, 5],  // Mohan   → Class 3, Section 5, PE
            [7, 3, 5, 5],  // Kavita  → Class 3, Section 5, Social Studies
            [8, 4, 6, 7],  // Arjun   → Class 4, Section 7, Computer
            [1, 5, 1, 9],  // Rajesh  → Class 5, Section 9, Math
            [3, 4, 2, 7],  // Anjali  → Class 4, Section 7, English
        ];

        foreach ($mappings as [$teacherId, $classId, $subjectId, $sectionId]) {
            DB::table('teacher_subject_mappings')->insertOrIgnore([
                'teacher_id' => $teacherId,
                'class_id'   => $classId,
                'subject_id' => $subjectId,
                'section_id' => $sectionId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Curriculums (one per subject-academic year)
        foreach (range(1, 10) as $subjectId) {
            DB::table('curriculums')->insertOrIgnore([
                'subject_id'             => $subjectId,
                'academic_year_id'       => 1,
                'description'            => "Curriculum plan for subject {$subjectId} for 2024-25",
                'syllabus_document_path' => 'dummy/dummy-image.jpg',
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);
        }

        // Lesson Plans (2 per curriculum)
        foreach (range(1, 10) as $curriculumId) {
            DB::table('lesson_plans')->insert([
                [
                    'curriculum_id' => $curriculumId,
                    'topic_name'    => "Unit 1 — Introduction",
                    'objectives'    => "Students will understand the basic concepts",
                    'start_date'    => '2024-04-05',
                    'end_date'      => '2024-04-30',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
                [
                    'curriculum_id' => $curriculumId,
                    'topic_name'    => "Unit 2 — Core Concepts",
                    'objectives'    => "Students will apply learned concepts",
                    'start_date'    => '2024-05-01',
                    'end_date'      => '2024-05-31',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ],
            ]);
        }

        // Textbooks (one per subject)
        $textbooks = [
            [1, 'Mathematics Class Book',     'R.D. Sharma',     'S. Chand',      '10th', '978-81-219-0001-1'],
            [2, 'English Reader',              'NCERT',           'NCERT',         '5th',  '978-81-700-0002-2'],
            [3, 'Science Textbook',            'NCERT',           'NCERT',         '6th',  '978-81-700-0003-3'],
            [4, 'Hindi Pathmala',              'Dr. Anita Gupta', 'Bharti Bhawan', '4th',  '978-81-219-0004-4'],
            [5, 'Social Science Today',        'Oxford Press',    'Oxford',        '3rd',  '978-01-955-0005-5'],
            [6, 'Computer Science Basics',     'Sumita Arora',    'Dhanpat Rai',   '8th',  '978-81-219-0006-6'],
            [7, 'Physical Education Manual',   'Sports Authority', 'Govt. Press',  '2nd',  '978-81-700-0007-7'],
            [8, 'Art & Craft Activities',      'Mrs. K. Sharma',  'Frank Bros',    '1st',  '978-81-219-0008-8'],
            [9, 'Music Theory & Practice',     'Pt. V. Shastri',  'Sangeet Press', '3rd',  '978-81-700-0009-9'],
            [10,'General Knowledge Today',     'Manohar Pandey',  'Arihant',       '5th',  '978-93-512-0010-0'],
        ];

        foreach ($textbooks as [$subjectId, $title, $author, $publisher, $edition, $isbn]) {
            DB::table('textbooks')->insert([
                'subject_id'        => $subjectId,
                'title'             => $title,
                'author'            => $author,
                'publisher'         => $publisher,
                'edition'           => $edition,
                'isbn'              => $isbn,
                'textbook_file_path'=> 'dummy/dummy-image.jpg',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }
    }
}
