<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Discount;
use App\Packages\FeeManagement\Models\FeeStructure;
use App\Packages\FeeManagement\Models\StudentDiscount;
use App\Packages\StudentManagement\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDiscountController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentDiscount::with('student', 'discount', 'feeStructure.feeCategory');

        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) =>
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name',  'like', '%'.$request->search.'%')
            );
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('fee-management::student-discount.index', compact('items'));
    }

    public function create(): View
    {
        $students      = Student::orderBy('first_name')->get();
        $discounts     = Discount::orderBy('discount_name')->get();
        $feeStructures = FeeStructure::with('feeCategory', 'schoolClass')->get();
        return view('fee-management::student-discount.create', compact('students', 'discounts', 'feeStructures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'       => 'required|integer',
            'discount_id'      => 'required|integer',
            'fee_structure_id' => 'required|integer',
        ]);
        StudentDiscount::create($validated);
        return redirect()->route('studentDiscount.index')->with('success', 'Discount assigned to student successfully.');
    }

    public function show(StudentDiscount $studentDiscount): View
    {
        $studentDiscount->load('student', 'discount', 'feeStructure.feeCategory', 'feeStructure.schoolClass');
        return view('fee-management::student-discount.show', compact('studentDiscount'));
    }

    public function edit(StudentDiscount $studentDiscount): View
    {
        $students      = Student::orderBy('first_name')->get();
        $discounts     = Discount::orderBy('discount_name')->get();
        $feeStructures = FeeStructure::with('feeCategory', 'schoolClass')->get();
        return view('fee-management::student-discount.edit', compact('studentDiscount', 'students', 'discounts', 'feeStructures'));
    }

    public function update(Request $request, StudentDiscount $studentDiscount): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'       => 'required|integer',
            'discount_id'      => 'required|integer',
            'fee_structure_id' => 'required|integer',
        ]);
        $studentDiscount->update($validated);
        return redirect()->route('studentDiscount.index')->with('success', 'Student discount updated successfully.');
    }

    public function destroy(StudentDiscount $studentDiscount): RedirectResponse
    {
        $studentDiscount->delete();
        return redirect()->route('studentDiscount.index')->with('success', 'Student discount removed.');
    }
}
