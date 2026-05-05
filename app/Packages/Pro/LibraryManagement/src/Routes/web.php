<?php

use App\Packages\Pro\LibraryManagement\Controllers\BookController;
use App\Packages\Pro\LibraryManagement\Controllers\IssueController;
use App\Packages\Pro\LibraryManagement\Controllers\FineController;
use App\Packages\Pro\LibraryManagement\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('library')->name('library-management.')->group(function () {
        // Books
        Route::resource('books', BookController::class)
            ->middleware('permission:view_library-management');

        Route::get('books/search', [BookController::class, 'search'])
            ->name('books.search')
            ->middleware('permission:view_library-management');

        // Members
        Route::resource('members', MemberController::class)
            ->middleware('permission:view_library-management');

        // Issues
        Route::resource('issues', IssueController::class)
            ->middleware('permission:view_library-management');

        Route::post('issues/{issue}/return', [IssueController::class, 'return'])
            ->name('issues.return')
            ->middleware('permission:edit_library-management_item');

        Route::get('issues/overdue', [IssueController::class, 'overdue'])
            ->name('issues.overdue')
            ->middleware('permission:view_library-management');

        Route::post('issues/mark-overdue', [IssueController::class, 'markOverdue'])
            ->name('issues.mark-overdue')
            ->middleware('permission:edit_library-management_item');

        // Fines
        Route::resource('fines', FineController::class)
            ->only(['index', 'show'])
            ->middleware('permission:view_library-management');

        Route::get('fines/pending', [FineController::class, 'pending'])
            ->name('fines.pending')
            ->middleware('permission:view_library-management');

        Route::post('fines/{fine}/payment', [FineController::class, 'recordPayment'])
            ->name('fines.payment')
            ->middleware('permission:edit_library-management_item');

        Route::post('fines/{fine}/waive', [FineController::class, 'waive'])
            ->name('fines.waive')
            ->middleware('permission:edit_library-management_item');

        Route::get('fines/report', [FineController::class, 'report'])
            ->name('fines.report')
            ->middleware('permission:view_library-management');
    });
});
