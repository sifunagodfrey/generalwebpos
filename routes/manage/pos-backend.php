<?php
use Illuminate\Support\Facades\Route;
Route::apiResource("products", \App\Http\Controllers\Manage\Inventory\ProductController::class)->parameters(["products" => "record"]);
Route::apiResource("categories", \App\Http\Controllers\Manage\Inventory\CategoryController::class)->parameters(["categories" => "record"]);
Route::apiResource("brands", \App\Http\Controllers\Manage\Inventory\BrandController::class)->parameters(["brands" => "record"]);
Route::apiResource("units", \App\Http\Controllers\Manage\Inventory\UnitOfMeasureController::class)->parameters(["units" => "record"]);
Route::apiResource("customers", \App\Http\Controllers\Manage\Customers\CustomerController::class)->parameters(["customers" => "record"]);
Route::apiResource("suppliers", \App\Http\Controllers\Manage\Suppliers\SupplierController::class)->parameters(["suppliers" => "record"]);
Route::get("shifts", [\App\Http\Controllers\Manage\Cashbox\ShiftController::class,"index"]);
Route::post("shifts", [\App\Http\Controllers\Manage\Cashbox\ShiftController::class,"store"]);
Route::get("shifts/{record}", [\App\Http\Controllers\Manage\Cashbox\ShiftController::class,"show"]);
Route::post("shifts/{record}/close", [\App\Http\Controllers\Manage\Cashbox\ShiftController::class,"close"]);
