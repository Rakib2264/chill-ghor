@extends('layouts.app')
@section('title', 'মেনু — চিল ঘর')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

  <div class="mb-8">
    <p class="text-xs font-bold uppercase tracking-widest text-primary">আমাদের মেনু</p>
    <h1 class="mt-1 font-display text-3xl font-bold sm:text-4xl">সব খাবার এক জায়গায়</h1>
  </div>

  <form method="GET" action="{{ route('menu.index') }}" class="mb-6 flex flex-wrap gap-3">
    <div class="relative flex-1 min-w-[240px]">
      <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/40">🔍</span>
      <input type="text" name="q" value="{{ $search }}" placeholder="খাবার খুঁজুন..."
             class="w-full rounded-full border border-charcoal/15 bg-white py-3 pl-11 pr-4 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    </div>
    @if ($search)
      <a href="{{ route('menu.index', ['category' => $activeCat]) }}" class="inline-flex items-center rounded-full border border-charcoal/15 bg-white px-5 text-sm">✕ সাফ</a>
    @endif
    <button class="rounded-full bg-gradient-warm px-6 text-sm font-bold text-white shadow-warm">খুঁজুন</button>
  </form>

  <div class="mb-8 flex flex-wrap gap-2">
    <a href="{{ route('menu.index', ['q' => $search]) }}"
       class="rounded-full border px-4 py-2 text-xs font-bold transition
              {{ $activeCat === 'all' ? 'border-primary bg-primary text-white shadow-warm' : 'border-charcoal/15 bg-white hover:border-primary' }}">
      🍽️ সব
    </a>
    @foreach ($categories as $cat)
      <a href="{{ route('menu.index', ['category' => $cat->slug, 'q' => $search]) }}"
         class="rounded-full border px-4 py-2 text-xs font-bold transition
                {{ $activeCat === $cat->slug ? 'border-primary bg-primary text-white shadow-warm' : 'border-charcoal/15 bg-white hover:border-primary' }}">
        {{ $cat->emoji }} {{ $cat->name }}
      </a>
    @endforeach
  </div>

  @if ($products->isEmpty())
    <div class="rounded-2xl border border-dashed border-charcoal/20 bg-white p-16 text-center">
      <div class="text-5xl">🍽️</div>
      <h3 class="mt-3 font-display text-xl font-bold">কোনো পণ্য পাওয়া যায়নি</h3>
      <p class="mt-1 text-sm text-charcoal/60">অন্য ক্যাটাগরি বা সার্চ চেষ্টা করুন</p>
    </div>
  @else
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach ($products as $product)
        @include('partials.product-card', ['product' => $product])
      @endforeach
    </div>
  @endif

</div>
@endsection
