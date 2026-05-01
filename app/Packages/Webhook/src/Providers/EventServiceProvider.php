<?php

namespace App\Packages\Webhook\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Student lifecycle
        Event::listen('webhook.student.created',   [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onStudentCreated']);
        Event::listen('webhook.student.updated',   [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onStudentUpdated']);

        // Staff lifecycle
        Event::listen('webhook.staff.created',     [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onStaffCreated']);
        Event::listen('webhook.staff.updated',     [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onStaffUpdated']);

        // Attendance
        Event::listen('webhook.attendance.marked', [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onAttendanceMarked']);

        // Fee payments
        Event::listen('webhook.fee.paid',          [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onFeePaid']);

        // Exam results
        Event::listen('webhook.exam.result',       [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onExamResult']);

        // Enrollment
        Event::listen('webhook.student.enrolled',  [\App\Packages\Webhook\Listeners\SchoolListener::class, 'onStudentEnrolled']);
    }
}
