<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Cashbox\CashboxWorkspaceController;

// The {page?} parameter defaults to 'index' if no page is specified in the URL.
Route::post('/cashbox/income', [CashboxWorkspaceController::class, 'storeIncome'])->name('manage.cashbox.income.store');
Route::post('/cashbox/expense', [CashboxWorkspaceController::class, 'storeExpense'])->name('manage.cashbox.expense.store');

Route::get('/cashbox/{page?}', [CashboxWorkspaceController::class, 'index'])
    ->name('manage.workspace.cashbox');