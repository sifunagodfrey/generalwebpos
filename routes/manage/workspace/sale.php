<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Sales\SaleWorkspaceController as Sales;
use App\Http\Controllers\Manage\Sales\SaleSubmoduleController as Pages;

// Core checkout and payment routes.
Route::get('/sale/barcode', [Sales::class, 'barcode'])->name('manage.sales.barcode');
Route::post('/sale/quote', [Sales::class, 'quote'])->name('manage.sales.quote');
Route::post('/sale/checkout', [Sales::class, 'submit'])->name('manage.sales.checkout');
Route::get('/sale/payment', [Sales::class, 'payment'])->name('manage.sales.payment');
Route::get('/sale/held', [Pages::class, 'held'])->name('manage.sales.held');
Route::get('/sale/held/{saleId}/detail', [Pages::class, 'heldDetail'])->name('manage.sales.held.detail');
Route::get('/sale/held/{saleId}', [Sales::class, 'recall'])->name('manage.sales.recall');
Route::delete('/sale/held/{saleId}', [Sales::class, 'discard'])->name('manage.sales.discard');
Route::get('/sale/history', [Pages::class, 'history'])->name('manage.sales.history');
Route::get('/sale/history/{saleId}', [Sales::class, 'receipt'])->name('manage.sales.receipt');
Route::get('/sale/backorders', [Pages::class, 'backorders'])->name('manage.sales.backorders');
Route::post('/sale/backorders/missing', [Pages::class, 'createMissing'])->name('manage.sales.missing.store');
Route::get('/sale/returns', [Pages::class, 'returns'])->name('manage.sales.returns');
Route::get('/sale/returns/{saleId}', [Pages::class, 'returnSale'])->name('manage.sales.returns.show');
Route::get('/sale/discounts', [Pages::class, 'discounts'])->name('manage.sales.discounts');
Route::get('/sale/discounts/create', [Pages::class, 'discountForm'])->name('manage.sales.discounts.create');
Route::post('/sale/discounts', [Pages::class, 'saveDiscount'])->name('manage.sales.discounts.store');
Route::get('/sale/discounts/{discountId}/edit', [Pages::class, 'discountForm'])->name('manage.sales.discounts.edit');
Route::put('/sale/discounts/{discountId}', [Pages::class, 'saveDiscount'])->name('manage.sales.discounts.update');
Route::delete('/sale/discounts/{discountId}', [Pages::class, 'deleteDiscount'])->name('manage.sales.discounts.destroy');
Route::get('/sale/backorders/products', [Pages::class, 'backorderProducts'])->name('manage.sales.backorders.products');
Route::get('/sale/{page?}', [Sales::class, 'navigate'])->name('manage.workspace.sale');
