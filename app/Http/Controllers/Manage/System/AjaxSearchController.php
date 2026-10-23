<?php

/*
 * ---------------------------------------------------------------------------
 * Global admin search.
 *
 * Placeholder for now. This becomes predictive search across su_contacts
 * (and call history) once those tables exist, backing both the topbar
 * search box and the agent's own contact search.
 * ---------------------------------------------------------------------------
 */

namespace App\Http\Controllers\Manage\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}
