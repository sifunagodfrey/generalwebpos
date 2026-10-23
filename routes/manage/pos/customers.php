<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Customers\CustomerController;

Route::apiResource('customers', CustomerController::class)->parameters(['customers' => 'record']);
