<?php

/* -------------------
   Settings.

   Everything a Super Admin can change without a deployment: company name,
   logo, site icon, brand colors, outgoing email, sign in security, the
   voice provider connection, business hours, and hold media. All of it
   sits behind the manage_settings permission at the file level, applied by
   routes/manage.php's module loader.
------------------- */

use App\Http\Controllers\Manage\Settings\BusinessHoursController;
use App\Http\Controllers\Manage\Settings\EmailController;
use App\Http\Controllers\Manage\Settings\GeneralController;
use App\Http\Controllers\Manage\Settings\HoldMediaController;
use App\Http\Controllers\Manage\Settings\SecurityController;
use App\Http\Controllers\Manage\Settings\VoiceProviderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->as('manage.')
    ->group(function () {

        Route::prefix('settings')->as('settings.')->group(function () {

            /* -------------------
               Company name, logo, site icon and brand colors
            ------------------- */
            Route::prefix('general')->as('general.')->group(function () {
                Route::get('/',  [GeneralController::class, 'index'])->name('index');
                Route::post('/', [GeneralController::class, 'update'])->name('update');
            });

            /* -------------------
               Outgoing email
            ------------------- */
            Route::prefix('email')->as('email.')->group(function () {
                Route::get('/',      [EmailController::class, 'index'])->name('index');
                Route::post('/',     [EmailController::class, 'update'])->name('update');
                Route::post('/test', [EmailController::class, 'test'])->name('test');
            });

            /* -------------------
               Sign in security
            ------------------- */
            Route::prefix('security')->as('security.')->group(function () {
                Route::get('/',  [SecurityController::class, 'index'])->name('index');
                Route::post('/', [SecurityController::class, 'update'])->name('update');
            });

            /* -------------------
               Voice provider connection, single active credential set
            ------------------- */
            Route::prefix('voice-provider')->as('voice-provider.')->group(function () {
                Route::get('/', [VoiceProviderController::class, 'index'])->name('index');
                Route::post('/', [VoiceProviderController::class, 'update'])->name('update');
            });

            /* -------------------
               Operating hours per day of the week
            ------------------- */
            Route::prefix('business-hours')->as('business-hours.')->group(function () {
                Route::get('/', [BusinessHoursController::class, 'index'])->name('index');
                Route::post('/', [BusinessHoursController::class, 'update'])->name('update');
            });

            /* -------------------
               Music, jingles and adverts played while a caller waits
            ------------------- */
            Route::prefix('hold-media')->as('hold-media.')->group(function () {
                Route::get('/', [HoldMediaController::class, 'index'])->name('index');
                Route::post('/', [HoldMediaController::class, 'store'])->name('store');
                Route::post('/reorder', [HoldMediaController::class, 'reorder'])->name('reorder');
                Route::post('/{id}/toggle', [HoldMediaController::class, 'toggle'])->name('toggle');
                Route::delete('/{id}', [HoldMediaController::class, 'destroy'])->name('destroy');
            });

        });

    });
