<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Support\Cart;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('code')));

        if ($code === '') {
            return back()->with('coupon_error', 'কুপন কোড লিখুন');
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('coupon_error', 'এই কোডের কোনো কুপন নেই');
        }

        $subtotal = Cart::subtotal();

        // ✅ validation check
        [$ok, $err] = $coupon->isValid($subtotal);
        if (!$ok) {
            return back()->with('coupon_error', $err);
        }

        // ✅ calculate discount
        $discount = $coupon->calculateDiscount($subtotal);

        // ❗ prevent negative বা invalid discount
        if ($discount <= 0) {
            return back()->with('coupon_error', 'এই কুপন প্রযোজ্য নয়');
        }

        // ✅ store session
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'coupon_id' => $coupon->id,
            ]
        ]);

        return back()->with('toast', "✅ কুপন প্রয়োগ হয়েছে — ৳{$discount} ছাড়");
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('toast', 'কুপন বাদ দেওয়া হয়েছে');
    }
}
