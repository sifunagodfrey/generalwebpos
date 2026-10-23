<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Suppliers\SupplierController;

Route::apiResource('suppliers', SupplierController::class)->parameters(['suppliers' => 'record']);
