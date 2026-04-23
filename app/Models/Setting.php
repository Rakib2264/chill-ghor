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
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            // Type casting based on type field
            switch ($setting->type) {
                case 'boolean':
                    // Handle various boolean formats
                    return in_array($setting->value, [1, '1', true, 'true', 'on', 'yes'], true);
                    
                case 'number':
                    return (int) $setting->value;
                    
                case 'json':
                    if (empty($setting->value)) {
                        return [];
                    }
                    $decoded = json_decode($setting->value, true);
                    return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                    
                case 'textarea':
                case 'text':
                default:
                    return $setting->value ?? $default;
            }
        });
    }

    public static function set(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            // If no type specified, automatically detect
            $type = 'text';
            if (is_bool($value) || $value === '0' || $value === '1') {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_numeric($value)) {
                $type = 'number';
                $value = (string) $value;
            } elseif (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            }
            
            self::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
            ]);
        } else {
            // Convert value if needed
            if ($setting->type === 'boolean') {
                $value = $value ? '1' : '0';
            } elseif ($setting->type === 'number') {
                $value = (string) $value;
            } elseif ($setting->type === 'json' && is_array($value)) {
                $value = json_encode($value);
            }
            
            $setting->update(['value' => $value]);
        }
        
        Cache::forget("setting.{$key}");
    }

    public static function getGroup(string $group): array
    {
        return Cache::rememberForever("settings.group.{$group}", function () use ($group) {
            $settings = self::where('group', $group)->get();
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting->key] = self::get($setting->key);
            }
            
            return $result;
        });
    }
    
    public static function isEnabled(string $key): bool
    {
        $value = self::get($key);
        
        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (int) $value === 1;
        if (is_string($value)) return in_array(strtolower($value), ['1', 'true', 'on', 'yes']);
        
        return false;
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::flush();
        });
        
        static::deleted(function () {
            Cache::flush();
        });
    }
}