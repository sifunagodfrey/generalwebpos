<?php

/* -------------------
   Media library.
------------------- */

use App\Http\Controllers\Manage\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->as('manage.')
    ->group(function () {

        Route::prefix('media')->as('media.')->group(function () {
            Route::get('/',        [MediaController::class, 'index'])->name('index');
            Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
            Route::get('/refresh', [MediaController::class, 'refresh'])->name('refresh');
            Route::get('/{id}/download', [MediaController::class, 'download'])->name('download');
            Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
        });

    });
