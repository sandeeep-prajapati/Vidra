<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view-fees')->group(function () {
    Route::get('feeCategory', 'FeeCategoryController@index')->name('feeCategory.index');
    Route::get('feeCategory/{feeCategory}', 'FeeCategoryController@show')->name('feeCategory.show');
    Route::get('feeStructure', 'FeeStructureController@index')->name('feeStructure.index');
    Route::get('feeStructure/{feeStructure}', 'FeeStructureController@show')->name('feeStructure.show');
    Route::get('studentFee', 'StudentFeeController@index')->name('studentFee.index');
    Route::get('studentFee/{studentFee}', 'StudentFeeController@show')->name('studentFee.show');
    Route::get('feePayment', 'FeePaymentController@index')->name('feePayment.index');
    Route::get('feePayment/{feePayment}', 'FeePaymentController@show')->name('feePayment.show');
    Route::get('discount', 'DiscountController@index')->name('discount.index');
    Route::get('discount/{discount}', 'DiscountController@show')->name('discount.show');
    Route::get('studentDiscount', 'StudentDiscountController@index')->name('studentDiscount.index');
    Route::get('studentDiscount/{studentDiscount}', 'StudentDiscountController@show')->name('studentDiscount.show');
    Route::get('expense', 'ExpenseController@index')->name('expense.index');
    Route::get('expense/{expense}', 'ExpenseController@show')->name('expense.show');
    Route::get('financialReport', 'FinancialReportController@index')->name('financialReport.index');
    Route::get('financialReport/{financialReport}', 'FinancialReportController@show')->name('financialReport.show');
});

Route::middleware('permission:create-fees')->group(function () {
    Route::get('feeCategory/create', 'FeeCategoryController@create')->name('feeCategory.create');
    Route::post('feeCategory', 'FeeCategoryController@store')->name('feeCategory.store');
    Route::get('feeStructure/create', 'FeeStructureController@create')->name('feeStructure.create');
    Route::post('feeStructure', 'FeeStructureController@store')->name('feeStructure.store');
    Route::get('studentFee/create', 'StudentFeeController@create')->name('studentFee.create');
    Route::post('studentFee', 'StudentFeeController@store')->name('studentFee.store');
    Route::get('feePayment/create', 'FeePaymentController@create')->name('feePayment.create');
    Route::post('feePayment', 'FeePaymentController@store')->name('feePayment.store');
    Route::get('discount/create', 'DiscountController@create')->name('discount.create');
    Route::post('discount', 'DiscountController@store')->name('discount.store');
    Route::get('studentDiscount/create', 'StudentDiscountController@create')->name('studentDiscount.create');
    Route::post('studentDiscount', 'StudentDiscountController@store')->name('studentDiscount.store');
    Route::get('expense/create', 'ExpenseController@create')->name('expense.create');
    Route::post('expense', 'ExpenseController@store')->name('expense.store');
    Route::get('financialReport/create', 'FinancialReportController@create')->name('financialReport.create');
    Route::post('financialReport', 'FinancialReportController@store')->name('financialReport.store');
});

Route::middleware('permission:edit-fees')->group(function () {
    Route::get('feeCategory/{feeCategory}/edit', 'FeeCategoryController@edit')->name('feeCategory.edit');
    Route::put('feeCategory/{feeCategory}', 'FeeCategoryController@update')->name('feeCategory.update');
    Route::patch('feeCategory/{feeCategory}', 'FeeCategoryController@update');
    Route::get('feeStructure/{feeStructure}/edit', 'FeeStructureController@edit')->name('feeStructure.edit');
    Route::put('feeStructure/{feeStructure}', 'FeeStructureController@update')->name('feeStructure.update');
    Route::patch('feeStructure/{feeStructure}', 'FeeStructureController@update');
    Route::get('studentFee/{studentFee}/edit', 'StudentFeeController@edit')->name('studentFee.edit');
    Route::put('studentFee/{studentFee}', 'StudentFeeController@update')->name('studentFee.update');
    Route::patch('studentFee/{studentFee}', 'StudentFeeController@update');
    Route::get('feePayment/{feePayment}/edit', 'FeePaymentController@edit')->name('feePayment.edit');
    Route::put('feePayment/{feePayment}', 'FeePaymentController@update')->name('feePayment.update');
    Route::patch('feePayment/{feePayment}', 'FeePaymentController@update');
    Route::get('discount/{discount}/edit', 'DiscountController@edit')->name('discount.edit');
    Route::put('discount/{discount}', 'DiscountController@update')->name('discount.update');
    Route::patch('discount/{discount}', 'DiscountController@update');
    Route::get('studentDiscount/{studentDiscount}/edit', 'StudentDiscountController@edit')->name('studentDiscount.edit');
    Route::put('studentDiscount/{studentDiscount}', 'StudentDiscountController@update')->name('studentDiscount.update');
    Route::patch('studentDiscount/{studentDiscount}', 'StudentDiscountController@update');
    Route::get('expense/{expense}/edit', 'ExpenseController@edit')->name('expense.edit');
    Route::put('expense/{expense}', 'ExpenseController@update')->name('expense.update');
    Route::patch('expense/{expense}', 'ExpenseController@update');
    Route::get('financialReport/{financialReport}/edit', 'FinancialReportController@edit')->name('financialReport.edit');
    Route::put('financialReport/{financialReport}', 'FinancialReportController@update')->name('financialReport.update');
    Route::patch('financialReport/{financialReport}', 'FinancialReportController@update');
});

Route::middleware('permission:delete-fees')->group(function () {
    Route::delete('feeCategory/{feeCategory}', 'FeeCategoryController@destroy')->name('feeCategory.destroy');
    Route::delete('feeStructure/{feeStructure}', 'FeeStructureController@destroy')->name('feeStructure.destroy');
    Route::delete('studentFee/{studentFee}', 'StudentFeeController@destroy')->name('studentFee.destroy');
    Route::delete('feePayment/{feePayment}', 'FeePaymentController@destroy')->name('feePayment.destroy');
    Route::delete('discount/{discount}', 'DiscountController@destroy')->name('discount.destroy');
    Route::delete('studentDiscount/{studentDiscount}', 'StudentDiscountController@destroy')->name('studentDiscount.destroy');
    Route::delete('expense/{expense}', 'ExpenseController@destroy')->name('expense.destroy');
    Route::delete('financialReport/{financialReport}', 'FinancialReportController@destroy')->name('financialReport.destroy');
});
