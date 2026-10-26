<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
use App\Http\Controllers\Manage\Suppliers\SupplierProfileController;
use App\Http\Controllers\Manage\Suppliers\SupplierPaymentController;
use App\Http\Controllers\Manage\Suppliers\PurchaseOrderController;
use App\Http\Controllers\Manage\Suppliers\SupplierOwedController;

// ---------------
// Supplier details, must sit above the catch-all page route
// ---------------
Route::get('/suppliers/profile/{supplier}', [SupplierProfileController::class, 'show'])->whereNumber('supplier')->name('manage.suppliers.show');
Route::post('/suppliers/profile/{supplier}/payment', [SupplierPaymentController::class, 'payment'])->whereNumber('supplier')->name('manage.suppliers.payment');

// ---------------
// Purchase orders and pending deliveries, "orders/new" must sit above "orders/{order}"
// ---------------
Route::get('/suppliers/orders', [PurchaseOrderController::class, 'index'])->name('manage.suppliers.orders');
Route::get('/suppliers/orders/new', [PurchaseOrderController::class, 'create'])->name('manage.suppliers.orders.create');
Route::post('/suppliers/orders', [PurchaseOrderController::class, 'store'])->name('manage.suppliers.orders.store');
Route::get('/suppliers/orders/{order}', [PurchaseOrderController::class, 'show'])->whereNumber('order')->name('manage.suppliers.orders.show');
Route::post('/suppliers/orders/{order}/receive', [PurchaseOrderController::class, 'receive'])->whereNumber('order')->name('manage.suppliers.orders.receive');
Route::get('/suppliers/deliveries', [PurchaseOrderController::class, 'deliveries'])->name('manage.suppliers.deliveries');

// ---------------
// Payments owed, the slug comes from the sidebar entry in config/suweka_ui.php
// ---------------
$owedEntry = collect(config('suweka_ui.suppliers.items', []))->first(fn ($i) => strcasecmp((string) ($i[1] ?? ''), 'Payments owed') === 0);
Route::get('/suppliers/' . ($owedEntry[0] ?? 'owed'), [SupplierOwedController::class, 'index'])->name('manage.suppliers.owed');

Route::get('/suppliers/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.suppliers');