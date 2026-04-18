<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Cart::items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('toast', 'কার্ট খালি আছে');
        }
        
        $subtotal = Cart::subtotal();
        $freeDeliveryMin = (int) Setting::get('free_delivery_min', 500);
        $deliveryCharge = (int) Setting::get('delivery_charge', 60);
        $deliveryFee = $subtotal >= $freeDeliveryMin ? 0 : $deliveryCharge;
        $total = $subtotal + $deliveryFee;

        $user = Auth::user();
        $prefill = $user ? [
            'customer_name' => $user->name,
            'phone'         => $user->phone,
            'address'       => $user->address,
        ] : [];

        return view('pages.checkout', compact('items', 'subtotal', 'deliveryFee', 'total', 'prefill'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'  => 'required|string|max:120',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string|max:500',
            'notes'          => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,bkash,nagad',
            'trx_id'         => 'required_if:payment_method,bkash,nagad|nullable|string|min:6|max:50',
        ], [
            'trx_id.required_if' => 'মোবাইল পেমেন্টের জন্য Transaction ID আবশ্যক',
        ]);

        $items = Cart::items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('toast', 'কার্ট খালি আছে');
        }

        $subtotal = Cart::subtotal();
        $freeDeliveryMin = (int) Setting::get('free_delivery_min', 500);
        $deliveryCharge = (int) Setting::get('delivery_charge', 60);
        $deliveryFee = $subtotal >= $freeDeliveryMin ? 0 : $deliveryCharge;
        $total = $subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $deliveryFee, $total) {
            $order = Order::create([
                'user_id'        => Auth::id(),
                'invoice_no'     => 'CH-' . strtoupper(Str::random(8)),
                'customer_name'  => $data['customer_name'],
                'phone'          => $data['phone'],
                'address'        => $data['address'],
                'notes'          => $data['notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'trx_id'         => $data['trx_id'] ?? null,
                'subtotal'       => $subtotal,
                'delivery_fee'   => $deliveryFee,
                'total'          => $total,
                'status'         => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'   => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price'        => $item['product']->price,
                    'quantity'     => $item['qty'],
                    'line_total'   => $item['product']->price * $item['qty'],
                ]);
            }

            return $order;
        });

        Cart::clear();

        return redirect()->route('checkout.success', $order)
            ->with('toast', '🎉 অর্ডার সফল হয়েছে! ইনভয়েস: ' . $order->invoice_no);
    }

    public function success(Order $order)
    {
        return view('pages.checkout-success', compact('order'));
    }
}