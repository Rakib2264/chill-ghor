<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'orders_today'    => Order::whereDate('created_at', $today)->count(),
            'sales_today'     => (int) Order::whereDate('created_at', $today)->sum('total'),
            'orders_pending'  => Order::where('status', 'pending')->count(),
            'orders_total'    => Order::count(),
            'sales_month'     => (int) Order::where('created_at', '>=', $monthStart)->sum('total'),
            'sales_total'     => (int) Order::sum('total'),
            'products_total'  => Product::count(),
            'categories'      => Category::count(),
            'users_total'     => User::count(),
        ];

        $recent = Order::latest()->take(8)->get();

        // Last 14 days sales for chart
        $salesByDay = collect(range(13, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $date->format('M d'),
                'total' => (int) Order::whereDate('created_at', $date)->sum('total'),
                'count' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        // Order status distribution
        $statusCounts = Order::select('status', DB::raw('count(*) as c'))
            ->groupBy('status')->pluck('c', 'status')->toArray();
        $allStatuses = ['pending','confirmed','preparing','out_for_delivery','delivered','cancelled'];
        $statusData = collect($allStatuses)->map(fn($s) => [
            'status' => $s,
            'count'  => (int) ($statusCounts[$s] ?? 0),
        ]);

        // Top 5 products (by quantity sold)
        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(line_total) as revenue'))
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->take(5)->get();

        // Payment method split
        $paymentSplit = Order::select('payment_method', DB::raw('count(*) as c'))
            ->groupBy('payment_method')->pluck('c', 'payment_method')->toArray();

        return view('admin.dashboard', compact(
            'stats', 'recent', 'salesByDay', 'statusData', 'topProducts', 'paymentSplit'
        ));
    }
}
