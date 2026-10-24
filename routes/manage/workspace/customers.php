<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
use App\Http\Controllers\Manage\Customers\CustomerProfileController;
use App\Http\Controllers\Manage\Customers\CreditLedgerController;
use App\Http\Controllers\Manage\Customers\CustomerCreditController;

// ---------------
// Customer tab, must sit above the catch-all page route
// ---------------
Route::get('/customers/profile/{customer}', [CustomerProfileController::class, 'show'])->whereNumber('customer')->name('manage.customers.show');
Route::get('/customers/ledger', [CreditLedgerController::class, 'index'])->name('manage.customers.ledger');
Route::post('/customers/profile/{customer}/payment', [CustomerCreditController::class, 'payment'])->whereNumber('customer')->name('manage.customers.payment');
Route::post('/customers/profile/{customer}/debt', [CustomerCreditController::class, 'debt'])->whereNumber('customer')->name('manage.customers.debt');
Route::post('/customers/profile/{customer}/settle', [CustomerCreditController::class, 'settle'])->whereNumber('customer')->name('manage.customers.settle');
Route::get('/customers/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.customers');