<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = Setting::select('group')->distinct()->pluck('group');
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function update(Request $request)
    {
        $inputSettings = $request->input('settings', []);

        // 🔥 Optimize query (no N+1)
        $allSettings = Setting::whereIn('key', array_keys($inputSettings))
            ->get()
            ->keyBy('key');

        foreach ($inputSettings as $key => $data) {

            $setting = $allSettings[$key] ?? null;
            if (!$setting) continue;

            // 🔥 Skip image (handled later)
            if ($setting->type === 'image') continue;

            $value = $data['value'] ?? null;

            switch ($setting->type) {

                case 'boolean':
                    $value = ($value == '1') ? '1' : '0';
                    break;

                case 'number':
                    $value = is_numeric($value) ? (string)$value : '0';
                    break;

                case 'json':
                    if (!empty($value)) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = json_encode($decoded);
                        } else {
                            continue; // skip invalid JSON
                        }
                    }
                    break;

                default:
                    $value = $value ?? '';
                    break;
            }

            $setting->update(['value' => $value]);

            // clear cache
            Cache::forget("setting.$key");

            if ($setting->group) {
                Cache::forget("settings.group.{$setting->group}");
            }
        }

        // 🔥 HANDLE IMAGE UPLOADS (ONLY HERE)
        if ($request->hasFile('settings')) {

            foreach ($request->file('settings') as $key => $fileData) {

                if (!isset($fileData['file'])) continue;

                $setting = $allSettings[$key] ?? null;
                if (!$setting || $setting->type !== 'image') continue;

                $file = $fileData['file'];

                // delete old image
                if ($setting->value &&
                    !str_contains($setting->value, 'default') &&
                    !str_contains($setting->value, 'logo.png') &&
                    !str_contains($setting->value, 'hero.jpeg')
                ) {
                    $oldPath = str_replace('storage/', '', $setting->value);

                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $path = $file->store('settings', 'public');

                $setting->update([
                    'value' => 'storage/' . $path
                ]);

                Cache::forget("setting.$key");

                if ($setting->group) {
                    Cache::forget("settings.group.{$setting->group}");
                }
            }
        }

        return back()->with('toast', '✅ সেটিংস সফলভাবে আপডেট হয়েছে');
    }
}