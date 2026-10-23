<?php

/* ------------------- Sign in security ------------------- */

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Manage\Concerns\HandlesSettings;
use App\Http\Controllers\Manage\ManageController;
use Illuminate\Http\Request;

class SecurityController extends ManageController
{
    use HandlesSettings;

    private const KEYS = [
        'two_factor_admin', 'two_factor_customer', 'session_timeout_minutes',
        'max_login_attempts', 'allow_guest_checkout', 'allow_registration',
    ];

    private const BOOLEANS = ['two_factor_admin', 'two_factor_customer', 'allow_guest_checkout', 'allow_registration'];

    public function index()
    {
        return view('manage.settings.security.index', ['values' => $this->settingValues(self::KEYS)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'session_timeout_minutes' => ['nullable', 'integer', 'min:5', 'max:1440'],
            'max_login_attempts'      => ['nullable', 'integer', 'min:3', 'max:20'],
        ]);

        $this->saveSettings($request, self::KEYS, [], self::BOOLEANS);
        $this->logAction('updated', 'settings', null, 'Security settings');

        return back()->with('success', 'Security settings saved.');
    }
}
