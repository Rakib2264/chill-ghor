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
        // ✅ action (inc/dec) অথবা direct qty — দুটোই handle করি
        if ($request->has('action')) {
            $action  = $request->input('action'); // 'inc' or 'dec'
            $current = Cart::getQty($product->id);

            if ($action === 'inc') {
                $qty = $current + 1;
                // Stock limit check
                if ($product->stock !== -1 && $qty > $product->stock) {
                    $qty = $product->stock;
                }
            } elseif ($action === 'dec') {
                $qty = max(0, $current - 1);
            } else {
                $qty = $current;
            }
        } else {
            $qty = max(0, (int) $request->input('qty', 0));
            if ($qty > 0 && $product->stock !== -1 && $qty > $product->stock) {
                $qty = $product->stock;
            }
        }

        Cart::update($product->id, $qty);

        // ✅ Checkout SPA-র জন্য full cart data return করি
        if ($request->wantsJson()) {
            return response()->json($this->cartJsonResponse());
        }

        return back()->with('toast', 'কার্ট আপডেট হয়েছে');
    }

    public function remove(Request $request, Product $product)
    {
        Cart::remove($product->id);

        if ($request->wantsJson()) {
            return response()->json($this->cartJsonResponse());
        }

        return back()->with('toast', 'পণ্য কার্ট থেকে সরানো হয়েছে');
    }

    // ✅ নতুন private helper — checkout SPA-র জন্য full response
    private function cartJsonResponse(): array
    {
        $items    = Cart::items();
        $subtotal = Cart::subtotal();

        return [
            'ok'           => true,
            'count'        => Cart::count(),
            'subtotal'     => $subtotal,
            'delivery_fee' => 60, // default, SPA নিজে zone অনুযায়ী update করবে
            'items'        => $items->map(fn($i) => [
                'id'    => $i['product']->id,
                'name'  => $i['product']->name,
                'price' => (int) $i['product']->price,
                'qty'   => $i['qty'],
                'image' => $i['product']->image_url,
                'total' => (int) ($i['product']->price * $i['qty']),
            ])->values(),
        ];
    }

    public function clear()
    {
        Cart::clear();
        return back()->with('toast', 'কার্ট খালি করা হয়েছে');
    }
}
