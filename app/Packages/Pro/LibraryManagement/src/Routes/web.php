<?php

use App\Packages\Pro\LibraryManagement\Controllers\BookController;
use App\Packages\Pro\LibraryManagement\Controllers\MemberController;
use App\Packages\Pro\LibraryManagement\Controllers\IssueController;
use App\Packages\Pro\LibraryManagement\Controllers\FineController;
use App\Packages\Pro\LibraryManagement\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('library')->group(function () {

    // Setup wizard
    Route::get('/setup', [SetupController::class, 'showSetup'])->name('library.setup');
    Route::post('/setup', [SetupController::class, 'runSetup'])->name('library.setup.run');

    Route::middleware('permission:view_library-management')->group(function () {
        // Books
        Route::get('/books', [BookController::class, 'index'])->name('library.books.index');
        Route::get('/books/search', [BookController::class, 'search'])->name('library.books.search');

        // Members
        Route::get('/members', [MemberController::class, 'index'])->name('library.members.index');
        Route::get('/members/{member}', [MemberController::class, 'show'])->name('library.members.show');

        // Issues
        Route::get('/issues', [IssueController::class, 'index'])->name('library.issues.index');
        Route::get('/issues/overdue', [IssueController::class, 'overdue'])->name('library.issues.overdue');
        Route::get('/issues/member/{member}', [IssueController::class, 'memberIssues'])->name('library.issues.member');

        // Fines
        Route::get('/fines', [FineController::class, 'index'])->name('library.fines.index');
        Route::get('/fines/pending', [FineController::class, 'pending'])->name('library.fines.pending');
        Route::get('/fines/report', [FineController::class, 'report'])->name('library.fines.report');
    });

    Route::middleware('permission:create_library-management_item')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('library.books.create');
        Route::post('/books', [BookController::class, 'store'])->name('library.books.store');
        Route::get('/members/create', [MemberController::class, 'create'])->name('library.members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('library.members.store');
        Route::get('/issues/create', [IssueController::class, 'create'])->name('library.issues.create');
        Route::post('/issues', [IssueController::class, 'store'])->name('library.issues.store');
    });

    Route::middleware('permission:edit_library-management_item')->group(function () {
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('library.books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('library.books.update');
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('library.members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('library.members.update');
        Route::post('/issues/{issue}/return', [IssueController::class, 'returnBook'])->name('library.issues.return');
    });

    Route::middleware('permission:manage_library_fines')->group(function () {
        Route::post('/fines/{fine}/pay', [FineController::class, 'recordPayment'])->name('library.fines.pay');
        Route::post('/fines/{fine}/waive', [FineController::class, 'waive'])->name('library.fines.waive');
    });

    Route::middleware('permission:delete_library-management_item')->group(function () {
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('library.books.destroy');
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('library.members.destroy');
    });
});
