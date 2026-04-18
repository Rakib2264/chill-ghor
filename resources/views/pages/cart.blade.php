@extends('layouts.app')
@section('title', 'কার্ট — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  <p class="text-xs font-bold uppercase tracking-widest text-primary">আপনার অর্ডার</p>
  <h1 class="mt-1 font-display text-3xl font-bold sm:text-4xl">শপিং কার্ট</h1>

  @if ($items->isEmpty())
    <div class="mt-12 rounded-3xl border border-dashed border-charcoal/20 bg-white p-16 text-center">
      <div class="text-6xl">🛒</div>
      <h2 class="mt-4 font-display text-2xl font-bold">কার্ট খালি</h2>
      <p class="mt-2 text-charcoal/60">আপনি এখনো কোনো খাবার যোগ করেননি।</p>
      <a href="{{ route('menu.index') }}" class="mt-6 inline-flex rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm">মেনু দেখুন →</a>
    </div>
  @else
    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr,360px]">

      <div class="space-y-3">
        @foreach ($items as $item)
          @php $p = $item['product']; @endphp
          <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-3 shadow-soft">
            <a href="{{ route('menu.show', $p) }}">
              <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="h-20 w-20 rounded-xl object-cover">
            </a>
            <div class="min-w-0 flex-1">
              <a href="{{ route('menu.show', $p) }}"><h3 class="font-display font-bold hover:text-primary">{{ $p->name }}</h3></a>
              <p class="line-clamp-1 text-xs text-charcoal/60">{{ $p->description }}</p>
              <p class="mt-1 font-bold text-primary">৳{{ number_format($p->price) }}</p>
            </div>

            <form action="{{ route('cart.update', $p) }}" method="POST" class="flex items-center gap-1 rounded-full border border-charcoal/15 bg-cream p-1">
              @csrf @method('PATCH')
              <button name="qty" value="{{ $item['qty'] - 1 }}" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-charcoal/5">−</button>
              <span class="w-8 text-center font-bold">{{ $item['qty'] }}</span>
              <button name="qty" value="{{ $item['qty'] + 1 }}" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-charcoal/5">+</button>
            </form>

            <div class="w-20 text-right font-bold">৳{{ number_format($p->price * $item['qty']) }}</div>

            <form action="{{ route('cart.remove', $p) }}" method="POST">
              @csrf @method('DELETE')
              <button class="flex h-9 w-9 items-center justify-center rounded-full text-charcoal/50 hover:bg-primary/10 hover:text-primary" aria-label="remove">✕</button>
            </form>
          </div>
        @endforeach

        <form action="{{ route('cart.clear') }}" method="POST" class="text-right">
          @csrf @method('DELETE')
          <button class="text-xs font-bold text-charcoal/50 hover:text-primary">🗑️ কার্ট খালি করুন</button>
        </form>
      </div>

      <aside class="h-fit rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h2 class="font-display text-xl font-bold">অর্ডার সারাংশ</h2>
        <dl class="mt-5 space-y-3 text-sm">
          <div class="flex justify-between"><dt class="text-charcoal/60">সাব-টোটাল</dt><dd class="font-bold">৳{{ number_format($subtotal) }}</dd></div>
          <div class="flex justify-between">
            <dt class="text-charcoal/60">ডেলিভারি ফি</dt>
            <dd class="font-bold">{{ $deliveryFee === 0 ? 'ফ্রি 🎉' : '৳' . number_format($deliveryFee) }}</dd>
          </div>
          @if ($subtotal < 500)
            <div class="rounded-lg bg-spice/10 p-3 text-xs text-charcoal/70">
              আরও <span class="font-bold text-primary">৳{{ number_format(500 - $subtotal) }}</span> অর্ডারে ফ্রি ডেলিভারি!
            </div>
          @endif
          <div class="border-t border-charcoal/10 pt-3 flex justify-between text-lg">
            <dt class="font-display font-bold">মোট</dt>
            <dd class="font-bold gradient-text">৳{{ number_format($total) }}</dd>
          </div>
        </dl>
        <a href="{{ route('checkout.index') }}" class="mt-6 flex items-center justify-center gap-2 rounded-full bg-gradient-warm px-6 py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">
          চেকআউটে যান →
        </a>
        <a href="{{ route('menu.index') }}" class="mt-3 block text-center text-xs font-bold text-charcoal/60 hover:text-primary">← আরও কেনাকাটা</a>
      </aside>

    </div>
  @endif

</div>
@endsection
