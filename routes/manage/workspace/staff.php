<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
Route::get('/staff/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.staff');
