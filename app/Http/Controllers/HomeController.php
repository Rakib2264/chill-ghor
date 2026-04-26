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

        $homeProducts = Product::where('active', true)
            ->where('show_on_home', true)
            ->orderBy('home_order')
            ->orderBy('id')
            ->take(12)
            ->get();

        if ($homeProducts->isEmpty()) {
            $homeProducts = Product::where('popular', true)
                ->where('active', true)
                ->take(8)
                ->get();
        }

        // ✅ এটা যোগ করুন
        $ads = Advertisement::forPage('home');

        return view('pages.home', compact('categories', 'homeProducts', 'ads'));
    }
}