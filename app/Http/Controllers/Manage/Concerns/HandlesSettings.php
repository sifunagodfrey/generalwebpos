<?php

/*
 * ---------------------------------------------------------------------------
 * Shared behaviour for the settings screens.
 *
 * Each screen owns a group of keys. Saving writes only those keys and clears
 * the cache, so two people editing different screens cannot overwrite each
 * other's work.
 * ---------------------------------------------------------------------------
 */

namespace App\Http\Controllers\Manage\Concerns;

use App\Models\Core\Setting;
use App\Services\UploadService;
use Illuminate\Http\Request;

trait HandlesSettings
{
    /* Current values for a screen, falling back to the defaults given */
    protected function settingValues(array $keys): array
    {
        $stored = Setting::allCached();
        $values = [];

        foreach ($keys as $k => $default) {
            $isIndexed = is_int($k);
            $key = $isIndexed ? $default : $k;
            $fallback = $isIndexed ? null : $default;

            $values[$key] = $stored[$key] ?? $fallback;
        }

        return $values;
    }

    /* Writes the keys this screen owns, handling uploads and checkboxes */
    protected function saveSettings(Request $request, array $keys, array $images = [], array $booleans = []): void
    {
        $values = [];

        foreach ($keys as $key) {
            if (in_array($key, $images, true)) {
                continue;
            }

            if (in_array($key, $booleans, true)) {
                $values[$key] = $request->boolean($key) ? '1' : '0';
                continue;
            }

            if ($request->has($key)) {
                $values[$key] = (string) $request->input($key);
            }
        }

        foreach ($images as $key) {
            if ($request->hasFile($key)) {
                $values[$key] = app(UploadService::class)->store($request->file($key), 'banners')->file_path;
            }
        }

        Setting::putMany($values);
    }
}
