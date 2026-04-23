<?php

// Alternative approach - More robust

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
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $settingData) {
            $setting = Setting::where('key', $key)->first();

            if (! $setting) {
                continue;
            }

            // Handle different field types
            switch ($setting->type) {
                case 'image':
                    $this->handleImageUpdate($request, $setting, $key);
                    break;

                case 'boolean':
                    $value = filter_var($settingData['value'] ?? false, FILTER_VALIDATE_BOOLEAN);
                    $setting->update([
                        'value' => $value ? '1' : '0',
                    ]);
                    break;

                case 'json':
                    if (isset($settingData['value']) && ! empty($settingData['value'])) {
                        $setting->update(['value' => $settingData['value']]);
                    }
                    break;

                default:
                    if (isset($settingData['value'])) {
                        $setting->update(['value' => $settingData['value']]);
                    }
                    break;
            }

            // Clear cache
            Cache::forget("setting.{$setting->key}");
        }

        // Handle file uploads separately
        if ($request->hasFile('settings')) {
            foreach ($request->file('settings') as $key => $file) {
                $setting = Setting::where('key', $key)->first();
                if ($setting && $setting->type === 'image') {
                    $this->handleImageUpdate($request, $setting, $key, true);
                }
            }
        }

        return back()->with('toast', '✅ সেটিংস আপডেট হয়েছে');
    }

    private function handleImageUpdate($request, $setting, $key, $isFileUpload = false)
    {
        $fileKey = $isFileUpload ? $key : "settings.{$key}.file";

        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);

            // Delete old image if exists and not default
            if ($setting->value && ! str_contains($setting->value, 'default') &&
                ! str_contains($setting->value, 'logo.png') &&
                ! str_contains($setting->value, 'hero.jpeg')) {
                $oldPath = str_replace('storage/', '', $setting->value);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $file->store('settings', 'public');
            $setting->update(['value' => 'storage/'.$path]);
        }
    }
}
