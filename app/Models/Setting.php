<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    protected $casts = [
        'value' => 'string',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {

            $setting = self::where('key', $key)->first();
            if (!$setting) return $default;

            switch ($setting->type) {

                case 'boolean':
                    return in_array($setting->value, [1, '1', true, 'true', 'on', 'yes'], true);

                case 'number':
                    return (int)$setting->value;

                case 'json':
                    $decoded = json_decode($setting->value, true);
                    return json_last_error() === JSON_ERROR_NONE ? $decoded : [];

                default:
                    return $setting->value ?? $default;
            }
        });
    }

    public static function getGroup(string $group): array
    {
        return Cache::rememberForever("settings.group.$group", function () use ($group) {

            return self::where('group', $group)
                ->get()
                ->mapWithKeys(fn($s) => [$s->key => self::get($s->key)])
                ->toArray();
        });
    }

    public static function isEnabled(string $key): bool
    {
        $value = self::get($key);

        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (int)$value === 1;
        if (is_string($value)) return in_array(strtolower($value), ['1', 'true', 'on', 'yes']);

        return false;
    }

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("setting.{$setting->key}");

            if ($setting->group) {
                Cache::forget("settings.group.{$setting->group}");
            }
        });

        static::deleted(function ($setting) {
            Cache::forget("setting.{$setting->key}");

            if ($setting->group) {
                Cache::forget("settings.group.{$setting->group}");
            }
        });
    }
}
