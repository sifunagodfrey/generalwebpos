<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Inventory\InventoryWorkspaceController as Inventory;

// Detail and form routes precede the module's catch-all navigation route.
Route::get('/inventory/products/{product}/edit', [Inventory::class, 'edit'])->name('manage.inventory.product.edit');
Route::put('/inventory/products/{product}', [Inventory::class, 'update'])->name('manage.inventory.product.update');
Route::get('/inventory/products/{product}', [Inventory::class, 'show'])->name('manage.inventory.product.show');
Route::post('/inventory/products', [Inventory::class, 'store'])->name('manage.inventory.product.store');

// Category routes
Route::get('/inventory/categories/create', [Inventory::class, 'createCategory'])->name('manage.inventory.categories.create');
Route::post('/inventory/categories', [Inventory::class, 'storeCategory'])->name('manage.inventory.categories.store');
// Category Edit/Update/Delete routes
Route::get('/inventory/categories/{category}/edit', [Inventory::class, 'editCategory'])->name('manage.inventory.categories.edit');
Route::put('/inventory/categories/{category}', [Inventory::class, 'updateCategory'])->name('manage.inventory.categories.update');
Route::delete('/inventory/categories/{category}', [Inventory::class, 'destroyCategory'])->name('manage.inventory.categories.destroy');

Route::post('/inventory/{type}/entries', [Inventory::class, 'storeLookup'])->whereIn('type', ['categories', 'brands'])->name('manage.inventory.lookup.store');
Route::put('/inventory/{type}/entries/{id}', [Inventory::class, 'updateLookup'])->whereIn('type', ['categories', 'brands'])->name('manage.inventory.lookup.update');
Route::get('/inventory/{page?}', [Inventory::class, 'section'])->name('manage.workspace.inventory');