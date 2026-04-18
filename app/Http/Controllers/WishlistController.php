<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use App\Support\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $ids = Wishlist::ids();
        $products = Product::whereIn('id', $ids)->get();
        return view('pages.wishlist', compact('products'));
    }

    public function toggle(Product $product)
    {
        $added = Wishlist::toggle($product->id);
        return back()->with('toast', $added ? '❤️ উইশলিস্টে যোগ হয়েছে' : 'উইশলিস্ট থেকে বাদ');
    }

    public function moveToCart(Product $product)
    {
        Cart::add($product->id, 1);
        Wishlist::remove($product->id);
        return back()->with('toast', 'কার্টে সরানো হয়েছে');
    }
}
