<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('invoice_no', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('customer_name', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();
        $statuses = ['all', 'pending', 'confirmed', 'preparing', 'delivered', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,delivered,cancelled',
        ]);
        $order->update($data);
        return back()->with('toast', '✅ স্ট্যাটাস আপডেট হয়েছে');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('toast', '🗑️ অর্ডার মুছে ফেলা হয়েছে');
    }
}
