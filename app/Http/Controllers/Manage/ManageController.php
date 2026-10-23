<?php

/*
 * ---------------------------------------------------------------------------
 * Base controller for the admin panel.
 *
 * Holds the pieces every module repeats: writing an audit line, flashing a
 * result, and answering either as JSON or as a redirect depending on how the
 * request arrived.
 * ---------------------------------------------------------------------------
 */

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\System\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class ManageController extends Controller
{
    /* Records an action against the audit trail */
    protected function logAction(string $action, string $module, $reference = null, ?string $description = null): void
    {
        SystemLog::create([
            'user_id'        => Auth::id(),
            'action'         => $action,
            'module'         => $module,
            'reference_type' => $reference ? class_basename($reference) : null,
            'reference_id'   => is_object($reference) ? ($reference->id ?? null) : $reference,
            'description'    => $description,
            'ip_address'     => request()->ip(),
            'user_agent'     => substr((string) request()->userAgent(), 0, 255),
        ]);
    }

    /* JSON for an interactive request, a redirect for a normal form post */
    protected function respond(Request $request, bool $ok, string $message, ?string $redirect = null, array $extra = [])
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => $ok, 'message' => $message] + $extra, $ok ? 200 : 422);
        }

        $response = $redirect ? redirect($redirect) : back();

        return $response->with($ok ? 'success' : 'error', $message);
    }

    /* Applies a sort order sent as an array of identifiers */
    protected function applyOrder(string $modelClass, array $ids, string $column = 'sort_order'): void
    {
        foreach ($ids as $position => $id) {
            $modelClass::where('id', $id)->update([$column => $position + 1]);
        }
    }
}