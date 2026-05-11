<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeePayment;
use App\Packages\FeeManagement\Models\StudentFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

class FeePaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = FeePayment::with('studentFee.student', 'studentFee.feeStructure.feeCategory');

        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $items = $query->orderByDesc('payment_date')->paginate(20)->withQueryString();
        return view('fee-management::fee-payment.index', compact('items'));
    }

    public function create(Request $request): View
    {
        $studentFees = StudentFee::with('student', 'feeStructure.feeCategory')
            ->whereIn('payment_status', ['Pending', 'Partially Paid'])
            ->get();
        $selectedStudentFeeId = $request->query('student_fee_id');
        return view('fee-management::fee-payment.create', compact('studentFees', 'selectedStudentFeeId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_fee_id'        => 'required|integer',
            'payment_date'          => 'required|date',
            'amount_paid'           => 'required|numeric|min:0.01',
            'payment_mode'          => 'required|in:Cash,Card,UPI,Bank Transfer',
            'transaction_reference' => 'nullable|string|max:150',
        ]);

        $payment = FeePayment::create($validated);
        $payment->studentFee->syncPaymentStatus();

        Event::dispatch('webhook.fee.paid', [[
            'id'             => $payment->payment_id,
            'student_fee_id' => $payment->student_fee_id,
            'amount_paid'    => $payment->amount_paid,
            'payment_mode'   => $payment->payment_mode,
            'payment_date'   => $payment->payment_date,
        ]]);

        return redirect()->route('feePayment.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(FeePayment $feePayment): View
    {
        $feePayment->load('studentFee.student', 'studentFee.feeStructure.feeCategory', 'studentFee.feeStructure.schoolClass');
        return view('fee-management::fee-payment.show', compact('feePayment'));
    }

    public function edit(FeePayment $feePayment): View
    {
        $studentFees = StudentFee::with('student', 'feeStructure.feeCategory')->get();
        return view('fee-management::fee-payment.edit', compact('feePayment', 'studentFees'));
    }

    public function update(Request $request, FeePayment $feePayment): RedirectResponse
    {
        $validated = $request->validate([
            'student_fee_id'        => 'required|integer',
            'payment_date'          => 'required|date',
            'amount_paid'           => 'required|numeric|min:0.01',
            'payment_mode'          => 'required|in:Cash,Card,UPI,Bank Transfer',
            'transaction_reference' => 'nullable|string|max:150',
        ]);

        $oldStudentFeeId = $feePayment->student_fee_id;
        $feePayment->update($validated);
        $feePayment->studentFee->syncPaymentStatus();

        if ($oldStudentFeeId !== (int) $validated['student_fee_id']) {
            StudentFee::find($oldStudentFeeId)?->syncPaymentStatus();
        }

        return redirect()->route('feePayment.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(FeePayment $feePayment): RedirectResponse
    {
        $studentFee = $feePayment->studentFee;
        $feePayment->delete();
        $studentFee?->syncPaymentStatus();
        return redirect()->route('feePayment.index')->with('success', 'Payment deleted.');
    }
}
