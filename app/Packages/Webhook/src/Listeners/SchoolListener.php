<?php

namespace App\Packages\Webhook\Listeners;

use App\Packages\Webhook\Services\WebhookService;

class SchoolListener
{
    public function __construct(protected WebhookService $webhookService) {}

    public function onStudentCreated(array $data): void
    {
        $this->webhookService->dispatch(
            'student.created',
            'student',
            $data['student_id'] ?? 0,
            $data,
        );
    }

    public function onStudentUpdated(array $data): void
    {
        $this->webhookService->dispatch(
            'student.updated',
            'student',
            $data['student_id'] ?? 0,
            $data,
        );
    }

    public function onStudentEnrolled(array $data): void
    {
        $this->webhookService->dispatch(
            'student.enrolled',
            'student',
            $data['student_id'] ?? 0,
            $data,
        );
    }

    public function onStaffCreated(array $data): void
    {
        $this->webhookService->dispatch(
            'staff.created',
            'staff',
            $data['staff_id'] ?? 0,
            $data,
        );
    }

    public function onStaffUpdated(array $data): void
    {
        $this->webhookService->dispatch(
            'staff.updated',
            'staff',
            $data['staff_id'] ?? 0,
            $data,
        );
    }

    public function onAttendanceMarked(array $data): void
    {
        $this->webhookService->dispatch(
            'attendance.marked',
            $data['type'] ?? 'student',
            $data['id'] ?? 0,
            $data,
        );
    }

    public function onFeePaid(array $data): void
    {
        $this->webhookService->dispatch(
            'fee.paid',
            'fee_payment',
            $data['id'] ?? 0,
            $data,
        );
    }

    public function onExamResult(array $data): void
    {
        $this->webhookService->dispatch(
            'exam.result',
            'student_mark',
            $data['id'] ?? 0,
            $data,
        );
    }
}
