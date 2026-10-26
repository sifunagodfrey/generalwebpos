<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manage\Reports\ReportsWorkspaceController;

Route::get('/reports/{page?}', [ReportsWorkspaceController::class, 'index'])
    ->name('manage.workspace.reports');