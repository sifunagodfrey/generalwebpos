<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Settings\SettingsWorkspaceController;

Route::get('/settings/{page?}', [SettingsWorkspaceController::class, 'index'])->name('manage.workspace.settings');
Route::post('/settings/{page}', [SettingsWorkspaceController::class, 'save'])->name('manage.settings.save');
Route::post('/settings/mpesa/test', [SettingsWorkspaceController::class, 'testMpesa'])->name('manage.settings.mpesa.test');
Route::post('/settings/backup/run', [SettingsWorkspaceController::class, 'runBackup'])->name('manage.settings.backup.run');
Route::post('/settings/support/logs', [SettingsWorkspaceController::class, 'shareLogs'])->name('manage.settings.support.logs');
Route::get('/settings/backup/download/{filename}', [SettingsWorkspaceController::class, 'downloadBackup'])->name('manage.settings.backup.download');