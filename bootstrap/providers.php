<?php

use App\Packages\AttendanceManagement\Providers\AttendanceManagementServiceProvider;
use App\Packages\ClassManagement\Providers\ClassManagementServiceProvider;
use App\Packages\CommunicationManagement\Providers\CommunicationManagementServiceProvider;
use App\Packages\CorePackage\Providers\CorePackageServiceProvider;
use App\Packages\ExamManagement\Providers\ExamManagementServiceProvider;
use App\Packages\FeeManagement\Providers\FeeManagementServiceProvider;
use App\Packages\HostelTransportManagement\Providers\HostelTransportManagementServiceProvider;
use App\Packages\RbacManagement\Providers\RbacManagementServiceProvider;
use App\Packages\StaffManagement\Providers\StaffManagementServiceProvider;
use App\Packages\StudentManagement\Providers\StudentManagementServiceProvider;
use App\Packages\SubjectManagement\Providers\SubjectManagementServiceProvider;
use App\Packages\TimetableManagement\Providers\TimetableManagementServiceProvider;
use App\Packages\DataTransfer\Providers\DataTransferServiceProvider;
use App\Packages\Installer\Providers\InstallerServiceProvider;
use App\Packages\Webhook\Providers\WebhookServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    InstallerServiceProvider::class,
    DataTransferServiceProvider::class,
    CorePackageServiceProvider::class,
    StudentManagementServiceProvider::class,
    StaffManagementServiceProvider::class,
    ClassManagementServiceProvider::class,
    SubjectManagementServiceProvider::class,
    AttendanceManagementServiceProvider::class,
    ExamManagementServiceProvider::class,
    FeeManagementServiceProvider::class,
    TimetableManagementServiceProvider::class,
    CommunicationManagementServiceProvider::class,
    RbacManagementServiceProvider::class,
    HostelTransportManagementServiceProvider::class,
    WebhookServiceProvider::class,
];
