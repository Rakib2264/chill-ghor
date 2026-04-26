<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.advertisements.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'body'          => 'required|string',
            'emoji'         => 'nullable|string|max:10',
            'badge'         => 'nullable|string|max:100',
            'bg_color'      => 'nullable|string|max:20',
            'text_color'    => 'nullable|string|max:20',
            'cta_text'      => 'nullable|string|max:100',
            'cta_url'       => 'nullable|string|max:255',
            'cta_color'     => 'nullable|string|max:20',
            'style'         => 'required|in:banner,popup,slide',
            'show_on_pages' => 'nullable|array',
            'is_active'     => 'boolean',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date|after_or_equal:starts_at',
            'sort_order'    => 'integer',
        ]);

        $data['show_on_pages'] = $request->input('show_on_pages', ['all']);
        $data['is_active']     = $request->boolean('is_active', true);

        Advertisement::create($data);

        return back()->with('toast', '✅ বিজ্ঞাপন তৈরি হয়েছে');
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'body'          => 'required|string',
            'emoji'         => 'nullable|string|max:10',
            'badge'         => 'nullable|string|max:100',
            'bg_color'      => 'nullable|string|max:20',
            'text_color'    => 'nullable|string|max:20',
            'cta_text'      => 'nullable|string|max:100',
            'cta_url'       => 'nullable|string|max:255',
            'cta_color'     => 'nullable|string|max:20',
            'style'         => 'required|in:banner,popup,slide',
            'show_on_pages' => 'nullable|array',
            'is_active'     => 'boolean',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date',
            'sort_order'    => 'integer',
        ]);

        $data['show_on_pages'] = $request->input('show_on_pages', ['all']);
        $data['is_active']     = $request->boolean('is_active');

        $advertisement->update($data);

        return back()->with('toast', '✅ বিজ্ঞাপন আপডেট হয়েছে');
    }

    public function toggle(Advertisement $advertisement)
    {
        $advertisement->update(['is_active' => !$advertisement->is_active]);
        $status = $advertisement->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়';
        return back()->with('toast', "✅ বিজ্ঞাপন {$status} করা হয়েছে");
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();
        return back()->with('toast', '🗑️ বিজ্ঞাপন মুছে ফেলা হয়েছে');
    }
}