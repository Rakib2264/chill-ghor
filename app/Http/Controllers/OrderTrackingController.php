<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    public function showForm()
    {
        return view('pages.order-track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string|max:50',
        ]);

        $order = Order::where('invoice_no', $request->invoice_no)->first();

        if (!$order) {
            return back()->with('error', '❌ এই ইনভয়েস নম্বরের কোনো অর্ডার পাওয়া যায়নি।');
        }

        if (Auth::check() && Auth::id() != $order->user_id) {
            return back()->with('error', '❌ আপনি এই অর্ডার দেখার অনুমতি নেই।');
        }

        return view('pages.order-status', compact('order'));
    }

    public function status($invoice_no)
    {
        $order = Order::where('invoice_no', $invoice_no)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $statuses = [
            'pending'           => ['label' => 'অপেক্ষমান', 'icon' => '⏳', 'color' => 'yellow', 'step' => 1],
            'confirmed'         => ['label' => 'নিশ্চিত করা হয়েছে', 'icon' => '✅', 'color' => 'blue', 'step' => 2],
            'preparing'         => ['label' => 'প্রস্তুত হচ্ছে', 'icon' => '🍳', 'color' => 'orange', 'step' => 3],
            'ready_for_delivery'=> ['label' => 'ডেলিভারির জন্য প্রস্তুত', 'icon' => '📦', 'color' => 'purple', 'step' => 4],
            'out_for_delivery'  => ['label' => 'ডেলিভারি রাস্তায়', 'icon' => '🚚', 'color' => 'indigo', 'step' => 5],
            'delivered'         => ['label' => 'ডেলিভারি সম্পন্ন', 'icon' => '🎉', 'color' => 'green', 'step' => 6],
            'cancelled'         => ['label' => 'বাতিল করা হয়েছে', 'icon' => '❌', 'color' => 'red', 'step' => 7],
        ];

        $currentStatus = $statuses[$order->status] ?? [
            'label' => ucfirst($order->status), 'icon' => '📋', 'color' => 'gray', 'step' => 0
        ];

        return response()->json([
            'success' => true,
            'order' => [
                'invoice_no'        => $order->invoice_no,
                'customer_name'     => $order->customer_name,
                'total'             => number_format($order->total, 2),
                'payment_method'    => $order->payment_method,
                'created_at'        => $order->created_at->format('d M Y, h:i A'),
                'estimated_delivery'=> $order->created_at->addMinutes(45)->format('h:i A'),
                'status_text'       => $currentStatus['label'],
                'status_icon'       => $currentStatus['icon'],
                'status_color'      => $currentStatus['color'],
                'status_step'       => $currentStatus['step'],
            ]
        ]);
    }
}