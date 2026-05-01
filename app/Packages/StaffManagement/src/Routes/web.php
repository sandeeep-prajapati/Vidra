<?php

use App\Packages\StaffManagement\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-staff')->group(function () {
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/{staff}', [StaffController::class, 'show'])->name('staff.show');
});

Route::middleware('permission:create-staff')->group(function () {
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::post('staff-departments', [StaffController::class, 'storeDepartment'])->name('staff.departments.store');
    Route::post('staff/{staff}/qualifications', [StaffController::class, 'storeQualification'])->name('staff.qualifications.store');
    Route::post('staff/{staff}/teacher-assignments', [StaffController::class, 'storeTeacherAssignment'])->name('staff.teacher-assignments.store');
    Route::post('staff/{staff}/attendance', [StaffController::class, 'storeAttendance'])->name('staff.attendance.store');
    Route::post('staff/{staff}/salary', [StaffController::class, 'storeSalary'])->name('staff.salary.store');
    Route::post('staff/{staff}/reviews', [StaffController::class, 'storeReview'])->name('staff.reviews.store');
    Route::post('staff/{staff}/leave-requests', [StaffController::class, 'storeLeaveRequest'])->name('staff.leave-requests.store');
    Route::post('staff/{staff}/department-assignments', [StaffController::class, 'storeDepartmentAssignment'])->name('staff.department-assignments.store');
});

Route::middleware('permission:edit-staff')->group(function () {
    Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::patch('staff/{staff}', [StaffController::class, 'update']);
});

Route::middleware('permission:delete-staff')->group(function () {
    Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::delete('staff/{staff}/qualifications/{qualification}', [StaffController::class, 'destroyQualification'])->name('staff.qualifications.destroy');
});
