<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('sort_order')->get();

        $query = Product::where('active', true);

        if ($request->filled('category') && $request->category !== 'all') {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat) $query->where('category_id', $cat->id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $products = $query->orderBy('id')->get();

        return view('pages.menu', [
            'categories' => $categories,
            'products'   => $products,
            'activeCat'  => $request->get('category', 'all'),
            'search'     => $request->get('q', ''),
        ]);
    }

    public function show(Product $product)
    {
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->take(4)->get();

        return view('pages.product', compact('product', 'related'));
    }
}
