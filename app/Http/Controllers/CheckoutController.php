<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Cart::items();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('toast', 'কার্ট খালি আছে');
        }

        $user = Auth::user();
        $addresses = $user ? $user->addresses()->orderByDesc('is_default')->latest()->get() : collect();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $deliveryZones = DeliveryZone::where('is_active', true)->get();
        $totals = $this->calculateTotals(null, null);

        $couponSession = session('coupon');
        $discount = (int) ($couponSession['discount'] ?? 0);
        $couponCode = $couponSession['code'] ?? null;

        return view('pages.checkout', [
            'items' => $items,
            'subtotal' => $totals['subtotal'],
            'deliveryFee' => $totals['deliveryFee'],
            'total' => max(0, $totals['total'] - $discount),
            'discount' => $discount,
            'couponCode' => $couponCode,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'deliveryZones' => $deliveryZones,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'address' => 'required|string|max:500',
            'area' => 'nullable|string|max:120',
            'delivery_zone' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,bkash,nagad',
            'trx_id' => 'required_if:payment_method,bkash,nagad|nullable|string|min:6|max:50',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $items = Cart::items();
        if ($items->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'কার্ট খালি আছে'], 422);
        }

        // Calculate delivery fee based on zone
        $zone = DeliveryZone::where('zone_name', $data['delivery_zone'] ?? '')->first();
        $totals = $this->calculateTotals($data['area'] ?? null, $zone);

        // Apply coupon if present in session
        $couponSession = session('coupon');
        $couponCode = $couponSession['code'] ?? null;
        $discount = (int) ($couponSession['discount'] ?? 0);
        $finalTotal = max(0, $totals['total'] - $discount);

        $order = DB::transaction(function () use ($data, $items, $totals, $zone, $couponCode, $discount, $finalTotal, $couponSession) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_no' => 'CH-' . strtoupper(Str::random(8)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'area' => $data['area'] ?? null,
                'delivery_zone' => $zone->zone_name ?? null,
                'notes' => $data['notes'] ?? null,
                'payment_method' => $data['payment_method'],
                'trx_id' => $data['trx_id'] ?? null,
                'subtotal' => $totals['subtotal'],
                'delivery_fee' => $totals['deliveryFee'],
                'total' => $finalTotal,
                'coupon_code' => $couponCode,
                'discount' => $discount,
                'status' => 'pending',
            ]);

            // Increment coupon usage
            if (!empty($couponSession['coupon_id'])) {
                Coupon::where('id', $couponSession['coupon_id'])->increment('used_count');
            }

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['product']->price,
                    'quantity' => $item['qty'],
                    'line_total' => $item['product']->price * $item['qty'],
                ]);
            }

            return $order;
        });

        Cart::clear();
        session()->forget('coupon');

        // Send order confirmation email
        $this->sendOrderConfirmationEmail($order);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'redirect' => route('checkout.success', $order),
                'message' => '🎉 অর্ডার সফল হয়েছে!',
            ]);
        }

        return redirect()->route('checkout.success', $order)
            ->with('toast', '🎉 অর্ডার সফল হয়েছে! ইনভয়েস: ' . $order->invoice_no);
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail(Order $order)
    {
        try {
            // Get valid recipient email
            $recipient = null;

            if (!empty($order->customer_email) && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                $recipient = $order->customer_email;
            } elseif ($order->user && !empty($order->user->email) && filter_var($order->user->email, FILTER_VALIDATE_EMAIL)) {
                $recipient = $order->user->email;
            }

            if (!$recipient) {
                \Log::warning('No valid email found for order #' . $order->invoice_no);
                return;
            }

            // Render email template
            $rendered = EmailTemplate::render('order.confirmation', [
                'name' => $order->customer_name,
                'order_no' => $order->invoice_no,
                'total' => number_format($order->total),
            ]);

            if (!$rendered) {
                \Log::warning('Email template not found: order.confirmation');
                return;
            }

            // Create email log
            $log = EmailLog::create([
                'email_template_id' => $rendered['template_id'] ?? null,
                'recipient_email' => $recipient,
                'recipient_name' => $order->customer_name,
                'subject' => $rendered['subject'],
                'audience' => 'order_confirmation',
                'status' => 'pending',
                'sent_by' => Auth::id(),
            ]);

            // Send email
            Mail::to($recipient)->send(new GenericMail($rendered['subject'], $rendered['body']));

            // Update log
            $log->update(['status' => 'sent', 'sent_at' => now()]);

            \Log::info('Order confirmation email sent to: ' . $recipient . ' for order #' . $order->invoice_no);
        } catch (\Throwable $e) {
            if (isset($log)) {
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
            \Log::error('Order email failed for order #' . $order->invoice_no . ': ' . $e->getMessage());
        }
    }

    public function calculateDeliveryFee($area, $subtotal)
    {
        $zone = DeliveryZone::where('zone_name', 'like', "%{$area}%")->first();
        if (!$zone) {
            $zone = DeliveryZone::where('zone_name', 'বনগ্রাম এলাকা')->first();
        }

        if ($subtotal >= $zone->min_order_for_free) {
            return 0;
        }

        return $zone->delivery_charge;
    }

    protected function calculateTotals($area = null, $zone = null)
    {
        $subtotal = Cart::subtotal();
        $deliveryFee = 0;

        if ($zone) {
            $deliveryFee = $subtotal >= $zone->min_order_for_free ? 0 : $zone->delivery_charge;
        } else {
            $defaultZone = DeliveryZone::where('is_active', true)->first();
            $deliveryFee = $subtotal >= ($defaultZone->min_order_for_free ?? 500) ? 0 : ($defaultZone->delivery_charge ?? 60);
        }

        return [
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
        ];
    }

    public function success(Order $order)
    {
        return view('pages.checkout-success', compact('order'));
    }

    public function getDeliveryFee(Request $request)
    {
        $area = $request->input('area');
        $subtotal = (int) $request->input('subtotal', 0);

        // Find matching delivery zone
        $zone = DeliveryZone::where('zone_name', 'like', "%{$area}%")->first();

        if (!$zone) {
            $zone = DeliveryZone::where('is_active', true)->first();
        }

        if (!$zone) {
            return response()->json([
                'delivery_fee' => 60,
                'total' => $subtotal + 60,
                'zone' => 'ডিফল্ট',
                'free_min' => 500,
            ]);
        }

        $deliveryFee = $subtotal >= $zone->min_order_for_free ? 0 : $zone->delivery_charge;

        return response()->json([
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
            'zone' => $zone->zone_name,
            'free_min' => $zone->min_order_for_free,
        ]);
    }
}
