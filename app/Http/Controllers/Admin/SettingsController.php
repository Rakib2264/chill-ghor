<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
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
        $data = $request->validate([
            'settings'            => 'required|array',
            'settings.*.key'      => 'required|string',
            'settings.*.value'    => 'nullable',
            'settings.*.type'     => 'required|string',
        ]);

        foreach ($data['settings'] as $settingData) {
            $setting = Setting::where('key', $settingData['key'])->first();
            
            if (!$setting) continue;
            
            $value = $settingData['value'] ?? '';
            
            if ($setting->type === 'image' && $request->hasFile("settings.{$settingData['key']}.file")) {
                $file = $request->file("settings.{$settingData['key']}.file");
                
                if ($setting->value && !str_starts_with($setting->value, 'http')) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $setting->value));
                }
                
                $path = $file->store('settings', 'public');
                $value = 'storage/' . $path;
            }
            
            $setting->update(['value' => $value]);
        }

        return back()->with('toast', '✅ সেটিংস আপডেট হয়েছে');
    }
}