@extends('layouts.app')
@section('title', 'কার্ট — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-14 lg:px-8">

  <h1 class="font-display text-2xl font-bold mb-6 sm:text-3xl lg:text-4xl">🛒 আপনার কার্ট</h1>

  @if ($items->isEmpty())
    <div class="flex flex-col items-center justify-center rounded-3xl border border-charcoal/10 bg-white py-16 px-6 text-center shadow-soft sm:py-24">
      <div class="text-6xl mb-4 sm:text-7xl">🛒</div>
      <p class="font-display text-lg font-bold mb-2 sm:text-xl">কার্ট খালি আছে</p>
      <p class="text-sm text-charcoal/55 mb-6 sm:mb-8">আপনার পছন্দের খাবার বেছে নিন।</p>
      <a href="{{ route('menu.index') }}"
         class="rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm transition hover:scale-105 sm:px-8 sm:py-3.5">
        মেনু দেখুন
      </a>
    </div>
  @else
    <div class="grid gap-6 lg:grid-cols-[1fr,360px] lg:gap-8">

      {{-- Cart Items --}}
      <div class="space-y-3">
        @foreach ($items as $item)
          @php
            $product = $item['product'];
            $qty     = $item['qty'];
            $price   = (int) $product->price;
            $name    = $product->name;
            $image   = $product->image_url ?? null;
            $id      = $product->id;
          @endphp

          <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-charcoal/10 bg-white p-3 shadow-soft sm:flex-nowrap sm:gap-4 sm:p-4">

            {{-- Image --}}
            <a href="{{ route('menu.show', $product) }}" class="flex h-16 w-16 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-cream text-3xl sm:h-20 sm:w-20 sm:text-4xl">
              @if ($image)
                <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover">
              @else
                🍽️
              @endif
            </a>

            {{-- Info --}}
            <div class="min-w-0 flex-1 order-1 sm:order-none basis-[calc(100%-5rem)] sm:basis-auto">
              <a href="{{ route('menu.show', $product) }}" class="font-display font-bold truncate hover:text-primary text-sm sm:text-base block">{{ $name }}</a>
              <p class="text-xs text-charcoal/55 mt-0.5 sm:text-sm">৳{{ number_format($price) }} / পিস</p>
              <div class="font-bold text-primary mt-1 sm:hidden">৳{{ number_format($price * $qty) }}</div>
            </div>

            {{-- Qty Controls --}}
            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
              @csrf @method('PATCH')
              <button type="submit" name="action" value="dec"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-charcoal/20 bg-cream font-bold text-charcoal hover:border-primary hover:bg-primary hover:text-white">−</button>
              <span class="w-7 text-center font-bold text-sm">{{ $qty }}</span>
              <button type="submit" name="action" value="inc"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-charcoal/20 bg-cream font-bold text-charcoal hover:border-primary hover:bg-primary hover:text-white">+</button>
            </form>

            {{-- Total (sm+) --}}
            <div class="hidden sm:block text-right min-w-[72px]">
              <div class="font-bold text-primary">৳{{ number_format($price * $qty) }}</div>
            </div>

            {{-- Remove --}}
            <form action="{{ route('cart.remove', $id) }}" method="POST">
              @csrf @method('DELETE')
              <button type="submit"
                class="flex h-8 w-8 items-center justify-center rounded-full text-charcoal/30 hover:bg-red-50 hover:text-red-500">✕</button>
            </form>
          </div>
        @endforeach

        {{-- Clear Cart --}}
        <div class="flex justify-end pt-2">
          <form action="{{ route('cart.clear') }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs font-bold text-charcoal/40 hover:text-red-500">
              🗑️ কার্ট খালি করুন
            </button>
          </form>
        </div>
      </div>

      {{-- Order Summary --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft h-fit sm:p-6 lg:sticky lg:top-24">
        <h2 class="font-display text-lg font-bold mb-5">অর্ডার সারাংশ</h2>

        @php
          $couponDiscount = (int) session('coupon.discount', 0);
          $couponCode = session('coupon.code');
          $finalTotal = max(0, $subtotal + $delivery_fee - $couponDiscount);
        @endphp

        <dl class="space-y-3 text-sm">
          <div class="flex justify-between">
            <dt class="text-charcoal/60">সাব-টোটাল</dt>
            <dd class="font-bold">৳{{ number_format($subtotal) }}</dd>
          </div>

          <div class="flex justify-between {{ $delivery_fee == 0 ? 'text-green-600' : '' }}">
            <dt>ডেলিভারি চার্জ</dt>
            <dd class="font-bold">{{ $delivery_fee == 0 ? 'ফ্রি' : '৳' . number_format($delivery_fee) }}</dd>
          </div>

          @if ($couponDiscount > 0)
            <div class="flex justify-between text-green-600">
              <dt>কুপন ({{ $couponCode }})</dt>
              <dd class="font-bold">−৳{{ number_format($couponDiscount) }}</dd>
            </div>
          @endif

          <div class="flex justify-between border-t border-charcoal/10 pt-3 text-base">
            <dt class="font-display font-bold text-charcoal">মোট</dt>
            <dd class="font-bold text-lg text-primary">৳{{ number_format($finalTotal) }}</dd>
          </div>
        </dl>

        {{-- Coupon Form --}}
        <div class="mt-5 border-t border-charcoal/10 pt-5">
          @if ($couponCode)
            <div class="flex items-center justify-between rounded-xl bg-green-50 px-3 py-2 text-xs">
              <span class="font-bold text-green-700">✅ {{ $couponCode }} প্রয়োগ হয়েছে</span>
              <form action="{{ route('cart.coupon.remove') }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="font-bold text-red-500 hover:underline">বাদ দিন</button>
              </form>
            </div>
          @else
            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
              @csrf
              <input type="text" name="code" placeholder="কুপন কোড" required
                class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm uppercase placeholder:normal-case focus:border-primary focus:outline-none">
              <button type="submit" class="rounded-xl bg-charcoal px-4 py-2 text-xs font-bold text-white hover:bg-primary">প্রয়োগ</button>
            </form>
            @if (session('coupon_error'))
              <p class="mt-2 text-xs font-bold text-red-500">{{ session('coupon_error') }}</p>
            @endif
          @endif
        </div>

        @if ($delivery_fee == 0 && $subtotal > 0)
          <div class="mt-4 rounded-xl bg-green-50 px-4 py-2.5 text-xs font-semibold text-green-700">
            ✅ আপনি ফ্রি ডেলিভারি পাচ্ছেন!
          </div>
        @elseif ($delivery_fee > 0)
          <div class="mt-4 rounded-xl bg-cream px-4 py-2.5 text-xs text-charcoal/55">
            ৳{{ number_format(max(0, ($freeMin ?? 500) - $subtotal)) }} বেশি অর্ডার করলে ফ্রি ডেলিভারি পাবেন।
          </div>
        @endif

        <a href="{{ route('checkout.index') }}"
          class="mt-6 block w-full rounded-full bg-gradient-warm py-3.5 text-center text-sm font-bold text-white shadow-warm hover:scale-[1.02]">
          ✅ চেকআউট করুন
        </a>

        <a href="{{ route('menu.index') }}"
          class="mt-3 block text-center text-xs font-bold text-charcoal/50 hover:text-primary">
          ← মেনুতে ফিরে যান
        </a>
      </div>
    </div>
  @endif
</div>
@endsection
