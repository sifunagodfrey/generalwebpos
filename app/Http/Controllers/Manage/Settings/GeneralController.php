<?php

/* ------------------- Company name, logo and currency ------------------- */

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Manage\Concerns\HandlesSettings;
use App\Http\Controllers\Manage\ManageController;
use Illuminate\Http\Request;

class GeneralController extends ManageController
{
    use HandlesSettings;

    private const KEYS = [
        'company_name', 'site_name', 'tagline', 'logo', 'logo_light', 'favicon',
        'currency_code', 'currency_symbol',
    ];

    private const IMAGES = ['logo', 'logo_light', 'favicon'];

    public function index()
    {
        return view('manage.settings.general.index', ['values' => $this->settingValues(self::KEYS)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'    => ['required', 'string', 'max:190'],
            'site_name'       => ['nullable', 'string', 'max:120'],
            'tagline'         => ['nullable', 'string', 'max:190'],
            'currency_code'   => ['nullable', 'string', 'max:5'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'logo'            => ['nullable', 'image', 'max:2048'],
            'logo_light'      => ['nullable', 'image', 'max:2048'],
            'favicon'         => ['nullable', 'image', 'max:512'],
        ]);

        $this->saveSettings($request, self::KEYS, self::IMAGES);
        $this->logAction('updated', 'settings', null, 'General settings');

        return back()->with('success', 'Settings saved.');
    }
}
