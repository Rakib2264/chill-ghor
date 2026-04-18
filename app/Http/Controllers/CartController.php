<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Support\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::items();
        $subtotal = Cart::subtotal();
        $deliveryFee = $subtotal >= 500 || $subtotal === 0 ? 0 : 60;
        $total = $subtotal + $deliveryFee;

        return view('pages.cart', compact('items', 'subtotal', 'deliveryFee', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));
        Cart::add($product->id, $qty);

        return back()->with('toast', "🛒 কার্টে যোগ হয়েছে — {$product->name}");
    }

    public function update(Request $request, Product $product)
    {
        Cart::update($product->id, (int) $request->input('qty', 1));
        return back();
    }

    public function remove(Product $product)
    {
        Cart::remove($product->id);
        return back()->with('toast', 'পণ্য সরানো হয়েছে');
    }

    public function clear()
    {
        Cart::clear();
        return back()->with('toast', 'কার্ট খালি করা হয়েছে');
    }
}
