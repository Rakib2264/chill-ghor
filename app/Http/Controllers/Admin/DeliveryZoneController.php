<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $zones = DeliveryZone::latest()->get();
        return view('admin.delivery-zones.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_name' => 'required|string|max:100|unique:delivery_zones,zone_name',
            'delivery_charge' => 'required|integer|min:0',
            'min_order_for_free' => 'required|integer|min:0',
        ]);

        DeliveryZone::create([
            'zone_name' => $request->zone_name,
            'delivery_charge' => $request->delivery_charge,
            'min_order_for_free' => $request->min_order_for_free,
            'is_active' => true,
        ]);

        return redirect()->route('admin.delivery-zones.index')
            ->with('toast', '✅ নতুন ডেলিভারি জোন যোগ হয়েছে');
    }

    public function update(Request $request, DeliveryZone $zone)
    {
        $request->validate([
            'zone_name' => 'required|string|max:100|unique:delivery_zones,zone_name,' . $zone->id,
            'delivery_charge' => 'required|integer|min:0',
            'min_order_for_free' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $zone->update([
            'zone_name' => $request->zone_name,
            'delivery_charge' => $request->delivery_charge,
            'min_order_for_free' => $request->min_order_for_free,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.delivery-zones.index')
            ->with('toast', '✅ ডেলিভারি জোন আপডেট হয়েছে');
    }

    public function destroy(DeliveryZone $zone)
    {
        $zone->delete();
        return back()->with('toast', '🗑️ ডেলিভারি জোন মুছে ফেলা হয়েছে');
    }
}