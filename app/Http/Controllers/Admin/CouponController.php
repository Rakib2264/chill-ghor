<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:40|unique:coupons,code',
            'label' => 'nullable|string|max:120',
            'type' => 'required|in:percent,flat',
            'value' => 'required|integer|min:1',
            'min_order' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        Coupon::create($data);

        return back()->with('toast', '✅ কুপন তৈরি হয়েছে');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => 'required|string|max:40|unique:coupons,code,' . $coupon->id,
            'label' => 'nullable|string|max:120',
            'type' => 'required|in:percent,flat',
            'value' => 'required|integer|min:1',
            'min_order' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', false);
        $coupon->update($data);
        return back()->with('toast', '✅ কুপন আপডেট হয়েছে');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('toast', '🗑️ কুপন মুছে ফেলা হয়েছে');
    }
}
