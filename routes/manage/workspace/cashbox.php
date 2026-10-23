<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
Route::get('/cashbox/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.cashbox');
