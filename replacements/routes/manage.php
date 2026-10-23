<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$dashboard = fn () => view('manage.dashboard.index');
$root = fn () => Auth::check() ? redirect()->route('manage.dashboard.index') : redirect()->route('manage.auth.login');
$domain = config('app.admin_domain');
// Keep the existing admin-domain convention. No call-center route modules.
if ($domain && request()->getHost() === $domain) {
    Route::domain($domain)->get('/', $root);
    Route::domain($domain)->middleware(['auth', 'remember.session'])->group(function () use ($dashboard) {
        Route::get('/dashboard', $dashboard)->name('manage.dashboard.index');
    });
} else {
    Route::get('/', $root);
    Route::middleware(['auth', 'remember.session'])->group(function () use ($dashboard) {
        Route::get('/dashboard', $dashboard)->name('manage.dashboard.index');
    });
}
