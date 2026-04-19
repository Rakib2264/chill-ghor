<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Support\Cart;

class CartController extends Controller
{
    public function index()
    {
        return view('pages.cart', $this->payload());
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));
        Cart::add($product->id, $qty);

        if ($request->wantsJson()) return $this->jsonState("🛒 কার্টে যোগ হয়েছে — {$product->name}");
        return back()->with('toast', "🛒 কার্টে যোগ হয়েছে — {$product->name}");
    }

    public function update(Request $request, Product $product)
    {
        $action = $request->input('action');
        $current = collect(session('cart', []))->get($product->id, 1);
        $qty = match($action) {
            'inc' => $current + 1,
            'dec' => max(0, $current - 1),
            default => max(0, (int) $request->input('qty', 1)),
        };
        Cart::update($product->id, $qty);

        if ($request->wantsJson()) return $this->jsonState();
        return back();
    }

    public function remove(Request $request, Product $product)
    {
        Cart::remove($product->id);
        if ($request->wantsJson()) return $this->jsonState('পণ্য সরানো হয়েছে');
        return back()->with('toast', 'পণ্য সরানো হয়েছে');
    }

    public function clear(Request $request)
    {
        Cart::clear();
        if ($request->wantsJson()) return $this->jsonState('কার্ট খালি করা হয়েছে');
        return back()->with('toast', 'কার্ট খালি করা হয়েছে');
    }

    /* ---------- helpers ---------- */
    protected function payload(): array
    {
        $items = Cart::items();
        $subtotal = Cart::subtotal();
        $freeMin = (int) Setting::get('free_delivery_min', 500);
        $charge  = (int) Setting::get('delivery_charge', 60);
        $deliveryFee = $subtotal === 0 ? 0 : ($subtotal >= $freeMin ? 0 : $charge);
        return [
            'items'        => $items,
            'subtotal'     => $subtotal,
            'delivery_fee' => $deliveryFee,
            'deliveryFee'  => $deliveryFee,
            'total'        => $subtotal + $deliveryFee,
            'freeMin'      => $freeMin,
        ];
    }

    protected function jsonState(?string $toast = null)
    {
        $p = $this->payload();
        return response()->json([
            'ok'           => true,
            'toast'        => $toast,
            'count'        => Cart::count(),
            'subtotal'     => $p['subtotal'],
            'delivery_fee' => $p['deliveryFee'],
            'total'        => $p['total'],
            'free_min'     => $p['freeMin'],
            'items'        => $p['items']->map(fn($i) => [
                'id'    => $i['product']->id,
                'name'  => $i['product']->name,
                'price' => (int) $i['product']->price,
                'qty'   => $i['qty'],
                'image' => $i['product']->image,
                'total' => (int) ($i['product']->price * $i['qty']),
            ])->values(),
        ]);
    }
}
