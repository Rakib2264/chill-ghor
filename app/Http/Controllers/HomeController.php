<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();
        $popular    = Product::where('popular', true)->where('active', true)->take(6)->get();

        return view('pages.home', compact('categories', 'popular'));
    }
}
