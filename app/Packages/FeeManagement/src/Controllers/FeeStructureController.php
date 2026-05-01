<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\FeeManagement\Models\FeeCategory;
use App\Packages\FeeManagement\Models\FeeStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeStructureController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes       = SchoolClass::orderBy('class_name')->get();
        $categories    = FeeCategory::orderBy('category_name')->get();

        $query = FeeStructure::with('schoolClass', 'feeCategory', 'academicYear');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('fee_category_id')) {
            $query->where('fee_category_id', $request->fee_category_id);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('fee-management::fee-structure.index', compact('items', 'academicYears', 'classes', 'categories'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes       = SchoolClass::orderBy('class_name')->get();
        $categories    = FeeCategory::orderBy('category_name')->get();
        return view('fee-management::fee-structure.create', compact('academicYears', 'classes', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer',
            'fee_category_id'  => 'required|integer',
            'amount'           => 'required|numeric|min:0',
            'due_date'         => 'required|date',
            'academic_year_id' => 'required|integer',
        ]);
        FeeStructure::create($validated);
        return redirect()->route('feeStructure.index')->with('success', 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure): View
    {
        $feeStructure->load('schoolClass', 'feeCategory', 'academicYear');
        return view('fee-management::fee-structure.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes       = SchoolClass::orderBy('class_name')->get();
        $categories    = FeeCategory::orderBy('category_name')->get();
        return view('fee-management::fee-structure.edit', compact('feeStructure', 'academicYears', 'classes', 'categories'));
    }

    public function update(Request $request, FeeStructure $feeStructure): RedirectResponse
    {
        $validated = $request->validate([
            'class_id'         => 'required|integer',
            'fee_category_id'  => 'required|integer',
            'amount'           => 'required|numeric|min:0',
            'due_date'         => 'required|date',
            'academic_year_id' => 'required|integer',
        ]);
        $feeStructure->update($validated);
        return redirect()->route('feeStructure.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure): RedirectResponse
    {
        $feeStructure->delete();
        return redirect()->route('feeStructure.index')->with('success', 'Fee structure deleted.');
    }
}
