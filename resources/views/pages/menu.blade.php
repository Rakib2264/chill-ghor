@extends('layouts.app')
@section('title', 'মেনু — চিল ঘর')

@section('content')
    @include('partials.advertisement', ['ads' => $ads])

    {{-- Hero strip --}}
    <div class="relative overflow-hidden grain"
         style="background: linear-gradient(160deg,#1c0f09,#2a1812 60%,#3d2010);">
        <div class="pointer-events-none absolute -top-10 right-0 h-56 w-56 rounded-full opacity-25 blur-3xl"
             style="background: radial-gradient(circle,#e8671a,transparent 60%);"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-spice/90">
                <span class="inline-block h-px w-6 align-middle bg-spice/60 mr-1.5"></span> আমাদের মেনু
            </p>
            <h1 class="mt-2 font-display text-3xl font-black text-white sm:text-4xl lg:text-5xl">
                সব খাবার এক জায়গায়
            </h1>
            <p class="mt-2 max-w-lg text-sm" style="color:rgba(255,255,255,.62)">
                বিরিয়ানি, ফাস্ট ফুড, ফুচকা, চা–কফি — পছন্দ করুন, অর্ডার করুন, উপভোগ করুন।
            </p>
        </div>
    </div>

    {{-- Sticky filter bar --}}
    <div class="sticky top-[56px] md:top-[64px] z-30 -mt-6 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="rounded-2xl border border-charcoal/8 bg-white shadow-soft p-3 sm:p-4">
                <form method="GET" action="{{ route('menu.index') }}" class="flex flex-wrap gap-2 sm:gap-3">
                    <div class="relative flex-1 min-w-[200px]">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/40">
                            <i class="fa-solid fa-magnifying-glass text-[13px]"></i>
                        </span>
                        <input type="text" name="q" value="{{ $search }}" placeholder="খাবার খুঁজুন..."
                            class="w-full rounded-full border border-charcoal/12 bg-cream/40 py-3 pl-11 pr-4 text-sm placeholder:text-charcoal/40 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 transition">
                    </div>
                    @if ($search)
                        <a href="{{ route('menu.index', ['category' => $activeCat]) }}"
                            class="inline-flex items-center gap-1.5 rounded-full border border-charcoal/15 bg-white px-4 text-sm font-bold text-charcoal/70 hover:border-primary hover:text-primary transition">
                            <i class="fa-solid fa-xmark text-[11px]"></i> সাফ
                        </a>
                    @endif
                    <button class="btn-primary inline-flex items-center gap-2 rounded-full px-6 text-sm font-black">
                        খুঁজুন <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </button>
                </form>

                {{-- Category chips --}}
                <div class="mt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-none -mx-1 px-1">
                    <a href="{{ route('menu.index', ['q' => $search]) }}"
                       class="chip flex-shrink-0 {{ $activeCat === 'all' ? 'chip-active' : '' }}">
                        🍽️ সব
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('menu.index', ['category' => $cat->slug, 'q' => $search]) }}"
                           class="chip flex-shrink-0 {{ $activeCat === $cat->slug ? 'chip-active' : '' }}">
                            <span>{{ $cat->emoji }}</span> {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pt-6 pb-12 sm:px-6 lg:px-8">

        {{-- Result count --}}
        @if(!$products->isEmpty())
            <div class="mb-5 flex items-center justify-between">
                <p class="text-xs font-bold text-charcoal/55">
                    <span class="font-black text-charcoal">{{ $products->count() }}</span> টি আইটেম পাওয়া গেছে
                    @if($search) "<span class="text-primary font-black">{{ $search }}</span>" এর জন্য @endif
                </p>
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="rounded-3xl border border-dashed border-charcoal/20 bg-white p-12 sm:p-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-cream text-4xl">🍽️</div>
                <h3 class="mt-4 font-display text-xl font-black sm:text-2xl">কোনো পণ্য পাওয়া যায়নি</h3>
                <p class="mt-2 text-sm text-charcoal/60">অন্য ক্যাটাগরি বা সার্চ চেষ্টা করুন</p>
                <a href="{{ route('menu.index') }}" class="btn-primary mt-6 inline-flex rounded-full px-6 py-2.5 text-sm font-black">সব মেনু দেখুন</a>
            </div>
        @else
            <div class="grid gap-3 grid-cols-2 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $i => $product)
                    <div class="reveal" style="transition-delay: {{ ($i % 8) * 50 }}ms;">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection