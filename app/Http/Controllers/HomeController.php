<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        // হোম পেজের জন্য নির্বাচিত প্রোডাক্ট
        $homeProducts = Product::forHomePage(16)->get();

        // Fallback: popular products
        if ($homeProducts->isEmpty()) {
            $homeProducts = Product::where('popular', true)
                ->where('active', true)
                ->take(8)
                ->get();
        }

        // ✅ Ads for home page
        $ads = Advertisement::forPage('home');

        return view('pages.home', compact('categories', 'homeProducts', 'ads'));
    }
}