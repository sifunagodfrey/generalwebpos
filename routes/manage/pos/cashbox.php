<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Cashbox\ShiftController;

Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
Route::get('shifts/{record}', [ShiftController::class, 'show'])->name('shifts.show');
Route::post('shifts/{record}/close', [ShiftController::class, 'close'])->name('shifts.close');
