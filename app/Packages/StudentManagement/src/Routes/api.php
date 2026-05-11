<?php

use App\Packages\StudentManagement\Controllers\Api\StudentApiController;
use Illuminate\Support\Facades\Route;

// Search endpoint — must be declared before apiResource to avoid conflict with {student}
Route::get('students/search', [StudentApiController::class, 'search'])->name('api.students.search');

// API routes
Route::apiResource('students', StudentApiController::class, [
    'names' => [
        'index' => 'api.students.index',
        'store' => 'api.students.store',
        'show' => 'api.students.show',
        'update' => 'api.students.update',
        'destroy' => 'api.students.destroy',
    ]
]);

// Parent info routes (API)
Route::post('students/{student}/parent', [StudentApiController::class, 'storeParent']);
Route::put('students/{student}/parent', [StudentApiController::class, 'updateParent']);

// Previous education routes (API)
Route::post('students/{student}/education', [StudentApiController::class, 'storeEducation']);
Route::put('students/{student}/education/{education}', [StudentApiController::class, 'updateEducation']);
Route::delete('students/{student}/education/{education}', [StudentApiController::class, 'destroyEducation']);

// Health record routes (API)
Route::get('students/{student}/health', [StudentApiController::class, 'indexHealthRecords']);
Route::post('students/{student}/health', [StudentApiController::class, 'storeHealthRecord']);
Route::put('students/{student}/health/{healthRecord}', [StudentApiController::class, 'updateHealthRecord']);
Route::delete('students/{student}/health/{healthRecord}', [StudentApiController::class, 'destroyHealthRecord']);

// Enrollment routes (API)
Route::post('students/{student}/enroll', [StudentApiController::class, 'enrollStudent']);
Route::put('students/{student}/enrollment/{enrollment}', [StudentApiController::class, 'updateEnrollment']);
Route::delete('students/{student}/enrollment/{enrollment}', [StudentApiController::class, 'destroyEnrollment']);
Route::get('students/{student}/enrollments', [StudentApiController::class, 'indexEnrollments']);

// Promotion routes (API)
Route::post('students/{student}/promote', [StudentApiController::class, 'promoteStudent']);
Route::get('students/{student}/promotions', [StudentApiController::class, 'indexPromotions']);
Route::delete('students/{student}/promotion/{promotion}', [StudentApiController::class, 'destroyPromotion']);

// Document routes (API)
Route::get('students/{student}/documents', [StudentApiController::class, 'indexDocuments']);
Route::post('students/{student}/documents', [StudentApiController::class, 'storeDocument']);
Route::put('students/{student}/document/{document}', [StudentApiController::class, 'updateDocument']);
Route::delete('students/{student}/document/{document}', [StudentApiController::class, 'destroyDocument']);

// Activity log routes (API)
Route::get('students/{student}/activities', [StudentApiController::class, 'indexActivityLogs']);

// Contact routes (API)
Route::get('students/{student}/contacts', [StudentApiController::class, 'indexContacts']);
Route::post('students/{student}/contacts', [StudentApiController::class, 'storeContact']);
Route::put('students/{student}/contact/{contact}', [StudentApiController::class, 'updateContact']);
Route::delete('students/{student}/contact/{contact}', [StudentApiController::class, 'destroyContact']);
