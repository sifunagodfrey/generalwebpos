<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
use App\Http\Controllers\Manage\Staff\StaffController;

// 1. Catch the "index" URL explicitly so it uses the new StaffController
Route::get('/staff/index', [StaffController::class, 'index'])->name('manage.staff.index');
Route::get('/staff', [StaffController::class, 'index']); 

Route::get('/staff/index', [StaffController::class, 'index'])->name('manage.staff.index');
Route::get('/staff', [StaffController::class, 'index']); 

Route::get('/staff/performance', [StaffController::class, 'performance'])->name('manage.staff.performance');
Route::get('/staff/leave', [StaffController::class, 'leave'])->name('manage.staff.leave');
Route::post('/staff/leave', [StaffController::class, 'storeLeave'])->name('manage.staff.store-leave');
Route::get('/staff/reports', [StaffController::class, 'reports'])->name('manage.staff.reports');
Route::post('/staff', [StaffController::class, 'store'])->name('manage.staff.store');

// 2. Keep detail routes above the catch-all (with whereNumber constraints)
Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->whereNumber('staff')->name('manage.staff.edit');
Route::put('/staff/{staff}', [StaffController::class, 'update'])->whereNumber('staff')->name('manage.staff.update');
Route::patch('/staff/{staff}/deactivate', [StaffController::class, 'deactivate'])->whereNumber('staff')->name('manage.staff.deactivate');
Route::post('/staff/{staff}/leave', [StaffController::class, 'logLeave'])->whereNumber('staff')->name('manage.staff.leave');
Route::get('/staff/{staff}', [StaffController::class, 'show'])->whereNumber('staff')->name('manage.staff.show');

// 3. Catch-all for generic sub-pages (like /staff/add or /staff/reports)
Route::get('/staff/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.staff');