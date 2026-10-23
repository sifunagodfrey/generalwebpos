<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Workspace\WorkspaceController;
Route::get('/suppliers/{page?}', [WorkspaceController::class, 'show'])->name('manage.workspace.suppliers');
