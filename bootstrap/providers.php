<?php

use App\Packages\AttendanceManagement\Providers\AttendanceManagementServiceProvider;
use App\Packages\ClassManagement\Providers\ClassManagementServiceProvider;
use App\Packages\CommunicationManagement\Providers\CommunicationManagementServiceProvider;
use App\Packages\ContactBundle\Providers\ContactBundleServiceProvider;
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
use App\Packages\BundleInstaller\Providers\BundleInstallerServiceProvider;
use App\Packages\Webhook\Providers\WebhookServiceProvider;
use App\Packages\Pro\AnalyticsBundle\Providers\AnalyticsBundleServiceProvider;
use App\Packages\Pro\AdmissionManagement\Providers\AdmissionManagementServiceProvider;
use App\Packages\Pro\AlumniManagement\Providers\AlumniManagementServiceProvider;
use App\Packages\Pro\AuditManagement\Providers\AuditManagementServiceProvider;
use App\Packages\Pro\BehaviorManagement\Providers\BehaviorManagementServiceProvider;
use App\Packages\Pro\EventManagement\Providers\EventManagementServiceProvider;
use App\Packages\Pro\ExtracurricularManagement\Providers\ExtracurricularManagementServiceProvider;
use App\Packages\Pro\GrievanceManagement\Providers\GrievanceManagementServiceProvider;
use App\Packages\Pro\HealthManagement\Providers\HealthManagementServiceProvider;
use App\Packages\Pro\HomeworkManagement\Providers\HomeworkManagementServiceProvider;
use App\Packages\Pro\InventoryManagement\Providers\InventoryManagementServiceProvider;
use App\Packages\Pro\LibraryManagement\Providers\LibraryManagementServiceProvider;
use App\Packages\Pro\PTMManagement\Providers\PTMManagementServiceProvider;
use App\Packages\Pro\ReportsManagement\Providers\ReportsManagementServiceProvider;
use App\Packages\Pro\TransportGpsManagement\Providers\TransportGpsManagementServiceProvider;
// installed pro bundles


use App\Providers\AppServiceProvider;

return [
    AnalyticsBundleServiceProvider::class,
    AdmissionManagementServiceProvider::class,
    AlumniManagementServiceProvider::class,
    AuditManagementServiceProvider::class,
    BehaviorManagementServiceProvider::class,
    EventManagementServiceProvider::class,
    ExtracurricularManagementServiceProvider::class,
    GrievanceManagementServiceProvider::class,
    HealthManagementServiceProvider::class,
    HomeworkManagementServiceProvider::class,
    InventoryManagementServiceProvider::class,
    LibraryManagementServiceProvider::class,
    PTMManagementServiceProvider::class,
    ReportsManagementServiceProvider::class,
    TransportGpsManagementServiceProvider::class,
    AppServiceProvider::class,
    InstallerServiceProvider::class,
    BundleInstallerServiceProvider::class,
    DataTransferServiceProvider::class,
    ContactBundleServiceProvider::class,
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
