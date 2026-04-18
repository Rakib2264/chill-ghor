<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($q) {
            $q->where('active', true)->orderBy('name');
        }])->orderBy('sort_order')->get();

        $products = Product::where('active', true)
            ->with('category')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (int) $product->price,
                    'category_id' => $product->category_id,
                    'image_url' => $product->image_url ?? asset('images/food/default.jpg'),
                    'description' => $product->description,
                ];
            });

        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->where('order_type', 'dine_in')
            ->with('items.product')
            ->latest()
            ->take(10)
            ->get();
            
        $todayOrders = Order::whereDate('created_at', today())
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.pos.index', compact('categories', 'products', 'activeOrders', 'todayOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:120',
            'customer_phone' => 'nullable|string|max:20',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'table_number' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bkash,nagad',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $total = $request->total;
            $paid = $request->paid_amount;
            $due = max(0, $total - $paid);
            $paymentStatus = $due > 0 ? 'partial' : 'paid';

            $order = Order::create([
                'invoice_no' => 'INV-' . date('ymd') . '-' . strtoupper(Str::random(4)),
                'customer_name' => $request->customer_name ?: 'Walking Customer',
                'phone' => $request->customer_phone ?? 'N/A',
                'address' => $request->address ?? 'N/A',
                'notes' => $request->notes,
                'order_type' => $request->order_type,
                'table_number' => $request->table_number,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'delivery_fee' => 0,
                'total' => $total,
                'paid_amount' => $paid,
                'due_amount' => $due,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'status' => $request->order_type === 'delivery' ? 'pending' : 'confirmed',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $product->name,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'line_total' => $item['price'] * $item['quantity'],
                    ]);
                }
            }
            
            DB::commit();

            // Generate invoice URL
            $invoiceUrl = route('admin.pos.invoice.print', $order);
            
            // Store success message in session for toast
            session()->flash('toast', "✅ অর্ডার সফল! ইনভয়েস: {$order->invoice_no} | মোট: ৳" . number_format($total));

            return response()->json([
                'success' => true,
                'message' => 'অর্ডার সফলভাবে সম্পন্ন হয়েছে',
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'invoice_url' => $invoiceUrl,
                'redirect' => route('admin.pos.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('category')
            ->take(20)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (int) $product->price,
                    'category_id' => $product->category_id,
                    'image_url' => $product->image_url ?? asset('images/food/default.jpg'),
                ];
            });
        
        return response()->json(['products' => $products]);
    }

    public function quickProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->name,
            'active' => true,
            'image' => 'images/food/default.jpg',
        ]);

        session()->flash('toast', "✅ {$product->name} পণ্য যোগ করা হয়েছে");

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (int) $product->price,
                'category_id' => $product->category_id,
                'image_url' => $product->image_url ?? asset('images/food/default.jpg'),
            ]
        ]);
    }

    public function printInvoice(Order $order)
    {
        $order->load('items.product', 'createdBy');
        return view('admin.pos.print-invoice', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);
        
        session()->flash('toast', "✅ অর্ডার স্ট্যাটাস আপডেট হয়েছে");

        return response()->json(['success' => true]);
    }

    public function addPayment(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $order->due_amount
        ]);

        $newPaid = $order->paid_amount + $request->amount;
        $newDue = $order->total - $newPaid;

        $order->update([
            'paid_amount' => $newPaid,
            'due_amount' => $newDue,
            'payment_status' => $newDue <= 0 ? 'paid' : 'partial',
        ]);

        session()->flash('toast', "✅ পেমেন্ট যোগ করা হয়েছে: ৳" . number_format($request->amount));

        return response()->json([
            'success' => true,
            'message' => 'পেমেন্ট যোগ করা হয়েছে'
        ]);
    }
}