@extends('layouts.app')
@php
    use App\Models\Setting;
    function getSetting($key, $default = null)
    {
        $value = Setting::get($key, $default);
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            $t = trim($value);
            if (str_starts_with($t, '[') || str_starts_with($t, '{')) {
                $d = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $d;
                }
            }
        }
        return $value;
    }
    $siteTitle = getSetting('site_title', 'চিল ঘর — চা–কফির আড্ডা, ফাস্ট ফুডের আসল স্বাদ');
    $siteDescription = getSetting('site_description', 'বনগ্রাম স্কুল ও কলেজের সামনে');
@endphp
@section('title', is_string($siteTitle) ? $siteTitle : 'চিল ঘর')
@section('description', is_string($siteDescription) ? $siteDescription : 'বনগ্রাম স্কুল ও কলেজের সামনে')

@section('content')

    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden grain"
        style="background:linear-gradient(160deg, #1c0f09 0%, #2a1812 55%, #3d2010 100%);">

        {{-- decorative aurora orbs --}}
        <div class="pointer-events-none absolute -top-20 -right-20 h-[28rem] w-[28rem] rounded-full opacity-25 blur-3xl"
            style="background: radial-gradient(circle, #e8671a 0%, transparent 60%);"></div>
        <div class="pointer-events-none absolute top-1/3 -left-20 h-80 w-80 rounded-full opacity-20 blur-3xl"
            style="background: radial-gradient(circle, #f5a623 0%, transparent 60%);"></div>
        <div
            class="pointer-events-none absolute -top-10 right-10 h-40 w-40 rounded-full border-[24px] border-primary/20 opacity-50">
        </div>

        <div
            class="relative mx-auto max-w-7xl px-4 pb-12 pt-8 sm:px-6 lg:grid lg:grid-cols-12 lg:items-center lg:gap-10 lg:px-8 lg:py-24">

            {{-- Left content --}}
            <div class="text-white lg:col-span-7">
                {{-- badge --}}
                <span
                    class="reveal mb-5 inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-[11px] font-black uppercase tracking-[0.16em]"
                    style="background:rgba(245,166,35,.12);border-color:rgba(245,166,35,.3);color:#f5a623;">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-spice opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-spice"></span>
                    </span>
                    {{ getSetting('hero_tagline', 'বনগ্রামের প্রিয় আড্ডাখানা') }}
                </span>

                {{-- h1 --}}
                <h1
                    class="reveal font-display font-black leading-[1.05] text-white text-[2.25rem] sm:text-5xl lg:text-[4.25rem]">
                    {!! getSetting('hero_title', 'ঘরের স্বাদ,<br><span style="color:#f5a623">রেস্টুরেন্টে</span> 🍛') !!}
                </h1>

                <p class="reveal mt-5 max-w-lg text-[15px] leading-relaxed sm:text-base"
                    style="color:rgba(255,255,255,.62)">
                    {{ getSetting('hero_description', 'কাচ্চি বিরিয়ানি, ইলিশ সরিষা, ফুচকা থেকে চিকেন বার্গার — বনগ্রাম স্কুল ও কলেজের সামনে।') }}
                </p>

                {{-- Hero image (mobile only - shows under text) --}}
                <div class="reveal mt-7 lg:hidden">
                    <div class="relative">
                        <img src="{{ asset(getSetting('hero_image', 'images/food/hero.jpeg')) }}" alt="চিল ঘরের বিশেষ খাবার"
                            loading="eager"
                            class="aspect-[4/3] w-full rounded-3xl object-cover ring-2 ring-white/10 shadow-glow"
                            onerror="this.src='https://placehold.co/600x450/3d2010/f5a623?text=চিল+ঘর'">
                        <div class="absolute -bottom-3 left-3 rounded-2xl bg-white px-3 py-2 shadow-lg">
                            <div class="font-display text-xs font-bold flex items-center gap-1.5"><i
                                    class="fa-regular fa-clock text-primary"></i>
                                {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}</div>
                        </div>
                        <div class="absolute -top-3 right-3 rounded-2xl px-3 py-2 shadow-lg" style="background:#f5a623;">
                            <div class="font-display text-xs font-bold text-gray-900 flex items-center gap-1.5"><i
                                    class="fa-solid fa-truck-fast"></i>
                                {{ getSetting('free_delivery_text', 'ফ্রি ডেলিভারি') }}</div>
                        </div>
                    </div>
                </div>

                {{-- CTA buttons --}}
                <div class="reveal mt-7 flex gap-3">
                    <a href="{{ route('menu.index') }}"
                        class="btn-primary inline-flex flex-1 items-center justify-center gap-2 rounded-full py-3.5 text-center text-sm font-black sm:flex-none sm:px-8">
                        <i class="fa-solid fa-utensils"></i> {{ getSetting('hero_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('about') }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-full border border-white/25 bg-white/8 py-3.5 text-center text-sm font-black text-white transition hover:bg-white/15 hover:-translate-y-0.5 active:scale-95 sm:flex-none sm:px-8">
                        {{ getSetting('hero_secondary_button_text', 'আমাদের গল্প →') }}
                    </a>
                </div>

                {{-- Stats strip --}}
                @php
                    $stats = getSetting('hero_stats', [
                        ['৪০+', 'পদের মেনু'],
                        ['১০K+', 'খুশি গ্রাহক'],
                        ['⭐৪.৮', 'গ্রাহক রেটিং'],
                    ]);
                    if (!is_array($stats)) {
                        $stats = [['৪০+', 'পদের মেনু'], ['১০K+', 'খুশি গ্রাহক'], ['⭐৪.৮', 'গ্রাহক রেটিং']];
                    }
                @endphp
                <div class="reveal mt-8 grid grid-cols-3 overflow-hidden rounded-2xl"
                    style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.10); backdrop-filter: blur(8px);">
                    @foreach ($stats as $stat)
                        <div class="border-r py-4 text-center last:border-r-0" style="border-color:rgba(255,255,255,.10);">
                            <div class="font-display text-xl font-black sm:text-2xl" style="color:#f5a623;">
                                {{ $stat[0] ?? '' }}</div>
                            <div class="mt-1 text-[11px] sm:text-xs" style="color:rgba(255,255,255,.45);">
                                {{ $stat[1] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hero image (desktop only) --}}
            <div class="relative mt-10 hidden lg:col-span-5 lg:block">
                <div class="relative">
                    {{-- glow ring --}}
                    <div class="absolute inset-0 rounded-[2rem] opacity-50 blur-2xl"
                        style="background: radial-gradient(circle at 30% 30%, #e8671a, transparent 60%);"></div>

                    <img src="{{ asset(getSetting('hero_image', 'images/food/hero.jpeg')) }}" alt="চিল ঘরের বিশেষ খাবার"
                        loading="eager"
                        class="relative aspect-square w-full rounded-[2rem] object-cover ring-2 ring-white/15 shadow-glow transition duration-500 hover:scale-[1.02]"
                        onerror="this.src='https://placehold.co/600x600/3d2010/f5a623?text=চিল+ঘর'">

                    {{-- floating cards --}}
                    <div
                        class="absolute -bottom-5 -left-5 animate-float rounded-2xl bg-white/95 backdrop-blur-md px-4 py-3 shadow-xl ring-1 ring-black/5">
                        <div class="font-display text-sm font-bold flex items-center gap-2">
                            <i class="fa-regular fa-clock text-primary"></i>
                            {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}
                        </div>
                        <div class="text-[11px] text-gray-500 mt-0.5">
                            {{ getSetting('delivery_time_label', 'দ্রুত ডেলিভারি') }}</div>
                    </div>
                    <div class="absolute -right-5 top-8 animate-float rounded-2xl px-4 py-3 shadow-xl ring-1 ring-black/5"
                        style="background:#f5a623; animation-delay:-3s;">
                        <div class="font-display text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-truck-fast"></i> {{ getSetting('free_delivery_text', 'ফ্রি ডেলিভারি') }}
                        </div>
                        <div class="text-[11px] text-gray-700 mt-0.5">
                            {{ getSetting('free_delivery_condition', '৫০০৳+ অর্ডারে') }}</div>
                    </div>

                    {{-- rating chip --}}
                    <div
                        class="absolute -top-4 left-6 rounded-full bg-white/95 px-3 py-1.5 shadow-lg backdrop-blur-md ring-1 ring-black/5">
                        <span class="text-xs font-black text-charcoal flex items-center gap-1">
                            <i class="fa-solid fa-star text-spice"></i> ৪.৮ <span
                                class="text-charcoal/50 font-bold">(১.২K+)</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wavy bottom divider --}}
        <svg class="block w-full -mb-px" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0,30 C240,60 480,0 720,20 C960,40 1200,55 1440,15 L1440,60 L0,60 Z" fill="#faf6ef" />
        </svg>
    </section>

    {{-- ===== DELIVERY STATUS BAR ===== --}}
    <div class="relative -mt-1 overflow-hidden text-white"
        style="background: linear-gradient(90deg, #c0392b 0%, #e8671a 50%, #c0392b 100%); background-size: 200% 100%; animation: shimmer 10s linear infinite;">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-2.5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-300 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-green-400"></span>
                </span>
                <span class="text-xs font-black">এখন ডেলিভারি চালু আছে</span>
            </div>
            <span class="text-xs font-bold text-white/85"><i class="fa-regular fa-clock mr-1"></i>
                {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}</span>
        </div>
    </div>

    {{-- ===== ADVERTISEMENTS ===== --}}
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.advertisement', ['ads' => $ads])
    </div>

    {{-- ===== CATEGORIES ===== --}}
    <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="reveal mb-5 flex items-end justify-between">
            <div>
                <p class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-[0.22em]"
                    style="color:#c0392b;">
                    <span class="inline-block h-px w-6 bg-primary"></span>
                    {{ getSetting('categories_badge', 'ক্যাটাগরি') }}
                </p>
                <h2 class="mt-1.5 font-display text-2xl font-black sm:text-3xl">
                    {{ getSetting('categories_title', 'পছন্দ বেছে নিন') }}
                </h2>
            </div>
            <a href="{{ route('menu.index') }}"
                class="inline-flex items-center gap-1 text-sm font-black hover:gap-2 transition-all"
                style="color:#c0392b;">সব <i class="fa-solid fa-arrow-right text-[11px]"></i></a>
        </div>

        <div
            class="flex gap-3 overflow-x-auto pb-2 scrollbar-none sm:grid sm:grid-cols-4 sm:overflow-visible lg:grid-cols-7">
            @foreach ($categories as $i => $cat)
                <a href="{{ route('menu.index', ['category' => $cat->slug]) }}"
                    class="reveal group flex flex-shrink-0 flex-col items-center gap-2 rounded-2xl border border-charcoal/8 bg-white p-4 text-center shadow-ring transition hover:border-primary/40 hover:-translate-y-1 hover:shadow-warm sm:flex-shrink"
                    style="transition-delay: {{ $i * 60 }}ms;">
                    <span
                        class="text-3xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">{{ $cat->emoji }}</span>
                    <span class="text-xs font-black leading-tight">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===== HOME PAGE PRODUCTS ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
        <div class="reveal mb-5 flex items-end justify-between">
            <div>
                <p class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-[0.22em]"
                    style="color:#c0392b;">
                    <span class="inline-block h-px w-6 bg-primary"></span>
                    {{ getSetting('popular_badge', '🏆 জনপ্রিয়') }}
                </p>
                <h2 class="mt-1.5 font-display text-2xl font-black sm:text-3xl">
                    {{ getSetting('popular_title', 'আজকের বেস্ট') }}
                </h2>
            </div>
            <a href="{{ route('menu.index') }}"
                class="inline-flex items-center gap-1 text-sm font-black hover:gap-2 transition-all"
                style="color:#c0392b;">সব <i class="fa-solid fa-arrow-right text-[11px]"></i></a>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @forelse ($homeProducts as $i => $product)
                <div class="reveal" style="transition-delay: {{ $i * 70 }}ms;">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-charcoal/10 bg-white p-8 text-center">
                    <p class="text-charcoal/50">কোন প্রোডাক্ট পাওয়া যায়নি</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
        @php
            $features = getSetting('features', [
                ['🚚', 'ফ্রি ডেলিভারি', '৫০০ টাকার উপরে অর্ডারে'],
                ['⏱️', '২০-৩০ মিনিট', 'দ্রুত ডেলিভারি গ্যারান্টি'],
                ['💰', 'ক্যাশ অন ডেলিভারি', 'খাবার পেয়ে পেমেন্ট করুন'],
            ]);
            if (!is_array($features)) {
                $features = [
                    ['🚚', 'ফ্রি ডেলিভারি', '৫০০ টাকার উপরে অর্ডারে'],
                    ['⏱️', '২০-৩০ মিনিট', 'দ্রুত ডেলিভারি গ্যারান্টি'],
                    ['💰', 'ক্যাশ অন ডেলিভারি', 'খাবার পেয়ে পেমেন্ট করুন'],
                ];
            }
        @endphp
        <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
            @foreach ($features as $i => $feature)
                <div class="reveal group flex items-center gap-4 rounded-2xl border border-charcoal/8 bg-white p-4 shadow-ring transition hover:-translate-y-0.5 hover:shadow-warm hover:border-primary/30"
                    style="transition-delay: {{ $i * 80 }}ms;">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl text-2xl shadow-warm transition-transform group-hover:rotate-6 group-hover:scale-110"
                        style="background: linear-gradient(135deg,#c0392b,#e8671a);">
                        {{ $feature[0] ?? '🎁' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-display font-black text-sm">{{ $feature[1] ?? '' }}</div>
                        <div class="text-xs text-charcoal/55 mt-0.5">{{ $feature[2] ?? '' }}</div>
                    </div>
                    <i
                        class="fa-solid fa-arrow-right text-charcoal/20 group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10 grain reveal"
            style="background:linear-gradient(160deg, #1c0f09 0%, #2a1812 50%, #3d2010 100%);">
            <div
                class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full border-[36px] border-red-700/20">
            </div>
            <div class="pointer-events-none absolute -bottom-10 left-1/3 h-40 w-40 rounded-full opacity-20 blur-2xl"
                style="background:#e8671a;"></div>
            <div class="pointer-events-none absolute -left-10 top-1/2 h-32 w-32 rounded-full opacity-15 blur-2xl"
                style="background:#f5a623;"></div>

            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <span
                        class="mb-3 inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-[11px] font-black uppercase tracking-[0.16em]"
                        style="background:rgba(245,166,35,.12);border-color:rgba(245,166,35,.3);color:#f5a623;">
                        <i class="fa-solid fa-utensils"></i> {{ getSetting('cta_badge', 'পরিবারের জন্য অর্ডার করুন') }}
                    </span>
                    <h2 class="font-display text-2xl font-black leading-tight text-white sm:text-3xl lg:text-4xl">
                        {!! getSetting('cta_title', 'ফ্যামিলি প্যাক ও<br>ইভেন্ট ক্যাটারিং') !!}
                    </h2>
                    <p class="mt-3 max-w-md text-sm leading-relaxed" style="color:rgba(255,255,255,.55);">
                        {{ getSetting('cta_description', 'কর্পোরেট লাঞ্চ, জন্মদিন, বিয়ের অনুষ্ঠান — সব আয়োজনে চিল ঘর।') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-stretch">
                    <a href="{{ route('menu.index') }}"
                        class="btn-primary inline-flex items-center justify-center gap-2 rounded-full py-3.5 px-8 text-center text-sm font-black">
                        <i class="fa-solid fa-utensils"></i> {{ getSetting('cta_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-white/25 bg-transparent py-3.5 px-8 text-center text-sm font-black text-white hover:bg-white/10 transition active:scale-95">
                        {{ getSetting('cta_secondary_button_text', 'যোগাযোগ করুন') }} <i
                            class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
