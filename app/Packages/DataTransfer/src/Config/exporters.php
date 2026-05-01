<?php

return [
    'student' => [
        'title'    => 'Students',
        'exporter' => \App\Packages\DataTransfer\Helpers\Exporters\Student\Exporter::class,
    ],
    'staff' => [
        'title'    => 'Staff',
        'exporter' => \App\Packages\DataTransfer\Helpers\Exporters\Staff\Exporter::class,
    ],
    'salary' => [
        'title'    => 'Salary Details',
        'exporter' => \App\Packages\DataTransfer\Helpers\Exporters\Salary\Exporter::class,
    ],
    'student_fee' => [
        'title'    => 'Student Fees',
        'exporter' => \App\Packages\DataTransfer\Helpers\Exporters\StudentFee\Exporter::class,
    ],
];
