<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        // NEW: Get products configured for home page instead of just popular ones
        $homeProducts = Product::forHomePage(12)->get();

        // If no products are configured for home page, fallback to popular products
        if ($homeProducts->isEmpty()) {
            $homeProducts = Product::where('popular', true)
                ->where('active', true)
                ->take(8)
                ->get();
        }

        return view('pages.home', compact('categories', 'homeProducts'));
    }
}
