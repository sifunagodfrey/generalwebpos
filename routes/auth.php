<?php

/* -------------------
   Authentication routes.

   This system has one login flow: the admin panel, with two factor
   verification and an OTP based password reset. There is no public
   registration, accounts are created by a Super Admin from the system
   users screen. This whole app is the admin panel, so these routes carry
   no /manage prefix, login lives at /login. When ADMIN_DOMAIN is set they
   bind to that subdomain instead.
------------------- */

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Manage\Auth\ManageForgotPasswordController;
use Illuminate\Support\Facades\Route;

$adminDomain = config('app.admin_domain');
$onAdminDomain = $adminDomain !== '' && request()->getHost() === $adminDomain;

/* -------------------
   Admin sign in, two factor, password reset and sign out
------------------- */
$adminAuthRoutes = function () {

    Route::get('login', [AdminLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');

    // Partial session state guards these in the controller, so no auth middleware
    Route::get('verify-2fa', [AdminLoginController::class, 'showVerify'])->name('verify2fa');
    Route::post('verify-2fa', [AdminLoginController::class, 'verify'])->name('verify2fa.submit');

    Route::get('forgot-password',         [ManageForgotPasswordController::class, 'showRequest'])   ->name('forgot-password');
    Route::post('forgot-password',        [ManageForgotPasswordController::class, 'sendCode'])      ->name('forgot-password.send');
    Route::get('forgot-password/verify',  [ManageForgotPasswordController::class, 'showVerify'])    ->name('forgot-password.verify');
    Route::post('forgot-password/verify', [ManageForgotPasswordController::class, 'verifyCode'])    ->name('forgot-password.verify.submit');
    Route::get('forgot-password/reset',   [ManageForgotPasswordController::class, 'showReset'])     ->name('forgot-password.reset');
    Route::post('forgot-password/reset',  [ManageForgotPasswordController::class, 'resetPassword']) ->name('forgot-password.reset.submit');

    Route::middleware('auth')->post('logout', [AdminLoginController::class, 'logout'])->name('logout');
};

if ($onAdminDomain) {
    Route::domain($adminDomain)->as('manage.auth.')->group($adminAuthRoutes);
} else {
    Route::as('manage.auth.')->group($adminAuthRoutes);
}
