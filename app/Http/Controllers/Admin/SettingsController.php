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
        $groups   = Setting::select('group')->distinct()->orderBy('group')->pluck('group');
        $settings = Setting::orderBy('id')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function update(Request $request)
    {
        $inputSettings = $request->input('settings', []);

        if (empty($inputSettings)) {
            return back()->with('toast', '⚠️ কোনো সেটিংস পাওয়া যায়নি');
        }

        // Load all relevant settings at once (no N+1)
        $allSettings = Setting::whereIn('key', array_keys($inputSettings))
            ->get()
            ->keyBy('key');

        foreach ($inputSettings as $key => $data) {
            $setting = $allSettings->get($key);
            if (!$setting) continue;

            // Images handled separately below
            if ($setting->type === 'image') continue;

            $value = $data['value'] ?? null;

            // ✅ Type-safe casting
            $value = match ($setting->type) {
                'boolean' => (isset($data['value']) && in_array($data['value'], ['1', 1, true, 'true', 'on'], true)) ? '1' : '0',
                'number'  => is_numeric($value) ? (string)(int)$value : '0',
                'json'    => $this->sanitizeJson($value),
                default   => (string)($value ?? ''),
            };

            // ✅ Skip invalid json
            if ($setting->type === 'json' && $value === null) continue;

            $setting->update(['value' => $value]);

            // Clear cache
            Cache::forget("setting.{$key}");
            Cache::forget("settings.group.{$setting->group}");
        }

        // ✅ Handle image uploads
        if ($request->hasFile('settings')) {
            foreach ($request->file('settings') as $key => $fileData) {
                if (empty($fileData['file'])) continue;

                $setting = $allSettings->get($key);
                if (!$setting || $setting->type !== 'image') continue;

                $file = $fileData['file'];
                if (!$file->isValid()) continue;

                // Delete old image (not default ones)
                $oldValue = $setting->value;
                if ($oldValue && str_starts_with($oldValue, 'storage/')) {
                    $oldPath = str_replace('storage/', '', $oldValue);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $path = $file->store('settings', 'public');
                $setting->update(['value' => 'storage/' . $path]);

                Cache::forget("setting.{$key}");
                Cache::forget("settings.group.{$setting->group}");
            }
        }

        return back()->with('toast', '✅ সেটিংস সফলভাবে সংরক্ষণ হয়েছে');
    }

    private function sanitizeJson(?string $value): ?string
    {
        if (empty($value)) return '[]';

        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE) return null; // signal to skip

        return json_encode($decoded, JSON_UNESCAPED_UNICODE);
    }
}