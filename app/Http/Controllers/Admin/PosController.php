<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();
        $products = Product::where('active', true)->with('category')->latest()->get();
        return view('admin.pos.index', compact('categories', 'products'));
    }

    public function searchProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $cat = $request->input('category');

        $products = Product::where('active', true)
            ->when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->when($cat, fn($qq) => $qq->whereHas('category', fn($c) => $c->where('slug', $cat)))
            ->limit(60)->get(['id', 'name', 'price', 'image']);

        return response()->json($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (int) $p->price,
            'image' => $p->image_url ?? null,
        ])->values());
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'image' => $product->image_url ?? null,
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:30',
            'payment_method' => 'required|in:cod,bkash,nagad,cash',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'discount' => 'nullable|integer|min:0',
        ]);

        $order = DB::transaction(function () use ($data) {
            $subtotal = 0;
            $itemRows = [];

            foreach ($data['items'] as $row) {
                $p = Product::find($row['id']);
                if (!$p) continue;
                $line = $p->price * $row['qty'];
                $subtotal += $line;
                $itemRows[] = [
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'price' => (int) $p->price,
                    'quantity' => (int) $row['qty'],
                    'line_total' => $line,
                ];
            }

            $discount = (int) ($data['discount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_no' => 'POS-' . strtoupper(Str::random(8)),
                'customer_name' => $data['customer_name'] ?: 'Walk-in Customer',
                'phone' => $data['phone'] ?: '-',
                'address' => 'In-Store (POS)',
                'payment_method' => $data['payment_method'] === 'cash' ? 'cod' : $data['payment_method'],
                'subtotal' => $subtotal,
                'delivery_fee' => 0,
                'total' => $total,
                'status' => 'delivered',
            ]);

            foreach ($itemRows as $r) {
                $order->items()->create($r);
            }
            return $order;
        });

        return response()->json([
            'ok' => true,
            'invoice_no' => $order->invoice_no,
            'order_id' => $order->id,
            'total' => $order->total,
            'message' => '✅ অর্ডার সফল!',
        ]);
    }
}
