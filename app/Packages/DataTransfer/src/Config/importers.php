<?php

return [
    'student' => [
        'title'    => 'Students',
        'importer' => \App\Packages\DataTransfer\Helpers\Importers\Student\Importer::class,
    ],
    'staff' => [
        'title'    => 'Staff',
        'importer' => \App\Packages\DataTransfer\Helpers\Importers\Staff\Importer::class,
    ],
    'salary' => [
        'title'    => 'Salary Details',
        'importer' => \App\Packages\DataTransfer\Helpers\Importers\Salary\Importer::class,
    ],
    'student_fee' => [
        'title'    => 'Student Fees',
        'importer' => \App\Packages\DataTransfer\Helpers\Importers\StudentFee\Importer::class,
    ],
];
