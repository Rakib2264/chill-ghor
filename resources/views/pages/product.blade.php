@extends('layouts.app')
@section('title', $product->name . ' — চিল ঘর')

@php use App\Support\Wishlist; $inWish = Wishlist::has($product->id); @endphp

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:py-10 lg:px-8">

  <nav class="mb-5 text-xs text-charcoal/60">
    <a href="{{ route('home') }}" class="hover:text-primary">হোম</a> /
    <a href="{{ route('menu.index') }}" class="hover:text-primary">মেনু</a> /
    <span class="font-bold text-charcoal">{{ $product->name }}</span>
  </nav>

  <div class="grid gap-6 lg:grid-cols-2 lg:gap-10">
    <div class="relative">
      <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
           class="aspect-square w-full rounded-3xl object-cover shadow-warm">
      @if ($product->popular)
        <span class="absolute left-4 top-4 rounded-full bg-spice px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-charcoal">🏆 জনপ্রিয়</span>
      @endif
    </div>

    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $product->category->name }}</p>
      <h1 class="mt-2 font-display text-2xl font-bold sm:text-3xl lg:text-4xl">{{ $product->name }}</h1>

      {{-- Rating Summary --}}
      @if ($product->reviews_count > 0)
        @php $avg = $product->average_rating; @endphp
        <div class="mt-2 flex items-center gap-2 text-sm">
          <div class="flex text-spice">
            @for ($i = 1; $i <= 5; $i++)
              <span>{!! $i <= round($avg) ? '★' : '☆' !!}</span>
            @endfor
          </div>
          <span class="font-bold">{{ $avg }}</span>
          <span class="text-charcoal/55">({{ $product->reviews_count }} রিভিউ)</span>
        </div>
      @endif

      <p class="mt-3 text-sm text-charcoal/70 leading-relaxed sm:text-base">{{ $product->long_description ?? $product->description }}</p>

      <div class="mt-5 flex items-end gap-3">
        <div class="text-3xl font-bold gradient-text sm:text-4xl">৳{{ number_format($product->price) }}</div>
        @if ($product->old_price)
          <div class="pb-1.5 text-base text-charcoal/40 line-through">৳{{ number_format($product->old_price) }}</div>
        @endif
        @if ($product->spicy)
          <span class="ml-auto inline-flex items-center gap-1 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">🌶️ ঝাল</span>
        @endif
      </div>

      <form action="{{ route('cart.add', $product) }}" method="POST" x-data="{ qty: 1 }" class="mt-6 sm:mt-8">
        @csrf
        <div class="flex flex-wrap items-center gap-3 sm:flex-nowrap sm:gap-4">
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

  {{-- ===== REVIEWS ===== --}}
  <section class="mt-12 sm:mt-16">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-2">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">⭐ রিভিউ</p>
        <h2 class="font-display text-xl font-bold sm:text-2xl">গ্রাহকদের মতামত ({{ $product->reviews_count }})</h2>
      </div>
    </div>

    {{-- Submit form --}}
    @auth
      <form action="{{ route('reviews.store', $product) }}" method="POST"
            x-data="{ rating: {{ $userReview->rating ?? 5 }}, hover: 0 }"
            class="rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft mb-6">
        @csrf
        <p class="text-sm font-bold mb-3">{{ $userReview ? 'আপনার রিভিউ আপডেট করুন' : 'আপনার রিভিউ লিখুন' }}</p>
        <div class="flex items-center gap-1 mb-3">
          @for ($i = 1; $i <= 5; $i++)
            <button type="button" @mouseenter="hover={{ $i }}" @mouseleave="hover=0" @click="rating={{ $i }}"
              class="text-3xl transition" :class="(hover || rating) >= {{ $i }} ? 'text-spice' : 'text-charcoal/20'">★</button>
          @endfor
          <input type="hidden" name="rating" :value="rating">
          <span class="ml-2 text-sm font-bold" x-text="rating + '/5'"></span>
        </div>
        <textarea name="comment" rows="3" maxlength="1000" placeholder="আপনার অভিজ্ঞতা শেয়ার করুন..."
          class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">{{ $userReview->comment ?? '' }}</textarea>
        <div class="mt-3 flex justify-end">
          <button type="submit" class="rounded-full bg-gradient-warm px-6 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105">
            {{ $userReview ? 'আপডেট করুন' : 'সাবমিট করুন' }}
          </button>
        </div>
      </form>
    @else
      <div class="rounded-2xl border border-dashed border-charcoal/20 bg-cream/50 p-5 text-center mb-6">
        <p class="text-sm text-charcoal/65">রিভিউ দিতে চাইলে <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">লগইন করুন</a></p>
      </div>
    @endauth

    {{-- List --}}
    <div class="space-y-3">
      @forelse ($product->reviews as $rev)
        <div class="rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft sm:p-5">
          <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-warm text-sm font-bold text-white">
                {{ strtoupper(substr($rev->user->name ?? 'U', 0, 1)) }}
              </div>
              <div>
                <div class="text-sm font-bold">{{ $rev->user->name ?? 'Guest' }}</div>
                <div class="flex items-center gap-1 text-xs">
                  <span class="text-spice">{{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}</span>
                  <span class="text-charcoal/50">· {{ $rev->created_at->diffForHumans() }}</span>
                </div>
              </div>
            </div>
            @auth
              @if ($rev->user_id === auth()->id() || (auth()->user()->is_admin ?? false))
                <form action="{{ route('reviews.destroy', $rev) }}" method="POST" onsubmit="return confirm('মুছে ফেলবেন?')">
                  @csrf @method('DELETE')
                  <button class="text-charcoal/30 hover:text-red-500 text-sm">✕</button>
                </form>
              @endif
            @endauth
          </div>
          @if ($rev->comment)
            <p class="mt-3 text-sm text-charcoal/75 leading-relaxed">{{ $rev->comment }}</p>
          @endif
        </div>
      @empty
        <div class="rounded-2xl border border-dashed border-charcoal/15 bg-white/50 p-8 text-center text-charcoal/55">
          এখনো কোনো রিভিউ নেই — প্রথম রিভিউ দেওয়ার সুযোগ আপনার!
        </div>
      @endforelse
    </div>
  </section>

  @if ($related->isNotEmpty())
    <section class="mt-12 sm:mt-16">
      <h2 class="font-display text-xl font-bold sm:text-2xl">আরও দেখুন</h2>
      <div class="mt-5 grid gap-4 grid-cols-2 sm:gap-5 lg:grid-cols-4">
        @foreach ($related as $product)
          @include('partials.product-card', ['product' => $product])
        @endforeach
      </div>
    </section>
  @endif

</div>
@endsection
