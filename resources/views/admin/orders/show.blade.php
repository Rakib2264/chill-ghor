@extends('admin.layouts.app')
@section('title', $order->invoice_no)
@section('header', 'অর্ডার: ' . $order->invoice_no)

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr,360px]">

        <div class="space-y-6">
            <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-widest text-primary">ইনভয়েস</div>
                        <div class="mt-1 font-mono text-2xl font-bold">{{ $order->invoice_no }}</div>
                        <div class="mt-1 text-xs text-charcoal/60">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div class="text-right">@include('admin.partials.status-badge', ['status' => $order->status])</div>
                </div>

                <table class="mt-6 w-full text-sm">
                    <thead class="text-xs uppercase tracking-wider text-charcoal/60">
                        <tr class="border-b border-charcoal/10">
                            <th class="py-2 text-left">পণ্য</th>
                            <th class="text-center">মূল্য</th>
                            <th class="text-center">পরিমাণ</th>
                            <th class="text-right">মোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $it)
                            <tr class="border-b border-charcoal/5">
                                <td class="py-3 font-bold">{{ $it->product_name }}</td>
                                <td class="text-center">৳{{ number_format($it->price) }}</td>
                                <td class="text-center">×{{ $it->quantity }}</td>
                                <td class="text-right font-bold">৳{{ number_format($it->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Bill Details with Discount --}}
                <div class="mt-6 border-t border-charcoal/10 pt-4">
                    <div class="ml-auto w-full max-w-xs space-y-2">

                        {{-- Subtotal --}}
                        <div class="flex justify-between text-sm">
                            <dt class="text-charcoal/60">সাব-টোটাল</dt>
                            <dd class="font-bold">৳{{ number_format($order->subtotal) }}</dd>
                        </div>

                        {{-- Discount (if exists) --}}
                        @php
                            $discountAmount = $order->subtotal - ($order->total - $order->delivery_fee);
                            // অথবা যদি আপনার orders টেবিলে discount কলাম থাকে:
                            // $discountAmount = $order->discount ?? 0;
                        @endphp

                        @if ($discountAmount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <dt class="flex items-center gap-1">
                                    <span>🏷️ ডিসকাউন্ট</span>
                                    <span class="text-xs bg-green-100 px-1.5 py-0.5 rounded-full">সেভিংস</span>
                                </dt>
                                <dd class="font-bold">- ৳{{ number_format($discountAmount) }}</dd>
                            </div>
                        @endif

                        {{-- After Discount (if discount exists) --}}
                        @if ($discountAmount > 0)
                            <div
                                class="flex justify-between text-sm text-charcoal/70 border-b border-dashed border-charcoal/10 pb-2">
                                <dt>ডিসকাউন্ট পরবর্তী মূল্য</dt>
                                <dd class="font-semibold">৳{{ number_format($order->subtotal - $discountAmount) }}</dd>
                            </div>
                        @endif

                        {{-- Delivery Fee --}}
                        <div class="flex justify-between text-sm">
                            <dt class="text-charcoal/60">🚚 ডেলিভারি ফি</dt>
                            <dd class="font-bold">
                                @if ($order->delivery_fee > 0)
                                    ৳{{ number_format($order->delivery_fee) }}
                                @else
                                    <span class="text-green-600">ফ্রি</span>
                                @endif
                            </dd>
                        </div>

                        {{-- Total --}}
                        <div class="flex justify-between border-t border-charcoal/10 pt-2 text-lg">
                            <dt class="font-display font-bold">মোট টাকা</dt>
                            <dd class="font-bold gradient-text">৳{{ number_format($order->total) }}</dd>
                        </div>

                        {{-- Calculation Formula --}}
                        <div
                            class="text-[10px] text-charcoal/40 text-center pt-2 border-t border-dashed border-charcoal/10">
                            @php
                                $afterDiscount = $order->subtotal - $discountAmount;
                            @endphp
                            {{ number_format($order->subtotal) }}
                            @if ($discountAmount > 0)
                                - {{ number_format($discountAmount) }}
                            @endif
                            @if ($order->delivery_fee > 0)
                                + {{ number_format($order->delivery_fee) }}
                            @endif
                            = {{ number_format($order->total) }}
                        </div>

                        {{-- Savings Badge --}}
                        @if ($discountAmount > 0)
                            <div class="bg-green-50 rounded-lg p-2 text-center mt-2">
                                <span class="text-xs font-bold text-green-600">
                                    🎉 ডিসকাউন্টে ৳{{ number_format($discountAmount) }} সাশ্রয় হয়েছে!
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h3 class="font-display font-bold">👤 কাস্টমার তথ্য</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-charcoal/60">নাম</dt>
                        <dd class="font-bold">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-charcoal/60">ফোন</dt>
                        <dd class="font-bold">
                            <a href="tel:{{ $order->phone }}" class="hover:text-primary">{{ $order->phone }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-charcoal/60">ঠিকানা</dt>
                        <dd class="font-bold">{{ $order->address }}</dd>
                    </div>
                    @if ($order->notes)
                        <div>
                            <dt class="text-xs text-charcoal/60">নোট</dt>
                            <dd class="text-charcoal/80">{{ $order->notes }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-charcoal/60">পেমেন্ট মেথড</dt>
                        <dd class="font-bold">
                            @php
                                $paymentLabels = [
                                    'cod' => '💵 ক্যাশ অন ডেলিভারি',
                                    'cash' => '💵 ক্যাশ',
                                    'bkash' => '📱 বিকাশ',
                                    'nagad' => '📱 নগদ',
                                ];
                                $paymentLabel =
                                    $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method);
                            @endphp
                            {{ $paymentLabel }}
                            @if ($order->trx_id)
                                <span class="font-mono text-xs text-charcoal/60 block mt-1">ট্রানজ্যাকশন:
                                    {{ $order->trx_id }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h3 class="font-display font-bold">📊 অর্ডার সামারি</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-charcoal/60">মোট আইটেম</span>
                        <span class="font-bold">{{ $order->items->sum('quantity') }} টি</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-charcoal/60">পণ্যের ধরণ</span>
                        <span class="font-bold">{{ $order->items->count() }} টি</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-charcoal/60">অর্ডার করা হয়েছে</span>
                        <span class="font-bold">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-charcoal/60">স্ট্যাটাস</span>
                        <span>@include('admin.partials.status-badge', ['status' => $order->status])</span>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h3 class="font-display font-bold">🔄 স্ট্যাটাস আপডেট</h3>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
                        @foreach (['pending' => '⏳ বকেয়া', 'confirmed' => '✅ কনফার্মড', 'preparing' => '🍳 রান্নায়', 'delivered' => '🚚 ডেলিভার্ড', 'cancelled' => '❌ বাতিল'] as $val => $label)
                            <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full rounded-full bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">
                        স্ট্যাটাস আপডেট করুন
                    </button>
                </form>

                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                    onsubmit="return confirm('⚠️ অর্ডারটি মুছে ফেলবেন? এই কাজটি অপরিবর্তনীয়!')" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-full border border-red-200 bg-white py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-50 hover:border-red-300">
                        🗑️ অর্ডার মুছুন
                    </button>
                </form>
            </section>

            <a href="{{ route('admin.orders.index') }}"
                class="block text-center text-xs font-bold text-charcoal/60 transition hover:text-primary">
                ← সব অর্ডার দেখুন
            </a>
        </aside>
    </div>
@endsection
