<?php

/* -------------------
   System administration.

   Staff accounts, roles and permissions, and the audit log.
------------------- */

use App\Http\Controllers\Manage\System\PermissionsController;
use App\Http\Controllers\Manage\System\RolesController;
use App\Http\Controllers\Manage\System\SystemUsersController;
use App\Http\Controllers\Manage\System\ActivityLogController;
use App\Http\Controllers\Manage\System\AjaxSearchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->as('manage.')
    ->group(function () {

        /* -------------------
           Global search in the admin header
        ------------------- */
        Route::get('ajax/search', [AjaxSearchController::class, 'search'])->name('ajax.search');

        /* -------------------
           Staff accounts
        ------------------- */
        Route::middleware('permission:users.manage')->group(function () {

            Route::prefix('system-users')->as('system-users.')->group(function () {
                Route::get('/',          [SystemUsersController::class, 'index'])->name('index');
                Route::get('/create',    [SystemUsersController::class, 'create'])->name('create');
                Route::post('/',         [SystemUsersController::class, 'store'])->name('store');
                Route::get('/{id}',      [SystemUsersController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [SystemUsersController::class, 'edit'])->name('edit');
                Route::put('/{id}',      [SystemUsersController::class, 'update'])->name('update');
                Route::post('/{id}/toggle', [SystemUsersController::class, 'toggle'])->name('toggle');
                Route::post('/{id}/presence', [SystemUsersController::class, 'setPresence'])->name('presence');
                Route::delete('/{id}',   [SystemUsersController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('roles')->as('roles.')->group(function () {
                Route::get('/',          [RolesController::class, 'index'])->name('index');
                Route::get('/create',    [RolesController::class, 'create'])->name('create');
                Route::post('/',         [RolesController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [RolesController::class, 'edit'])->name('edit');
                Route::put('/{id}',      [RolesController::class, 'update'])->name('update');
                Route::delete('/{id}',   [RolesController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('permissions')->as('permissions.')->group(function () {
                Route::get('/',       [PermissionsController::class, 'index'])->name('index');
                Route::post('/update',[PermissionsController::class, 'update'])->name('update');
            });
        });

        /* -------------------
           Audit log
        ------------------- */
        Route::middleware('permission:view_system_logs')->group(function () {

            Route::prefix('activity-log')->as('activity-log.')->group(function () {
                Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            });
        });

    });
