<?php

use App\Packages\StudentManagement\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:create-students')->group(function () {
    Route::get('students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('students', [StudentController::class, 'store'])->name('students.store');
    Route::post('students/{student}/parent', [StudentController::class, 'storeParent'])->name('students.parent.store');
    Route::post('students/{student}/education', [StudentController::class, 'storeEducation'])->name('students.education.store');
    Route::post('students/{student}/health', [StudentController::class, 'storeHealthRecord'])->name('students.health.store');
    Route::post('students/{student}/enroll', [StudentController::class, 'enrollStudent'])->name('students.enroll.store');
    Route::post('students/{student}/promote', [StudentController::class, 'promoteStudent'])->name('students.promote.store');
    Route::post('students/{student}/documents', [StudentController::class, 'storeDocument'])->name('students.documents.store');
    Route::post('students/{student}/contacts', [StudentController::class, 'storeContact'])->name('students.contacts.store');
});

Route::middleware('permission:view-students')->group(function () {
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('students/{student}/health', [StudentController::class, 'indexHealthRecords'])->name('students.health.index');
    Route::get('students/{student}/documents', [StudentController::class, 'indexDocuments'])->name('students.documents.index');
    Route::get('students/{student}/activities', [StudentController::class, 'indexActivityLogs'])->name('students.activities.index');
    Route::get('students/{student}/contacts', [StudentController::class, 'indexContacts'])->name('students.contacts.index');
});

Route::middleware('permission:edit-students')->group(function () {
    Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::patch('students/{student}', [StudentController::class, 'update']);
    Route::put('students/{student}/parent', [StudentController::class, 'updateParent'])->name('students.parent.update');
    Route::put('students/{student}/education/{education}', [StudentController::class, 'updateEducation'])->name('students.education.update');
    Route::put('students/{student}/health/{healthRecord}', [StudentController::class, 'updateHealthRecord'])->name('students.health.update');
    Route::put('students/{student}/enrollment/{enrollment}', [StudentController::class, 'updateEnrollment'])->name('students.enrollment.update');
    Route::put('students/{student}/document/{document}', [StudentController::class, 'updateDocument'])->name('students.documents.update');
    Route::put('students/{student}/contact/{contact}', [StudentController::class, 'updateContact'])->name('students.contacts.update');
});

Route::middleware('permission:delete-students')->group(function () {
    Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::delete('students/{student}/education/{education}', [StudentController::class, 'destroyEducation'])->name('students.education.destroy');
    Route::delete('students/{student}/health/{healthRecord}', [StudentController::class, 'destroyHealthRecord'])->name('students.health.destroy');
    Route::delete('students/{student}/enrollment/{enrollment}', [StudentController::class, 'destroyEnrollment'])->name('students.enrollment.destroy');
    Route::delete('students/{student}/promotion/{promotion}', [StudentController::class, 'destroyPromotion'])->name('students.promotion.destroy');
    Route::delete('students/{student}/document/{document}', [StudentController::class, 'destroyDocument'])->name('students.documents.destroy');
    Route::delete('students/{student}/contact/{contact}', [StudentController::class, 'destroyContact'])->name('students.contacts.destroy');
});
