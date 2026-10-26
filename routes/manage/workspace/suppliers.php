<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
use App\Http\Controllers\Manage\Suppliers\SupplierProfileController;
use App\Http\Controllers\Manage\Suppliers\SupplierPaymentController;

// ---------------
// Supplier details, must sit above the catch-all page route
// ---------------
Route::get('/suppliers/profile/{supplier}', [SupplierProfileController::class, 'show'])->whereNumber('supplier')->name('manage.suppliers.show');
Route::post('/suppliers/profile/{supplier}/payment', [SupplierPaymentController::class, 'payment'])->whereNumber('supplier')->name('manage.suppliers.payment');
Route::get('/suppliers/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.suppliers');