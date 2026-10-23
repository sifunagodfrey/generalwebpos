<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Inventory\ProductController;
use App\Http\Controllers\Manage\Inventory\CategoryController;
use App\Http\Controllers\Manage\Inventory\BrandController;
use App\Http\Controllers\Manage\Inventory\UnitOfMeasureController;

Route::apiResource('products', ProductController::class)->parameters(['products' => 'record']);
Route::apiResource('categories', CategoryController::class)->parameters(['categories' => 'record']);
Route::apiResource('brands', BrandController::class)->parameters(['brands' => 'record']);
Route::apiResource('units', UnitOfMeasureController::class)->parameters(['units' => 'record']);
