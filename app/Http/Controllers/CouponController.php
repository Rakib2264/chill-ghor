<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        Log::info('=== COUPON APPLY CALLED ===');
        Log::info('Request data:', $request->all());
        
        $code = strtoupper(trim((string) $request->input('code')));

        if ($code === '') {
            return response()->json(['ok' => false, 'message' => 'কুপন কোড লিখুন'], 422);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['ok' => false, 'message' => 'এই কোডের কোনো কুপন নেই'], 422);
        }

        $subtotal = Cart::subtotal();
        $userId = Auth::id();

        // ✅ ইউজার লিমিট সহ ভ্যালিডেশন
        list($isValid, $errorMessage) = $coupon->isValid($subtotal, $userId);
        
        if (!$isValid) {
            return response()->json(['ok' => false, 'message' => $errorMessage], 422);
        }

        // ডিসকাউন্ট ক্যালকুলেশন
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return response()->json(['ok' => false, 'message' => 'এই কুপন প্রযোজ্য নয়'], 422);
        }

        // সেশনে স্টোর করুন
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'coupon_id' => $coupon->id,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'user_id' => $userId
            ]
        ]);

        // ইউজার কতবার ব্যবহার করতে পারবে তা জানান
        $remainingUses = $coupon->userRemainingUses($userId);
        $extraMessage = '';
        
        if ($remainingUses > 0 && $coupon->per_user_limit) {
            $extraMessage = " (আরও {$remainingUses} বার বাকি)";
        } elseif ($coupon->per_user_limit === 1 && $coupon->userUsedCount($userId) === 0) {
            $extraMessage = " (শুধু একবার ব্যবহারযোগ্য)";
        }

        return response()->json([
            'ok' => true,
            'discount' => $discount,
            'message' => "✅ কুপন প্রয়োগ হয়েছে — ৳{$discount} ছাড়{$extraMessage}"
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('coupon');
        
        return response()->json([
            'ok' => true,
            'message' => 'কুপন বাদ দেওয়া হয়েছে'
        ]);
    }
}