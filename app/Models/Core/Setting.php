<?php

/*
 * ---------------------------------------------------------------------------
 * Key and value store for every site wide setting.
 * Table: su_settings
 *
 * Reads go through a single cached array so a page that touches twenty
 * settings still issues one query. Any write clears that cache.
 * ---------------------------------------------------------------------------
 */

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'su_settings';

    public $timestamps = false;

    protected $fillable = [
        'setting_group',
        'setting_key',
        'setting_value',
        'field_type',
        'description',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_public'  => 'boolean',
        'sort_order' => 'integer',
    ];

    private const CACHE_KEY = 'site_settings_all';
    private const CACHE_TTL = 600;

    /* ---------------------------------------------------------------------
       Reads
    --------------------------------------------------------------------- */

    /* One value, or the fallback when it is missing or blank */
    public static function get(string $key, ?string $default = null): ?string
    {
        $all = static::allCached();

        return array_key_exists($key, $all) && $all[$key] !== '' ? $all[$key] : $default;
    }

    /* A boolean setting such as a feature toggle */
    public static function enabled(string $key, bool $default = false): bool
    {
        $value = static::get($key);

        return $value === null ? $default : in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    /* A numeric setting such as a validity period or a rate */
    public static function number(string $key, float $default = 0): float
    {
        $value = static::get($key);

        return $value === null || $value === '' ? $default : (float) $value;
    }

    /* An uploaded file setting resolved to a url */
    public static function image(string $key, ?string $fallback = null): ?string
    {
        $value = static::get($key);

        if (! $value) {
            return $fallback ? asset($fallback) : null;
        }

        return str_starts_with($value, 'http') ? $value : asset($value);
    }

    public static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::pluck('setting_value', 'setting_key')->toArray();
        });
    }

    /* Every setting in one group, used to build the settings screens */
    public static function group(string $group): array
    {
        return static::where('setting_group', $group)
            ->orderBy('sort_order')
            ->pluck('setting_value', 'setting_key')
            ->toArray();
    }

    /* ---------------------------------------------------------------------
       Writes
    --------------------------------------------------------------------- */

    public static function put(string $key, ?string $value, ?string $description = null): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value ?? ''] + ($description ? ['description' => $description] : [])
        );

        static::flush();
    }

    /* Save a whole form in one pass */
    public static function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => is_array($value) ? json_encode($value) : (string) ($value ?? '')]
            );
        }

        static::flush();
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
