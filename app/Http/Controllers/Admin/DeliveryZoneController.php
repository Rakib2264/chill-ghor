<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    public function index()
    {
        $zones = DeliveryZone::orderBy('zone_name')->get();
        return view('admin.delivery-zones.index', compact('zones'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'zone_name' => 'required|string|max:100|unique:delivery_zones',
            'min_order_for_free' => 'required|integer|min:0',
            'delivery_charge' => 'required|integer|min:0',
        ]);
        
        DeliveryZone::create($data + ['is_active' => true]);
        return back()->with('toast', '✅ জোন যোগ হয়েছে');
    }
    
    public function update(Request $request, DeliveryZone $zone)
    {
        $data = $request->validate([
            'zone_name' => 'required|string|max:100|unique:delivery_zones,zone_name,' . $zone->id,
            'min_order_for_free' => 'required|integer|min:0',
            'delivery_charge' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $zone->update($data + ['is_active' => $request->boolean('is_active')]);
        return back()->with('toast', '✅ জোন আপডেট হয়েছে');
    }
    
    public function destroy(DeliveryZone $zone)
    {
        $zone->delete();
        return back()->with('toast', '🗑️ জোন মুছে ফেলা হয়েছে');
    }
}