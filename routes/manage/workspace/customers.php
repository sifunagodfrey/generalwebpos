<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
use App\Http\Controllers\Manage\Customers\CustomerProfileController;
use App\Http\Controllers\Manage\Customers\CreditLedgerController;

// ---------------
// Customer tab, must sit above the catch-all page route
// ---------------
Route::get('/customers/profile/{customer}', [CustomerProfileController::class, 'show'])->whereNumber('customer')->name('manage.customers.show');
Route::get('/customers/ledger', [CreditLedgerController::class, 'index'])->name('manage.customers.ledger');
Route::get('/customers/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.customers');