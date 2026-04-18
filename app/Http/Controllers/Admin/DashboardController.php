<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'orders_today'   => Order::whereDate('created_at', $today)->count(),
            'sales_today'    => (int) Order::whereDate('created_at', $today)->sum('total'),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'orders_total'   => Order::count(),
            'products_total' => Product::count(),
            'categories'     => Category::count(),
        ];

        $recent = Order::latest()->take(8)->get();

        // Last 7 days sales for chart
        $salesByDay = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $date->format('M d'),
                'total' => (int) Order::whereDate('created_at', $date)->sum('total'),
            ];
        });

        return view('admin.dashboard', compact('stats', 'recent', 'salesByDay'));
    }
}
