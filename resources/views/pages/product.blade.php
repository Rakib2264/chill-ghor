@extends('layouts.app')
@section('title', $product->name . ' — চিল ঘর')

@php use App\Support\Wishlist; $inWish = Wishlist::has($product->id); @endphp

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  <nav class="mb-6 text-xs text-charcoal/60">
    <a href="{{ route('home') }}" class="hover:text-primary">হোম</a> /
    <a href="{{ route('menu.index') }}" class="hover:text-primary">মেনু</a> /
    <span class="font-bold text-charcoal">{{ $product->name }}</span>
  </nav>

  <div class="grid gap-8 lg:grid-cols-2">
    <div class="relative">
      <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
           class="aspect-square w-full rounded-3xl object-cover shadow-warm">
      @if ($product->popular)
        <span class="absolute left-4 top-4 rounded-full bg-spice px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-charcoal">🏆 জনপ্রিয়</span>
      @endif
    </div>

    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $product->category->name }}</p>
      <h1 class="mt-2 font-display text-3xl font-bold sm:text-4xl">{{ $product->name }}</h1>
      <p class="mt-3 text-charcoal/70 leading-relaxed">{{ $product->long_description ?? $product->description }}</p>

      <div class="mt-6 flex items-end gap-3">
        <div class="text-4xl font-bold gradient-text">৳{{ number_format($product->price) }}</div>
        @if ($product->old_price)
          <div class="pb-1.5 text-base text-charcoal/40 line-through">৳{{ number_format($product->old_price) }}</div>
        @endif
        @if ($product->spicy)
          <span class="ml-auto inline-flex items-center gap-1 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">🌶️ ঝাল</span>
        @endif
      </div>

      <form action="{{ route('cart.add', $product) }}" method="POST" x-data="{ qty: 1 }" class="mt-8">
        @csrf
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2 rounded-full border border-charcoal/15 bg-white p-1">
            <button type="button" @click="qty = Math.max(1, qty - 1)" class="flex h-9 w-9 items-center justify-center rounded-full hover:bg-charcoal/5">−</button>
            <input type="number" name="qty" x-model="qty" min="1" class="w-12 border-0 bg-transparent text-center font-bold focus:outline-none">
            <button type="button" @click="qty++" class="flex h-9 w-9 items-center justify-center rounded-full hover:bg-charcoal/5">+</button>
          </div>
          <button type="submit" class="flex-1 rounded-full bg-gradient-warm px-7 py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">
            🛒 কার্টে যোগ করুন
          </button>
        </div>
      </form>

      <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full border border-charcoal/15 bg-white py-3 text-sm font-bold transition hover:border-primary hover:text-primary">
          {{ $inWish ? '❤️ উইশলিস্ট থেকে বাদ' : '🤍 উইশলিস্টে যোগ করুন' }}
        </button>
      </form>

      <div class="mt-6 grid grid-cols-3 gap-3 text-center text-xs">
        <div class="rounded-xl bg-cream p-3"><div class="text-xl">🚚</div><div class="mt-1 font-bold">ফ্রি ডেলিভারি</div></div>
        <div class="rounded-xl bg-cream p-3"><div class="text-xl">⏱️</div><div class="mt-1 font-bold">২০-৩০ মিনিট</div></div>
        <div class="rounded-xl bg-cream p-3"><div class="text-xl">💰</div><div class="mt-1 font-bold">COD</div></div>
      </div>
    </div>
  </div>

  @if ($related->isNotEmpty())
    <section class="mt-16">
      <h2 class="font-display text-2xl font-bold">আরও দেখুন</h2>
      <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($related as $product)
          @include('partials.product-card', ['product' => $product])
        @endforeach
      </div>
    </section>
  @endif

</div>
@endsection
