<?php

/* ------------------- Outgoing mail ------------------- */

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Manage\ManageController;
use App\Models\System\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends ManageController
{
    public function index()
    {
        return view('manage.settings.email.index', [
            'settings' => EmailSetting::firstOrCreate([], ['driver' => 'smtp', 'port' => 587, 'encryption' => 'tls']),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'driver'       => ['required', 'string', 'max:40'],
            'host'         => ['nullable', 'string', 'max:190'],
            'port'         => ['nullable', 'integer', 'min:1', 'max:65535'],
            'username'     => ['nullable', 'string', 'max:190'],
            'password'     => ['nullable', 'string', 'max:255'],
            'encryption'   => ['nullable', 'in:tls,ssl,none'],
            'from_address' => ['nullable', 'email', 'max:190'],
            'from_name'    => ['nullable', 'string', 'max:150'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $settings = EmailSetting::firstOrCreate([]);

        /* A blank password means leave the stored one alone */
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['is_active'] = (int) ($data['is_active'] ?? 0);
        $settings->update($data);

        $this->logAction('updated', 'settings', null, 'Email settings');

        return back()->with('success', 'Email settings saved.');
    }

    public function test(Request $request)
    {
        $request->validate(['to' => ['required', 'email']]);

        try {
            Mail::raw('This is a test message from the Duramax Mabati admin panel. If you are reading it, outgoing mail is working.',
                fn ($m) => $m->to($request->input('to'))->subject('Duramax Mabati test message'));

            return back()->with('success', 'Test message sent to ' . $request->input('to') . '.');
        } catch (\Throwable $e) {
            return back()->with('error', 'The message could not be sent: ' . $e->getMessage());
        }
    }
}
