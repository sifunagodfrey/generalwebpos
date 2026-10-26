<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Documents\DocumentWorkspaceController;
use App\Http\Controllers\Manage\Sales\SaleSubmoduleController;

// Create routes must be above the {page} catch-all
Route::get('/documents/{type}/create', [DocumentWorkspaceController::class, 'create'])->name('manage.documents.create');
Route::post('/documents/{type}', [DocumentWorkspaceController::class, 'store'])->name('manage.documents.store');

Route::get('/documents/{page?}', [DocumentWorkspaceController::class, 'index'])->name('manage.workspace.documents');

// API routes for the Issue Credit Note split view
Route::get('/documents/api/sale/{saleId}', [SaleSubmoduleController::class, 'returnSale'])->name('manage.documents.api.sale');
Route::post('/documents/api/return/{saleId}', [SaleSubmoduleController::class, 'storeReturn'])->name('manage.documents.api.return');