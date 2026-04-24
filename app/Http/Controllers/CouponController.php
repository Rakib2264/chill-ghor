<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        // ✅ ডিবাগ লাইন 1
        Log::info('=== COUPON APPLY CALLED ===');
        Log::info('Request data:', $request->all());
        
        $code = strtoupper(trim((string) $request->input('code')));

        Log::info('Code after trim/upper:', ['code' => $code]);

        if ($code === '') {
            Log::warning('Empty coupon code');
            return response()->json(['ok' => false, 'message' => 'কুপন কোড লিখুন'], 422);
        }

        $coupon = Coupon::where('code', $code)->first();

        Log::info('Coupon query result:', ['found' => $coupon ? 'yes' : 'no']);

        if (!$coupon) {
            Log::warning('Coupon not found in database', ['code' => $code]);
            return response()->json(['ok' => false, 'message' => 'এই কোডের কোনো কুপন নেই'], 422);
        }

        $subtotal = Cart::subtotal();
        
        Log::info('Coupon details:', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'is_active' => $coupon->is_active,
            'subtotal' => $subtotal
        ]);

        // Check if coupon is active
        if (!$coupon->is_active) {
            Log::warning('Coupon is inactive');
            return response()->json(['ok' => false, 'message' => 'কুপনটি নিষ্ক্রিয়'], 422);
        }

        // Check date range
        if ($coupon->valid_from && now()->lt($coupon->valid_from)) {
            Log::warning('Coupon not yet valid', ['valid_from' => $coupon->valid_from]);
            return response()->json(['ok' => false, 'message' => 'কুপনটি এখনো সক্রিয় হয়নি'], 422);
        }
        
        if ($coupon->valid_to && now()->gt($coupon->valid_to)) {
            Log::warning('Coupon expired', ['valid_to' => $coupon->valid_to]);
            return response()->json(['ok' => false, 'message' => 'কুপনটির মেয়াদ শেষ'], 422);
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            Log::warning('Coupon usage limit reached');
            return response()->json(['ok' => false, 'message' => 'কুপনটির ব্যবহারের সীমা শেষ'], 422);
        }

        // Check minimum order amount
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            Log::warning('Minimum order amount not met', [
                'required' => $coupon->min_order_amount,
                'current' => $subtotal
            ]);
            return response()->json(['ok' => false, 'message' => "ন্যূনতম {$coupon->min_order_amount} টাকার অর্ডারে কুপন valid"], 422);
        }

        // Calculate discount
        if ($coupon->type === 'percentage') {
            $discount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->value;
        }

        $discount = min($discount, $subtotal);

        Log::info('Calculated discount:', ['discount' => $discount]);

        if ($discount <= 0) {
            Log::warning('Discount is zero or negative');
            return response()->json(['ok' => false, 'message' => 'এই কুপন প্রযোজ্য নয়'], 422);
        }

        // Store in session
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'coupon_id' => $coupon->id,
                'type' => $coupon->type,
                'value' => $coupon->value
            ]
        ]);

        Log::info('Coupon applied successfully!', ['code' => $code, 'discount' => $discount]);

        return response()->json([
            'ok' => true,
            'discount' => $discount,
            'message' => "✅ কুপন প্রয়োগ হয়েছে — ৳{$discount} ছাড়"
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('coupon');
        Log::info('Coupon removed from session');
        
        return response()->json([
            'ok' => true,
            'message' => 'কুপন বাদ দেওয়া হয়েছে'
        ]);
    }
}