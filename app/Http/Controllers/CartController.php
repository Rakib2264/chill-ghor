<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items    = Cart::items();
        $subtotal = Cart::subtotal();

        return view('pages.cart', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        // ── ১. Active check ──────────────────────────────────────────────────
        if (!$product->active) {
            $msg = '"' . $product->name . '" এই পণ্যটি এখন পাওয়া যাচ্ছে না।';
            return $request->wantsJson()
                ? response()->json(['ok' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        // ── ২. Out of stock check ────────────────────────────────────────────
        if ($product->isOutOfStock()) {
            $msg = '"' . $product->name . '" এখন স্টকে নেই।';
            return $request->wantsJson()
                ? response()->json(['ok' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $qty = max(1, (int) $request->input('qty', 1));

        // ── ৩. Stock limit check (unlimited = -1) ───────────────────────────
        if ($product->stock !== -1) {
            $currentInCart = Cart::getQty($product->id);
            $newTotal      = $currentInCart + $qty;

            if ($newTotal > $product->stock) {
                $available = max(0, $product->stock - $currentInCart);

                if ($available <= 0) {
                    $msg = '"' . $product->name . '" এর সর্বোচ্চ পরিমাণ ইতোমধ্যে কার্টে যোগ হয়েছে।';
                } else {
                    $msg = 'আর মাত্র ' . $available . 'টি যোগ করতে পারবেন। (স্টক: ' . $product->stock . 'টি)';
                    // Adjust qty to available
                    $qty = $available;
                }

                if ($available <= 0) {
                    return $request->wantsJson()
                        ? response()->json(['ok' => false, 'message' => $msg], 422)
                        : back()->with('error', $msg);
                }

                // Adjusted qty দিয়ে add করি
                Cart::add($product, $qty);
                $warnMsg = '"' . $product->name . '" এর ' . $qty . 'টি কার্টে যোগ হয়েছে। (সর্বোচ্চ স্টক পৌঁছে গেছে)';
                return $request->wantsJson()
                    ? response()->json([
                        'ok'      => true,
                        'warning' => true,
                        'message' => $warnMsg,
                        'count'   => Cart::count(),
                    ])
                    : back()->with('toast', '🛒 ' . $warnMsg);
            }
        }

        Cart::add($product, $qty);

        $msg = '"' . $product->name . '" কার্টে যোগ হয়েছে!';

        return $request->wantsJson()
            ? response()->json([
                'ok'      => true,
                'message' => $msg,
                'count'   => Cart::count(),
            ])
            : back()->with('toast', '🛒 ' . $msg);
    }

    public function update(Request $request, Product $product)
    {
        $qty = max(0, (int) $request->input('qty', 0));

        // Stock limit check on update
        if ($qty > 0 && $product->stock !== -1 && $qty > $product->stock) {
            $qty = $product->stock;
        }

        Cart::update($product->id, $qty);

        return $request->wantsJson()
            ? response()->json(['ok' => true, 'count' => Cart::count()])
            : back()->with('toast', 'কার্ট আপডেট হয়েছে');
    }

    public function remove(Request $request, Product $product)
    {
        Cart::remove($product->id);

        return $request->wantsJson()
            ? response()->json(['ok' => true, 'count' => Cart::count()])
            : back()->with('toast', 'পণ্য কার্ট থেকে সরানো হয়েছে');
    }

    public function clear()
    {
        Cart::clear();
        return back()->with('toast', 'কার্ট খালি করা হয়েছে');
    }
}