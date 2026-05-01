<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeeStructure;
use App\Packages\FeeManagement\Models\StudentFee;
use App\Packages\StudentManagement\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentFeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentFee::with('student', 'feeStructure.feeCategory', 'feeStructure.schoolClass');

        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) =>
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name',  'like', '%'.$request->search.'%')
            );
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('fee-management::student-fee.index', compact('items'));
    }

    public function create(): View
    {
        $students      = Student::orderBy('first_name')->get();
        $feeStructures = FeeStructure::with('feeCategory', 'schoolClass', 'academicYear')->get();
        return view('fee-management::student-fee.create', compact('students', 'feeStructures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'       => 'required|integer',
            'fee_structure_id' => 'required|integer',
            'amount_due'       => 'required|numeric|min:0',
            'discount_amount'  => 'nullable|numeric|min:0',
            'penalty_amount'   => 'nullable|numeric|min:0',
            'due_date'         => 'required|date',
        ]);

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['penalty_amount']  = $validated['penalty_amount']  ?? 0;
        $validated['total_payable']   = $validated['amount_due'] - $validated['discount_amount'] + $validated['penalty_amount'];
        $validated['payment_status']  = 'Pending';

        StudentFee::create($validated);
        return redirect()->route('studentFee.index')->with('success', 'Student fee record created successfully.');
    }

    public function show(StudentFee $studentFee): View
    {
        $studentFee->load('student', 'feeStructure.feeCategory', 'feeStructure.schoolClass', 'payments');
        return view('fee-management::student-fee.show', compact('studentFee'));
    }

    public function edit(StudentFee $studentFee): View
    {
        $students      = Student::orderBy('first_name')->get();
        $feeStructures = FeeStructure::with('feeCategory', 'schoolClass', 'academicYear')->get();
        return view('fee-management::student-fee.edit', compact('studentFee', 'students', 'feeStructures'));
    }

    public function update(Request $request, StudentFee $studentFee): RedirectResponse
    {
        $validated = $request->validate([
            'student_id'       => 'required|integer',
            'fee_structure_id' => 'required|integer',
            'amount_due'       => 'required|numeric|min:0',
            'discount_amount'  => 'nullable|numeric|min:0',
            'penalty_amount'   => 'nullable|numeric|min:0',
            'due_date'         => 'required|date',
        ]);

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['penalty_amount']  = $validated['penalty_amount']  ?? 0;
        $validated['total_payable']   = $validated['amount_due'] - $validated['discount_amount'] + $validated['penalty_amount'];

        $studentFee->update($validated);
        $studentFee->syncPaymentStatus();

        return redirect()->route('studentFee.index')->with('success', 'Student fee record updated successfully.');
    }

    public function destroy(StudentFee $studentFee): RedirectResponse
    {
        $studentFee->delete();
        return redirect()->route('studentFee.index')->with('success', 'Student fee record deleted.');
    }
}
