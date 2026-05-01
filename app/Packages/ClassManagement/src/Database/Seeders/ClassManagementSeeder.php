<?php

namespace App\Packages\ClassManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassManagementSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Academic Years
        DB::table('academic_years')->insert([
            [
                'year_range'  => '2024-2025',
                'start_date'  => '2024-04-01',
                'end_date'    => '2025-03-31',
                'is_current'  => true,
                'description' => 'Current academic year',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'year_range'  => '2023-2024',
                'start_date'  => '2023-04-01',
                'end_date'    => '2024-03-31',
                'is_current'  => false,
                'description' => 'Previous academic year',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        // Classes
        $classes = [
            ['Class 1', 'CLS-01'],
            ['Class 2', 'CLS-02'],
            ['Class 3', 'CLS-03'],
            ['Class 4', 'CLS-04'],
            ['Class 5', 'CLS-05'],
        ];

        foreach ($classes as [$name, $code]) {
            DB::table('classes')->insert([
                'class_name'       => $name,
                'class_code'       => $code,
                'academic_year_id' => 1,
                'description'      => "Standard {$name} curriculum",
                'is_active'        => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // Sections — class_teacher_id maps to staff IDs 1-10
        $sections = [
            [1, 'A', 30, 1],
            [1, 'B', 30, 2],
            [2, 'A', 35, 3],
            [2, 'B', 35, 4],
            [3, 'A', 32, 5],
            [3, 'B', 32, 6],
            [4, 'A', 30, 7],
            [4, 'B', 30, 8],
            [5, 'A', 28, 9],
            [5, 'B', 28, 10],
        ];

        foreach ($sections as [$classId, $sectionName, $capacity, $teacherId]) {
            DB::table('sections')->insert([
                'class_id'         => $classId,
                'section_name'     => $sectionName,
                'capacity'         => $capacity,
                'class_teacher_id' => $teacherId,
                'description'      => "Section {$sectionName} of Class {$classId}",
                'is_active'        => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // Batches (one per class-section-academic year combination)
        // section_ids 1-10 correspond to [class1-A, class1-B, class2-A, ...]
        $batchMap = [
            [1, 1, 1, 'Batch 2024-25 Class 1 A', 'BCH-01'],
            [1, 2, 1, 'Batch 2024-25 Class 1 B', 'BCH-02'],
            [2, 3, 1, 'Batch 2024-25 Class 2 A', 'BCH-03'],
            [2, 4, 1, 'Batch 2024-25 Class 2 B', 'BCH-04'],
            [3, 5, 1, 'Batch 2024-25 Class 3 A', 'BCH-05'],
            [3, 6, 1, 'Batch 2024-25 Class 3 B', 'BCH-06'],
            [4, 7, 1, 'Batch 2024-25 Class 4 A', 'BCH-07'],
            [4, 8, 1, 'Batch 2024-25 Class 4 B', 'BCH-08'],
            [5, 9, 1, 'Batch 2024-25 Class 5 A', 'BCH-09'],
            [5, 10, 1, 'Batch 2024-25 Class 5 B', 'BCH-10'],
        ];

        foreach ($batchMap as [$classId, $sectionId, $ayId, $batchName, $batchCode]) {
            DB::table('batches')->insert([
                'class_id'         => $classId,
                'section_id'       => $sectionId,
                'academic_year_id' => $ayId,
                'batch_name'       => $batchName,
                'batch_code'       => $batchCode,
                'start_date'       => '2024-04-01',
                'end_date'         => '2025-03-31',
                'start_time'       => '08:00:00',
                'end_time'         => '14:00:00',
                'is_active'        => true,
                'description'      => "{$batchName}",
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }
}
