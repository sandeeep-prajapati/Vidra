<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('feeCategory', 'Api\\FeeCategoryApiController');
Route::apiResource('feeStructure', 'Api\\FeeStructureApiController');
Route::apiResource('studentFee', 'Api\\StudentFeeApiController');
Route::apiResource('feePayment', 'Api\\FeePaymentApiController');
Route::apiResource('discount', 'Api\\DiscountApiController');
Route::apiResource('studentDiscount', 'Api\\StudentDiscountApiController');
Route::apiResource('expense', 'Api\\ExpenseApiController');
Route::apiResource('financialReport', 'Api\\FinancialReportApiController');
