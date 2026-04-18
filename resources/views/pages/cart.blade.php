@extends('layouts.app')
@section('title', 'কার্ট — চিল ঘর')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">

  <h1 class="font-display text-3xl font-bold mb-8 sm:text-4xl">🛒 আপনার কার্ট</h1>

  @if (empty($items))
    <div class="flex flex-col items-center justify-center rounded-3xl border border-charcoal/10 bg-white py-24 text-center shadow-soft">
      <div class="text-7xl mb-5">🛒</div>
      <p class="font-display text-xl font-bold mb-2">কার্ট খালি আছে</p>
      <p class="text-sm text-charcoal/55 mb-8">আপনার পছন্দের খাবার বেছে নিন।</p>
      <a href="{{ route('menu.index') }}"
         class="rounded-full bg-gradient-warm px-8 py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-105">
        মেনু দেখুন
      </a>
    </div>
  @else
    <div class="grid gap-8 lg:grid-cols-[1fr,360px]">

      {{-- Cart Items --}}
      <div class="space-y-3">
        @foreach ($items as $id => $item)

          @php
            $price = $item['price'] ?? 0;
            $qty   = $item['qty'] ?? 1;
            $name  = $item['name'] ?? 'Unknown';
            $image = $item['image'] ?? null;
          @endphp

          <div class="flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">

            {{-- Image --}}
            <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-cream text-4xl">
              @if ($image)
                <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover">
              @else
                🍽️
              @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
              <h3 class="font-display font-bold truncate">{{ $name }}</h3>
              <p class="text-sm text-charcoal/55 mt-0.5">
                ৳{{ number_format($price) }} / পিস
              </p>
            </div>

            {{-- Qty Controls --}}
            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
              @csrf @method('PATCH')

              <button type="submit" name="action" value="dec"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-charcoal/20 bg-cream font-bold text-charcoal hover:border-primary hover:bg-primary hover:text-white">
                −
              </button>

              <span class="w-8 text-center font-bold text-sm">
                {{ $qty }}
              </span>

              <button type="submit" name="action" value="inc"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-charcoal/20 bg-cream font-bold text-charcoal hover:border-primary hover:bg-primary hover:text-white">
                +
              </button>
            </form>

            {{-- Total --}}
            <div class="text-right min-w-[72px]">
              <div class="font-bold text-primary">
                ৳{{ number_format($price * $qty) }}
              </div>
            </div>

            {{-- Remove --}}
            <form action="{{ route('cart.remove', $id) }}" method="POST">
              @csrf @method('DELETE')
              <button type="submit"
                class="flex h-8 w-8 items-center justify-center rounded-full text-charcoal/30 hover:bg-red-50 hover:text-red-500">
                ✕
              </button>
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
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft h-fit">

        <h2 class="font-display text-lg font-bold mb-5">অর্ডার সারাংশ</h2>

        @php
          $subtotal = $subtotal ?? 0;
          $delivery_fee = $delivery_fee ?? 0;
        @endphp

        <dl class="space-y-3 text-sm">
          <div class="flex justify-between">
            <dt class="text-charcoal/60">সাব-টোটাল</dt>
            <dd class="font-bold">৳{{ number_format($subtotal) }}</dd>
          </div>

          <div class="flex justify-between text-green-600">
            <dt>ডেলিভারি চার্জ</dt>
            <dd class="font-bold">
              {{ $delivery_fee == 0 ? 'ফ্রি' : '৳' . number_format($delivery_fee) }}
            </dd>
          </div>

          <div class="flex justify-between border-t border-charcoal/10 pt-3 text-base">
            <dt class="font-display font-bold text-charcoal">মোট</dt>
            <dd class="font-bold text-lg text-primary">
              ৳{{ number_format($subtotal + $delivery_fee) }}
            </dd>
          </div>
        </dl>

        {{-- Delivery Notice --}}
        @if ($delivery_fee == 0)
          <div class="mt-4 rounded-xl bg-green-50 px-4 py-2.5 text-xs font-semibold text-green-700">
            ✅ আপনি ফ্রি ডেলিভারি পাচ্ছেন!
          </div>
        @else
          <div class="mt-4 rounded-xl bg-cream px-4 py-2.5 text-xs text-charcoal/55">
            ৳{{ number_format(max(0, 500 - $subtotal)) }} বেশি অর্ডার করলে ফ্রি ডেলিভারি পাবেন।
          </div>
        @endif

        {{-- Actions --}}
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