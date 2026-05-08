@extends('layouts.app')
@section('title', 'কার্ট — চিল ঘর')

@section('content')

    @php
        // Define these OUTSIDE @if so mobile sticky CTA always has access
        $couponDiscount = (int) session('coupon.discount', 0);
        $couponCode = session('coupon.code');
        $subtotal = $subtotal ?? 0;
        $delivery_fee = $delivery_fee ?? 0;
        $finalTotal = max(0, $subtotal + $delivery_fee - $couponDiscount);
        $freeMin = $freeMin ?? 500;
    @endphp

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:py-14 lg:px-8">

        {{-- ===== EMPTY STATE ===== --}}
        @if ($items->isEmpty())
            <div
                class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-charcoal/15 bg-white py-16 px-6 text-center shadow-soft sm:py-24">
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-cream text-5xl mb-4">🛒</div>
                <p class="font-display text-xl font-black sm:text-2xl">কার্ট খালি আছে</p>
                <p class="mt-2 text-sm text-charcoal/55 max-w-xs">আপনার পছন্দের সুস্বাদু খাবার বেছে নিয়ে কার্টে যোগ করুন।
                </p>
                <a href="{{ route('menu.index') }}"
                    class="mt-7 inline-flex items-center gap-2 rounded-full bg-gradient-warm px-7 py-3 text-sm font-black text-white shadow-warm transition hover:scale-105 sm:px-8 sm:py-3.5">
                    🍽️ মেনু দেখুন
                </a>
            </div>

            {{-- ===== CART WITH ITEMS ===== --}}
        @else
            <div class="grid gap-6 lg:grid-cols-[1fr,360px] lg:gap-8">

                {{-- ── Cart Items ── --}}
                <div class="space-y-3">
                    @foreach ($items as $item)
                        @php
                            $product = $item['product'];
                            $qty = $item['qty'];
                            $price = (int) $product->price;
                            $name = $product->name;
                            $image = $product->image_url ?? null;
                            $id = $product->id;
                        @endphp

                        <div
                            class="flex flex-wrap items-center gap-3 rounded-2xl border border-charcoal/8 bg-white p-3 shadow-soft transition hover:shadow-warm sm:flex-nowrap sm:gap-4 sm:p-4">

                            {{-- Image --}}
                            <a href="{{ route('menu.show', $product) }}"
                                class="flex h-16 w-16 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-cream text-3xl sm:h-20 sm:w-20 sm:text-4xl">
                                @if ($image)
                                    <img src="{{ $image }}" alt="{{ $name }}"
                                        class="h-full w-full object-cover">
                                @else
                                    🍽️
                                @endif
                            </a>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1 order-1 sm:order-none basis-[calc(100%-5rem)] sm:basis-auto">
                                <a href="{{ route('menu.show', $product) }}"
                                    class="font-display font-black truncate hover:text-primary text-sm sm:text-base block">{{ $name }}</a>
                                <p class="text-xs text-charcoal/55 mt-0.5 sm:text-sm">
                                    ৳{{ number_format($price) }} <span class="text-charcoal/35">/ পিস</span>
                                </p>
                                <div class="font-black text-primary mt-1 sm:hidden">৳{{ number_format($price * $qty) }}
                                </div>
                            </div>

                            {{-- Qty Controls --}}
                            <form action="{{ route('cart.update', $id) }}" method="POST"
                                class="flex items-center rounded-full border border-charcoal/12 bg-cream/60">
                                @csrf @method('PATCH')
                                <button type="submit" name="action" value="dec"
                                    class="flex h-8 w-8 items-center justify-center rounded-l-full font-black text-charcoal hover:bg-primary hover:text-white transition">−</button>
                                <span class="w-8 text-center font-black text-sm">{{ $qty }}</span>
                                <button type="submit" name="action" value="inc"
                                    class="flex h-8 w-8 items-center justify-center rounded-r-full font-black text-charcoal hover:bg-primary hover:text-white transition">+</button>
                            </form>

                            {{-- Line total (desktop) --}}
                            <div class="hidden sm:block text-right min-w-[80px]">
                                <div class="font-display font-black text-primary">৳{{ number_format($price * $qty) }}</div>
                            </div>

                            {{-- Remove --}}
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="flex h-8 w-8 items-center justify-center rounded-full text-charcoal/30 hover:bg-red-50 hover:text-red-500 transition"
                                    title="সরান">✕</button>
                            </form>
                        </div>
                    @endforeach

                    {{-- Clear Cart --}}
                    <div class="flex justify-end pt-2">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-charcoal/40 hover:text-red-500 transition">
                                🗑️ কার্ট খালি করুন
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ── Order Summary (desktop sticky) ── --}}
                <div class="rounded-3xl border border-charcoal/8 bg-white p-5 shadow-soft h-fit sm:p-6 lg:sticky lg:top-24">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="font-display text-lg font-black">অর্ডার সারাংশ</h2>
                        <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-[10px] font-black text-primary">
                            {{ $items->count() }} আইটেম
                        </span>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-charcoal/60">সাব-টোটাল</dt>
                            <dd class="font-black">৳{{ number_format($subtotal) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-charcoal/60">ডেলিভারি</dt>
                            <dd class="font-black {{ $delivery_fee == 0 ? 'text-green-600' : '' }}">
                                {{ $delivery_fee == 0 ? 'ফ্রি' : '৳' . number_format($delivery_fee) }}
                            </dd>
                        </div>
                        @if ($couponDiscount > 0)
                            <div class="flex justify-between text-green-600">
                                <dt>কুপন ({{ $couponCode }})</dt>
                                <dd class="font-black">−৳{{ number_format($couponDiscount) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-dashed border-charcoal/15 pt-3 text-base">
                            <dt class="font-display font-black">মোট</dt>
                            <dd class="font-display font-black text-xl text-primary">৳{{ number_format($finalTotal) }}</dd>
                        </div>
                    </dl>

                    {{-- Coupon --}}
                    <div class="mt-5 border-t border-charcoal/10 pt-5">
                        @if ($couponCode)
                            <div
                                class="flex items-center justify-between rounded-xl bg-green-50 px-3 py-2.5 text-xs ring-1 ring-green-200">
                                <span class="font-black text-green-700">✅ {{ $couponCode }} প্রয়োগ হয়েছে</span>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-black text-red-500 hover:underline">বাদ দিন</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" placeholder="কুপন কোড" required
                                    class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-3 py-2.5 text-sm uppercase placeholder:normal-case focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/15">
                                <button type="submit"
                                    class="rounded-xl bg-charcoal px-4 py-2.5 text-xs font-black text-white hover:bg-primary transition">
                                    প্রয়োগ
                                </button>
                            </form>
                            @if (session('coupon_error'))
                                <p class="mt-2 text-xs font-bold text-red-500">{{ session('coupon_error') }}</p>
                            @endif
                        @endif
                    </div>

                    {{-- Free delivery nudge --}}
                    @if ($delivery_fee == 0 && $subtotal > 0)
                        <div
                            class="mt-4 rounded-xl bg-green-50 px-4 py-2.5 text-xs font-bold text-green-700 ring-1 ring-green-200">
                            ✅ আপনি ফ্রি ডেলিভারি পাচ্ছেন!
                        </div>
                    @elseif ($delivery_fee > 0)
                        <div class="mt-4 rounded-xl bg-cream px-4 py-2.5 text-xs text-charcoal/60">
                            💡 ৳{{ number_format(max(0, $freeMin - $subtotal)) }} বেশি অর্ডার করলে ফ্রি ডেলিভারি পাবেন।
                        </div>
                    @endif

                    {{-- Desktop checkout button --}}
                    <a href="{{ route('checkout.index') }}"
                        class="mt-6 hidden lg:flex w-full items-center justify-center gap-2 rounded-full bg-gradient-warm py-3.5 text-center text-sm font-black text-white shadow-warm transition hover:scale-[1.02]">
                        ✅ চেকআউট করুন
                    </a>
                    <a href="{{ route('menu.index') }}"
                        class="mt-3 hidden lg:block text-center text-xs font-bold text-charcoal/50 hover:text-primary">
                        ← মেনুতে ফিরে যান
                    </a>
                </div>
            </div>

            {{-- ── Mobile sticky checkout CTA (inside @else so $finalTotal always exists) ── --}}
            <div class="fixed inset-x-0 bottom-[68px] z-40 px-4 lg:hidden"
                style="padding-bottom: env(safe-area-inset-bottom);">
                <a href="{{ route('checkout.index') }}"
                    class="flex w-full items-center justify-between gap-3 rounded-2xl bg-gradient-warm px-5 py-3.5 shadow-warm">
                    <span class="text-xs font-black text-white">
                        ৳{{ number_format($finalTotal) }}
                        <span class="opacity-70 font-bold"> · {{ $items->count() }} আইটেম</span>
                    </span>
                    <span class="inline-flex items-center gap-2 text-sm font-black text-white">
                        চেকআউট →
                    </span>
                </a>
            </div>

        @endif {{-- end items check --}}

    </div>
@endsection
