<?php

/* ------------------- Audit trail ------------------- */

namespace App\Http\Controllers\Manage\System;

use App\Http\Controllers\Manage\ManageController;
use App\Models\System\SystemLog;
use Illuminate\Http\Request;

class ActivityLogController extends ManageController
{
    public function index(Request $request)
    {
        return view('manage.system.activity.index', [
            'entries' => SystemLog::with('user:id,first_name,last_name')
                ->when($request->input('module'), fn ($q, $m) => $q->where('module', $m))
                ->when($request->filled('user'), fn ($q) => $q->where('user_id', $request->integer('user')))
                ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))
                ->latest('id')
                ->paginate(50)
                ->withQueryString(),

            'modules' => SystemLog::distinct()->orderBy('module')->pluck('module')->filter(),
        ]);
    }
}
