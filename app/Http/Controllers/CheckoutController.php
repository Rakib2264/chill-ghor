<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use App\Models\Coupon;
use App\Models\DeliveryZone;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\Setting;
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

        // ─── ঠিকানাগুলো delivery zone সহ লোড করুন ────────────────────────────
        $addresses = $user
            ? $user->addresses()
                ->with('deliveryZone')   // ← zone eager-load
                ->orderByDesc('is_default')
                ->latest()
                ->get()
            : collect();

        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $deliveryZones = DeliveryZone::where('is_active', true)->get();

        // ─── Default delivery fee — ডিফল্ট address-এর zone থেকে নিন ──────────
        $totals = $this->calculateTotals(null, $defaultAddress?->deliveryZone ?? null);

        $couponSession = session('coupon');
        $discount = (int) ($couponSession['discount'] ?? 0);
        $couponCode = $couponSession['code'] ?? null;

        // ─── addresses JSON-এ deliveryZone তথ্য যোগ করুন ─────────────────────
        $addressesWithZone = $addresses->map(function ($addr) {
            return [
                'id' => $addr->id,
                'label' => $addr->label,
                'recipient_name' => $addr->recipient_name,
                'phone' => $addr->phone,
                'area' => $addr->area,
                'address_line' => $addr->address_line,
                'is_default' => $addr->is_default,
                'delivery_zone_id' => $addr->delivery_zone_id,
                // ── zone তথ্য সরাসরি address object-এ ──
                'zone_name' => $addr->deliveryZone?->zone_name ?? '',
                'delivery_charge' => $addr->deliveryZone?->delivery_charge ?? 0,
                'min_order_for_free' => $addr->deliveryZone?->min_order_for_free ?? 1500,
            ];
        });

        return view('pages.checkout', [
            'items' => $items,
            'subtotal' => $totals['subtotal'],
            'deliveryFee' => $totals['deliveryFee'],
            'total' => $totals['total'], // ✅ FIXED
            'discount' => $discount,
            'couponCode' => $couponCode,
            'addresses' => $addressesWithZone,
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

        $zone = DeliveryZone::where('zone_name', $data['delivery_zone'] ?? '')->first();
        $totals = $this->calculateTotals($data['area'] ?? null, $zone);

        $couponSession = session('coupon');
        $couponCode = $couponSession['code'] ?? null;
        $discount = (int) ($couponSession['discount'] ?? 0);

        // ✅ FINAL FIX
        $finalTotal = max(0, $totals['total'] - $discount);

        $order = DB::transaction(function () use ($data, $items, $totals, $zone, $couponCode, $discount, $finalTotal, $couponSession) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_no' => 'CH-'.strtoupper(Str::random(8)),
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

            if (! empty($couponSession['coupon_id'])) {
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

        $this->sendOrderConfirmationEmail($order);

        $this->sendAdminNotificationEmail($order);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'track_url' => route('order.track.form').'?invoice='.$order->invoice_no,
                'message' => '🎉 অর্ডার সফল হয়েছে!',
            ]);
        }

        return redirect()->route('checkout.success', $order)
            ->with('toast', '🎉 অর্ডার সফল হয়েছে! ইনভয়েস: '.$order->invoice_no);
    }

    // ─── Delivery fee API (unchanged) ─────────────────────────────────────────

    public function getDeliveryFee(Request $request)
    {
        $zoneName = $request->input('area');
        $subtotal = (int) $request->input('subtotal', 0);

        $zone = DeliveryZone::where('zone_name', $zoneName)->first();

        if (! $zone) {
            return response()->json([
                'delivery_fee' => 60,  // ✅ Number
                'free_min' => 500,      // ✅ Number
                'zone_name' => 'ডিফল্ট',
            ]);
        }

        // ✅ নিশ্চিত করুন Integer রিটার্ন করছে
        $deliveryFee = ($subtotal >= $zone->min_order_for_free) ? 0 : (int) $zone->delivery_charge;

        return response()->json([
            'delivery_fee' => $deliveryFee,      // ✅ Number
            'free_min' => (int) $zone->min_order_for_free,  // ✅ Number
            'zone_name' => $zone->zone_name,
        ]);
    }

    public function success(Order $order)
    {
        return view('pages.checkout-success', compact('order'));
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    protected function calculateTotals($area = null, $zone = null)
    {
        $subtotal = Cart::subtotal();  // ✅ 20 আসছে (সাব-টোটাল)

        if (! $zone) {
            $zone = DeliveryZone::where('is_active', true)->first();
        }

        $minOrderForFree = $zone->min_order_for_free;
        $deliveryCharge = $zone->delivery_charge;

        if ($subtotal >= $minOrderForFree) {
            $deliveryFee = 0;
        } else {
            $deliveryFee = $deliveryCharge;  // ✅ 60 আসছে
        }

        return [
            'subtotal' => $subtotal,        // 20 ✅
            'deliveryFee' => $deliveryFee,   // 60 ✅
            'total' => $subtotal + $deliveryFee,  // 80 হওয়ার কথা ✅
        ];
    }

    private function sendOrderConfirmationEmail(Order $order)
    {
        try {
            $recipient = null;
            if (! empty($order->customer_email) && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                $recipient = $order->customer_email;
            } elseif ($order->user && ! empty($order->user->email) && filter_var($order->user->email, FILTER_VALIDATE_EMAIL)) {
                $recipient = $order->user->email;
            }
            if (! $recipient) {
                \Log::warning('No valid email for order #'.$order->invoice_no);

                return;
            }

            $rendered = EmailTemplate::render('order.confirmation', [
                'name' => $order->customer_name,
                'order_no' => $order->invoice_no,
                'total' => number_format($order->total),
            ]);
            if (! $rendered) {
                \Log::warning('Email template not found: order.confirmation');

                return;
            }

            $log = EmailLog::create([
                'email_template_id' => $rendered['template_id'] ?? null,
                'recipient_email' => $recipient,
                'recipient_name' => $order->customer_name,
                'subject' => $rendered['subject'],
                'audience' => 'order_confirmation',
                'status' => 'pending',
                'sent_by' => Auth::id(),
            ]);

            Mail::to($recipient)->send(new GenericMail($rendered['subject'], $rendered['body']));
            $log->update(['status' => 'sent', 'sent_at' => now()]);
        } catch (\Throwable $e) {
            if (isset($log)) {
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
            \Log::error('Order email failed for #'.$order->invoice_no.': '.$e->getMessage());
        }
    }

    /**
     * অ্যাডমিনকে নোটিফিকেশন ইমেইল পাঠান
     */
    /**
     * একাধিক অ্যাডমিনকে নোটিফিকেশন ইমেইল পাঠান
     */
    private function sendAdminNotificationEmail(Order $order)
    {
        try {
            // পদ্ধতি ১: সেটিংস থেকে কমা সেপারেটেড ইমেইল
            $adminEmailsString = Setting::get('admin_emails', 'admin@chillghor.com');

            // কমা বা সেমিকোলন দিয়ে আলাদা করা ইমেইলগুলোকে array তে রূপান্তর
            $adminEmails = preg_split('/[\s,;]+/', $adminEmailsString);
            $adminEmails = array_filter($adminEmails, function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            // পদ্ধতি ২: ডাটাবেজ থেকে সব অ্যাডমিন ইউজারের ইমেইল
            // $adminEmails = \App\Models\User::where('is_admin', true)
            //     ->whereNotNull('email')
            //     ->pluck('email')
            //     ->toArray();

            if (empty($adminEmails)) {
                \Log::warning('No admin emails found for notification');

                return;
            }

            // অর্ডার আইটেম HTML তৈরি
            $itemsHtml = '';
            foreach ($order->items as $item) {
                $itemsHtml .= "<tr>
                <td style='padding:8px; border-bottom:1px solid #eee'>{$item->product_name}</td>
                <td style='padding:8px; border-bottom:1px solid #eee'>{$item->quantity}</td>
                <td style='padding:8px; border-bottom:1px solid #eee'>৳{$item->price}</td>
                <td style='padding:8px; border-bottom:1px solid #eee'>৳{$item->line_total}</td>
            </tr>";
            }

            $subject = "🛒 নতুন অর্ডার! #{$order->invoice_no} - চিল ঘর";

            $htmlBody = "
        <div style='font-family: Hind Siliguri, sans-serif; max-width:600px; margin:0 auto'>
            <div style='background:linear-gradient(135deg,#c0392b,#e8671a); padding:20px; text-align:center; color:white'>
                <h2>🍽️ চিল ঘর</h2>
                <p>নতুন অর্ডার এসেছে!</p>
            </div>
            
            <div style='padding:20px; background:#faf6ef'>
                <div style='background:white; padding:15px; border-radius:12px; margin-bottom:15px'>
                    <h3 style='margin:0 0 10px 0'>অর্ডার নং: #{$order->invoice_no}</h3>
                    <p><strong>📅 সময়:</strong> {$order->created_at->format('d/m/Y h:i A')}</p>
                </div>
                
                <div style='background:white; padding:15px; border-radius:12px; margin-bottom:15px'>
                    <h4>👤 গ্রাহকের তথ্য</h4>
                    <p><strong>নাম:</strong> {$order->customer_name}</p>
                    <p><strong>📞 ফোন:</strong> {$order->phone}</p>
                    <p><strong>📧 ইমেইল:</strong> {$order->customer_email}</p>
                    <p><strong>📍 এলাকা:</strong> {$order->area}</p>
                    <p><strong>🏠 ঠিকানা:</strong> {$order->address}</p>
                    ".($order->notes ? "<p><strong>📝 নোট:</strong> {$order->notes}</p>" : '')."
                </div>
                
                <div style='background:white; padding:15px; border-radius:12px; margin-bottom:15px'>
                    <h4>🍕 অর্ডারকৃত আইটেম</h4>
                    <table style='width:100%; border-collapse:collapse'>
                        <thead>
                            <tr><th style='text-align:left; padding:8px; background:#c0392b; color:white'>আইটেম</th>
                                <th style='text-align:center; padding:8px; background:#c0392b; color:white'>পরিমাণ</th>
                                <th style='text-align:right; padding:8px; background:#c0392b; color:white'>দাম</th>
                                <th style='text-align:right; padding:8px; background:#c0392b; color:white'>মোট</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                    </table>
                </div>
                
                <div style='background:white; padding:15px; border-radius:12px; text-align:right'>
                    <p><strong>সাবটোটাল:</strong> ৳{$order->subtotal}</p>
                    <p><strong>ডেলিভারি চার্জ:</strong> ৳{$order->delivery_fee}</p>
                    ".($order->discount > 0 ? "<p><strong>ডিসকাউন্ট:</strong> ৳{$order->discount}</p>" : '')."
                    <h3><strong>মোট:</strong> ৳{$order->total}</h3>
                    <p><strong>💳 পেমেন্ট পদ্ধতি:</strong> ".strtoupper($order->payment_method)."</p>
                </div>
                
                <div style='margin-top:20px; text-align:center'>
                    <a href='".route('admin.orders.show', $order)."' 
                       style='background:#c0392b; color:white; padding:12px 24px; text-decoration:none; border-radius:30px; display:inline-block'>
                       📋 অর্ডার দেখুন ও স্ট্যাটাস আপডেট করুন
                    </a>
                </div>
            </div>
        </div>";

            // ✅ একাধিক অ্যাডমিনকে ইমেইল পাঠান
            foreach ($adminEmails as $adminEmail) {
                try {
                    Mail::to($adminEmail)->send(new GenericMail($subject, $htmlBody));
                    \Log::info("Admin notification sent to {$adminEmail} for order #{$order->invoice_no}");
                } catch (\Exception $e) {
                    \Log::error("Failed to send to {$adminEmail}: ".$e->getMessage());
                }
            }

        } catch (\Throwable $e) {
            \Log::error("Admin notification email failed for #{$order->invoice_no}: ".$e->getMessage());
        }
    }
}
