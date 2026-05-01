<?php

use App\Packages\ClassManagement\Controllers\AcademicYearController;
use App\Packages\ClassManagement\Controllers\BatchController;
use App\Packages\ClassManagement\Controllers\ClassController;
use App\Packages\ClassManagement\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-classes')->group(function () {
    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('classes/{schoolClass}', [ClassController::class, 'show'])->name('classes.show');
    Route::get('sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('sections/{section}', [SectionController::class, 'show'])->name('sections.show');
    Route::get('batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('batches/{batch}', [BatchController::class, 'show'])->name('batches.show');
    Route::get('academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
    Route::get('academic-years/{academic_year}', [AcademicYearController::class, 'show'])->name('academic-years.show');
});

Route::middleware('permission:create-classes')->group(function () {
    Route::get('classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('sections/create', [SectionController::class, 'create'])->name('sections.create');
    Route::post('sections', [SectionController::class, 'store'])->name('sections.store');
    Route::get('batches/create', [BatchController::class, 'create'])->name('batches.create');
    Route::post('batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('academic-years/create', [AcademicYearController::class, 'create'])->name('academic-years.create');
    Route::post('academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
});

Route::middleware('permission:edit-classes')->group(function () {
    Route::get('classes/{schoolClass}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('classes/{schoolClass}', [ClassController::class, 'update'])->name('classes.update');
    Route::patch('classes/{schoolClass}', [ClassController::class, 'update']);
    Route::get('sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
    Route::put('sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::patch('sections/{section}', [SectionController::class, 'update']);
    Route::get('batches/{batch}/edit', [BatchController::class, 'edit'])->name('batches.edit');
    Route::put('batches/{batch}', [BatchController::class, 'update'])->name('batches.update');
    Route::patch('batches/{batch}', [BatchController::class, 'update']);
    Route::get('academic-years/{academic_year}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
    Route::put('academic-years/{academic_year}', [AcademicYearController::class, 'update'])->name('academic-years.update');
    Route::patch('academic-years/{academic_year}', [AcademicYearController::class, 'update']);
});

Route::middleware('permission:delete-classes')->group(function () {
    Route::delete('classes/{schoolClass}', [ClassController::class, 'destroy'])->name('classes.destroy');
    Route::delete('sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::delete('batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');
    Route::delete('academic-years/{academic_year}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');
});
