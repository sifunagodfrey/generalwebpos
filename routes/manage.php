<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Existing authentication routes are registered separately in routes/auth.php.
// This file only defines the dashboard and protected POS modules.
$dashboard = fn () => view('manage.dashboard.index');
$root = fn () => Auth::check()
    ? redirect()->route('manage.dashboard.index')
    : redirect()->route('manage.auth.login');

$domain = config('app.admin_domain');
$routes = static function () use ($dashboard): void {
    Route::get('/dashboard', [\App\Http\Controllers\Manage\Workspace\WorkspaceController::class, 'home'])->name('manage.dashboard.index');
    Route::prefix('workspace')->group(base_path('routes/manage/workspace.php'));
    Route::get('/workspace/license/activate', [\App\Http\Controllers\Manage\Settings\Licensing\PosLicenseController::class, 'show'])->name('manage.license.show');
    Route::post('/workspace/license/activate', [\App\Http\Controllers\Manage\Settings\Licensing\PosLicenseController::class, 'activate'])->name('manage.license.activate');

    // All POS feature routes live in their own files, with a shared
    // /pos URL prefix and manage.pos.* name prefix.
    Route::prefix('pos')->name('manage.pos.')->group(
        base_path('routes/manage/pos.php')
    );
};

if ($domain && request()->getHost() === $domain) {
    Route::domain($domain)->get('/', $root);
    Route::domain($domain)->middleware(['auth', 'remember.session'])->group($routes);
} else {
    Route::get('/', $root);
    Route::middleware(['auth', 'remember.session'])->group($routes);
}
