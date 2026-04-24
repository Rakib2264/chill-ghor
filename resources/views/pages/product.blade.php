@extends('layouts.app')
@section('title', $product->name . ' — চিল ঘর')

@php
    use App\Support\Wishlist;
    $inWish = Wishlist::has($product->id);
@endphp

@section('content')
    <div class="min-h-screen" style="background:#f8f4f0;">

        {{-- ── BREADCRUMB ── --}}
        <div class="mx-auto max-w-6xl px-4 pt-5 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1.5 text-xs text-charcoal/40 font-medium">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">হোম</a>
                <svg class="h-3 w-3 text-charcoal/25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('menu.index') }}" class="hover:text-primary transition-colors">মেনু</a>
                <svg class="h-3 w-3 text-charcoal/25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-charcoal/65 font-semibold truncate max-w-[180px]">{{ $product->name }}</span>
            </nav>
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 lg:py-8 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[55%_1fr] lg:gap-10 lg:items-start">

                {{-- ════════════════════════════
           LEFT: IMAGE BLOCK
           ════════════════════════════ --}}
                <div class="relative">

                    {{-- Image card --}}
                    <div class="relative overflow-hidden rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.13)]"
                        style="aspect-ratio:1/1;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover transition-transform duration-700 hover:scale-[1.04]"
                            loading="eager">

                        {{-- Bottom gradient --}}
                        <div class="absolute inset-x-0 bottom-0 h-2/5 pointer-events-none"
                            style="background:linear-gradient(to top, rgba(28,15,9,0.55) 0%, transparent 100%);"></div>

                        {{-- TOP row: popular badge + wishlist --}}
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">

                            @if ($product->popular)
                                <div class="flex items-center gap-1.5 rounded-full px-3.5 py-1.5 shadow-lg backdrop-blur-sm"
                                    style="background:rgba(245,166,35,0.92);">
                                    <span class="text-xs">🏆</span>
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-[#1c0f09]">জনপ্রিয়</span>
                                </div>
                            @else
                                <div></div>
                            @endif

                            {{-- ★ WISHLIST BUTTON ★ --}}
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="group flex items-center gap-2 rounded-full px-4 py-2.5
           shadow-md border transition-all duration-200
           hover:scale-105 active:scale-95 font-semibold text-sm
           {{ $inWish
               ? 'bg-red-600 text-white border-red-600'
               : 'bg-white text-gray-800 border-gray-300 hover:bg-red-500 hover:text-white hover:border-red-500' }}">

                                    <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                        fill="{{ $inWish ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682
                   a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318
                   a4.5 4.5 0 00-6.364 0z" />
                                    </svg>

                                    {{ $inWish ? 'সেভড ✓' : 'সেভ করুন' }}
                                </button>
                            </form>
                        </div>

                        {{-- BOTTOM row: price + spicy --}}
                        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
                            <div class="rounded-2xl px-4 py-2.5 backdrop-blur-md shadow-lg"
                                style="background:rgba(255,255,255,0.93);">
                                <div class="flex items-baseline gap-2">
                                    <span
                                        class="font-display text-2xl font-black text-primary">৳{{ number_format($product->price) }}</span>
                                    @if ($product->old_price)
                                        <span
                                            class="text-sm text-charcoal/35 line-through">৳{{ number_format($product->old_price) }}</span>
                                    @endif
                                </div>
                                @if ($product->old_price)
                                    <div class="text-[10px] font-bold text-green-600 mt-0.5">
                                        {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                        ছাড় পাচ্ছেন!
                                    </div>
                                @endif
                            </div>

                            @if ($product->spicy)
                                <div class="flex items-center gap-1 rounded-full px-3 py-1.5 backdrop-blur-sm shadow"
                                    style="background:rgba(192,57,43,0.90);">
                                    <span class="text-sm">🌶️</span>
                                    <span class="text-[11px] font-black text-white">ঝাল</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Rating bar below image --}}
                    @if ($product->reviews_count > 0)
                        @php $avg = $product->average_rating; @endphp
                        <div
                            class="mt-3 flex items-center justify-between rounded-2xl bg-white px-5 py-3 shadow-sm border border-charcoal/6">
                            <div class="flex items-center gap-3">
                                <span class="font-display text-2xl font-black text-[#1c0f09]">{{ $avg }}</span>
                                <div>
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-3.5 w-3.5 {{ $i <= round($avg) ? 'text-[#f5a623]' : 'text-charcoal/15' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-[10px] text-charcoal/40 mt-0.5">{{ $product->reviews_count }} গ্রাহক
                                        রিভিউ</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-green-600">✓ যাচাইকৃত পণ্য</p>
                                <p class="text-[10px] text-charcoal/40 mt-0.5">চিল ঘর কর্তৃক পরিবেশিত</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ════════════════════════════
           RIGHT: DETAIL CARD
           ════════════════════════════ --}}
                <div class="flex flex-col gap-4">

                    {{-- Main info card --}}
                    <div class="rounded-[28px] bg-white border border-charcoal/6 shadow-sm p-6">

                        <span
                            class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1
                       text-[11px] font-black uppercase tracking-widest text-primary">
                            {{ $product->category->name }}
                        </span>

                        <h1 class="mt-3 font-display text-[26px] font-black leading-tight text-[#1c0f09] sm:text-3xl">
                            {{ $product->name }}
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-charcoal/60 sm:text-[15px]">
                            {{ $product->long_description ?? $product->description }}
                        </p>

                        <div class="my-5 border-t border-dashed border-charcoal/10"></div>

                        {{-- Price desktop --}}
                        <div class="hidden lg:flex items-baseline gap-3">
                            <span
                                class="font-display text-4xl font-black text-primary">৳{{ number_format($product->price) }}</span>
                            @if ($product->old_price)
                                <span
                                    class="text-lg text-charcoal/30 line-through">৳{{ number_format($product->old_price) }}</span>
                                <span
                                    class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-black text-green-600 border border-green-100">
                                    {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                    ছাড়
                                </span>
                            @endif
                        </div>

                        {{-- Add to cart --}}
                        <form action="{{ route('cart.add', $product) }}" method="POST" x-data="{ qty: 1 }"
                            class="mt-5">
                            @csrf

                            <div class="flex items-center gap-3">
                                {{-- Stepper --}}
                                <div class="flex items-center rounded-2xl border border-charcoal/12 bg-[#f8f4f0]">
                                    <button type="button" @click="qty = Math.max(1, qty - 1)"
                                        class="flex h-12 w-11 items-center justify-center text-xl font-black
                               text-charcoal/40 hover:text-primary hover:bg-primary/5 transition rounded-l-2xl">−</button>
                                    <span class="w-9 text-center text-base font-black text-[#1c0f09]" x-text="qty"></span>
                                    <input type="hidden" name="qty" :value="qty">
                                    <button type="button" @click="qty++"
                                        class="flex h-12 w-11 items-center justify-center text-xl font-black
                               text-charcoal/40 hover:text-primary hover:bg-primary/5 transition rounded-r-2xl">+</button>
                                </div>

                                <button type="submit"
                                    class="flex flex-1 items-center justify-center gap-2.5 rounded-2xl
                             py-3.5 px-5 text-sm font-black text-white
                             transition-all hover:scale-[1.02] active:scale-[0.98]
                             shadow-[0_4px_20px_rgba(192,57,43,0.35)]"
                                    style="background:linear-gradient(135deg,#c0392b 0%,#e74c3c 100%);">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    কার্টে যোগ করুন
                                </button>
                            </div>

                            <div class="mt-3 flex items-center justify-between rounded-xl bg-[#f8f4f0] px-4 py-2.5">
                                <span class="text-xs text-charcoal/40 font-medium">মোট মূল্য</span>
                                <span class="text-sm font-black text-[#1c0f09]"
                                    x-text="'৳' + ({{ $product->price }} * qty).toLocaleString()"></span>
                            </div>
                        </form>
                    </div>

                    {{-- Feature chips --}}
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([['🚚', 'দ্রুত', 'ডেলিভারি', 'rgba(192,57,43,0.08)'], ['💵', 'ক্যাশ', 'অন ডেলিভারি', 'rgba(245,166,35,0.10)'], ['✅', 'তাজা', 'উপাদান', 'rgba(22,163,74,0.08)']] as [$icon, $title, $sub, $bg])
                            <div
                                class="flex flex-col items-center gap-2 rounded-2xl bg-white border border-charcoal/6 py-4 shadow-sm">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                    style="background:{{ $bg }}">
                                    <span class="text-xl">{{ $icon }}</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-[11px] font-black text-[#1c0f09]">{{ $title }}</p>
                                    <p class="text-[10px] text-charcoal/45 leading-tight">{{ $sub }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {{-- REVIEWS --}}
        <div class="mx-auto max-w-6xl px-4 pb-10 sm:px-6 lg:px-8">
            <div class="rounded-[32px] bg-white border border-charcoal/6 shadow-sm overflow-hidden">

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-charcoal/8 px-6 py-5">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-primary">⭐ রিভিউ</p>
                        <h2 class="font-display text-xl font-black text-[#1c0f09] sm:text-2xl">
                            গ্রাহকদের মতামত
                            <span
                                class="ml-1.5 rounded-full bg-primary/10 px-2.5 py-0.5 text-sm font-bold text-primary">{{ $product->reviews_count }}</span>
                        </h2>
                    </div>
                    @if ($product->reviews_count > 0)
                        @php $avg = $product->average_rating; @endphp
                        <div class="flex items-center gap-3 rounded-2xl px-4 py-2.5" style="background:#f8f4f0;">
                            <span class="font-display text-3xl font-black text-[#1c0f09]">{{ $avg }}</span>
                            <div>
                                <div class="flex gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= round($avg) ? 'text-[#f5a623]' : 'text-charcoal/15' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-[10px] text-charcoal/40 mt-0.5">{{ $product->reviews_count }} রিভিউ</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6 space-y-4">

                    @auth
                        <form action="{{ route('reviews.store', $product) }}" method="POST" x-data="{ rating: {{ $userReview->rating ?? 5 }}, hover: 0 }"
                            class="rounded-2xl border border-charcoal/8 p-5" style="background:#f8f4f0;">
                            @csrf
                            <p class="mb-3 text-sm font-black text-[#1c0f09]">
                                {{ $userReview ? 'আপনার রিভিউ আপডেট করুন' : 'আপনার রিভিউ লিখুন' }}
                            </p>
                            <div class="mb-4 flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" @mouseenter="hover={{ $i }}" @mouseleave="hover=0"
                                        @click="rating={{ $i }}"
                                        class="text-[28px] transition-transform hover:scale-110"
                                        :class="(hover || rating) >= {{ $i }} ? 'text-[#f5a623]' :
                                            'text-charcoal/15'">★</button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                                <span class="ml-2 rounded-full bg-white px-2.5 py-0.5 text-sm font-black shadow-sm"
                                    x-text="rating + '/5'"></span>
                            </div>
                            <textarea name="comment" rows="3" maxlength="1000" placeholder="আপনার অভিজ্ঞতা শেয়ার করুন..."
                                class="w-full rounded-xl border border-charcoal/10 bg-white px-4 py-3 text-sm placeholder:text-charcoal/30
                             focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 resize-none">{{ $userReview->comment ?? '' }}</textarea>
                            <div class="mt-3 flex justify-end">
                                <button type="submit"
                                    class="rounded-full px-7 py-2.5 text-sm font-black text-white transition hover:scale-105 active:scale-95 shadow-warm"
                                    style="background:linear-gradient(135deg,#c0392b,#e74c3c);">
                                    {{ $userReview ? 'আপডেট করুন' : 'সাবমিট করুন' }}
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="rounded-2xl border border-dashed border-charcoal/15 px-5 py-6 text-center"
                            style="background:#f8f4f0;">
                            <p class="text-sm text-charcoal/50">রিভিউ দিতে চাইলে <a href="{{ route('login') }}"
                                    class="font-bold text-primary hover:underline">লগইন করুন</a></p>
                        </div>
                    @endauth

                    <div class="space-y-3">
                        @forelse ($product->reviews as $rev)
                            <div
                                class="rounded-2xl border border-charcoal/7 bg-white p-4 sm:p-5 transition hover:shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full text-sm font-black text-white shadow-sm"
                                            style="background:linear-gradient(135deg,#c0392b,#f5a623);">
                                            {{ strtoupper(substr($rev->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-[#1c0f09]">
                                                {{ $rev->user->name ?? 'Guest' }}</div>
                                            <div class="mt-0.5 flex items-center gap-2">
                                                <div class="flex gap-0.5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="h-3 w-3 {{ $i <= $rev->rating ? 'text-[#f5a623]' : 'text-charcoal/15' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span
                                                    class="text-[11px] text-charcoal/35">{{ $rev->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @auth
                                        @if ($rev->user_id === auth()->id() || (auth()->user()->is_admin ?? false))
                                            <form action="{{ route('reviews.destroy', $rev) }}" method="POST"
                                                onsubmit="return confirm('মুছে ফেলবেন?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="flex h-7 w-7 items-center justify-center rounded-full text-charcoal/20 hover:bg-red-50 hover:text-red-500 transition text-sm">✕</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                @if ($rev->comment)
                                    <p class="mt-3 text-sm leading-relaxed text-charcoal/65">{{ $rev->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-charcoal/12 py-10 text-center text-sm text-charcoal/40"
                                style="background:#f8f4f0;">
                                এখনো কোনো রিভিউ নেই — প্রথম রিভিউ দেওয়ার সুযোগ আপনার! 🍽️
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>

        {{-- RELATED --}}
        @if ($related->isNotEmpty())
            <div class="mx-auto max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                <div class="mb-5 flex items-end justify-between">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-primary">আরও দেখুন</p>
                        <h2 class="font-display text-xl font-black text-[#1c0f09] sm:text-2xl">পছন্দ করতে পারেন</h2>
                    </div>
                    <a href="{{ route('menu.index') }}" class="text-sm font-bold text-primary hover:underline">সব →</a>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
                    @foreach ($related as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
