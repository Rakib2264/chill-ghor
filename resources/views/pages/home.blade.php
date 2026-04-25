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
    <section style="background:#1c0f09;" class="relative overflow-hidden">
        {{-- decorative rings --}}
        <div class="pointer-events-none absolute -top-16 -right-16 h-64 w-64 rounded-full border-[40px] border-primary/10">
        </div>
        <div class="pointer-events-none absolute bottom-0 left-4 h-40 w-40 rounded-full opacity-10"
            style="background:#e8671a;"></div>

        <div
            class="relative mx-auto max-w-7xl px-4 pb-8 pt-6 sm:px-6 lg:grid lg:grid-cols-2 lg:items-center lg:gap-12 lg:px-8 lg:py-24">
            <div class="text-white">
                {{-- top bar: logo area on mobile --}}
                <div class="mb-5 flex items-center justify-between lg:hidden">
                    <span class="font-display text-lg font-black text-white">
                        চিল <span style="color:#f5a623">ঘর</span>
                    </span>
                    <div class="flex gap-2">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/10 text-base">📍</span>
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-white/10 text-base">🔔</span>
                    </div>
                </div>

                {{-- badge --}}
                <span class="mb-4 inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-bold"
                    style="background:rgba(245,166,35,.12);border-color:rgba(245,166,35,.3);color:#f5a623;">
                    ☕ {{ getSetting('hero_tagline', 'বনগ্রামের প্রিয় আড্ডাখানা') }}
                </span>

                {{-- h1 --}}
                <h1 class="font-display text-3xl font-black leading-tight text-white sm:text-4xl lg:text-5xl xl:text-6xl">
                    {!! getSetting('hero_title', 'ঘরের স্বাদ,<br><span style="color:#f5a623">রেস্টুরেন্টে</span> 🍛') !!}
                </h1>
                <p class="mt-4 max-w-md text-sm leading-relaxed sm:text-base" style="color:rgba(255,255,255,.55)">
                    {{ getSetting('hero_description', 'কাচ্চি বিরিয়ানি, ইলিশ সরিষা, ফুচকা থেকে চিকেন বার্গার — বনগ্রাম স্কুল ও কলেজের সামনে।') }}
                </p>

                {{-- CTA buttons --}}
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('menu.index') }}"
                        class="flex-1 rounded-full py-3.5 text-center text-sm font-bold text-white transition active:scale-95 sm:flex-none sm:px-8"
                        style="background:#c0392b;">
                        🍽️ {{ getSetting('hero_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('about') }}"
                        class="flex-1 rounded-full border py-3.5 text-center text-sm font-bold text-white transition hover:bg-white/10 active:scale-95 sm:flex-none sm:px-8"
                        style="border-color:rgba(255,255,255,.25);background:rgba(255,255,255,.08);">
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
                <div class="mt-6 flex overflow-hidden rounded-2xl"
                    style="background:rgba(255,255,255,.06);border:0.5px solid rgba(255,255,255,.1);">
                    @foreach ($stats as $stat)
                        <div class="flex-1 border-r py-3 text-center last:border-r-0"
                            style="border-color:rgba(255,255,255,.1);">
                            <div class="font-display text-xl font-black" style="color:#f5a623;">{{ $stat[0] ?? '' }}</div>
                            <div class="mt-0.5 text-xs" style="color:rgba(255,255,255,.4);">{{ $stat[1] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hero image (desktop only) --}}
            <div class="relative mt-10 hidden lg:block">
                <img src="{{ asset(getSetting('hero_image', 'images/food/hero.jpeg')) }}" alt="চিল ঘরের বিশেষ খাবার"
                    loading="eager"
                    class="aspect-square w-full rounded-3xl object-cover ring-2 ring-white/10 transition hover:scale-[1.02]"
                    onerror="this.src='https://placehold.co/600x600/3d2010/f5a623?text=চিল+ঘর'">
                <div class="absolute -bottom-4 -left-4 rounded-2xl bg-white px-4 py-3 shadow-lg">
                    <div class="font-display text-sm font-bold">🕐 {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}</div>
                    <div class="text-xs text-gray-500">{{ getSetting('delivery_time_label', 'দ্রুত ডেলিভারি') }}</div>
                </div>
                <div class="absolute -right-4 top-8 rounded-2xl px-4 py-3 shadow-lg" style="background:#f5a623;">
                    <div class="font-display text-sm font-bold text-gray-900">🚚
                        {{ getSetting('free_delivery_text', 'ফ্রি ডেলিভারি') }}</div>
                    <div class="text-xs text-gray-700">{{ getSetting('free_delivery_condition', '৫০০৳+ অর্ডারে') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== DELIVERY STATUS BAR ===== --}}
    <div style="background:#c0392b;" class="px-4 py-2.5">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-xs font-bold text-white">এখন ডেলিভারি চালু আছে</span>
            </div>
            <span class="text-xs font-semibold text-white/80">🕐 {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}</span>
        </div>
    </div>

    {{-- ===== CATEGORIES ===== --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-5 flex items-end justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest" style="color:#c0392b;">
                    {{ getSetting('categories_badge', 'ক্যাটাগরি') }}</p>
                <h2 class="mt-0.5 font-display text-2xl font-black sm:text-3xl">
                    {{ getSetting('categories_title', 'পছন্দ বেছে নিন') }}</h2>
            </div>
            <a href="{{ route('menu.index') }}" class="text-sm font-bold" style="color:#c0392b;">সব →</a>
        </div>

        {{-- mobile: horizontal scroll, desktop: grid --}}
        <div
            class="flex gap-3 overflow-x-auto pb-2 scrollbar-none sm:grid sm:grid-cols-4 sm:overflow-visible lg:grid-cols-7">
            @foreach ($categories as $cat)
                <a href="{{ route('menu.index', ['category' => $cat->slug]) }}"
                    class="group flex flex-shrink-0 flex-col items-center gap-2 rounded-2xl border bg-white p-3 text-center transition hover:border-red-600/40 hover:-translate-y-1 sm:flex-shrink">
                    <span class="text-3xl transition-transform group-hover:scale-110">{{ $cat->emoji }}</span>
                    <span class="text-xs font-bold leading-tight">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===== POPULAR ITEMS ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
        <div class="mb-5 flex items-end justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest" style="color:#c0392b;">
                    {{ getSetting('popular_badge', '🏆 জনপ্রিয়') }}</p>
                <h2 class="mt-0.5 font-display text-2xl font-black sm:text-3xl">
                    {{ getSetting('popular_title', 'আজকের বেস্ট') }}</h2>
            </div>
            <a href="{{ route('menu.index') }}" class="text-sm font-bold" style="color:#c0392b;">সব →</a>
        </div>

        {{-- 2-col on mobile, 4-col on desktop --}}
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($popular as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
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
        <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
            @foreach ($features as $feature)
                <div class="flex flex-1 items-center gap-4 rounded-2xl border bg-white p-4 transition hover:shadow-md">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl text-2xl"
                        style="background:#c0392b;">
                        {{ $feature[0] ?? '🎁' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-display font-black text-sm">{{ $feature[1] ?? '' }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $feature[2] ?? '' }}</div>
                    </div>
                    <span class="text-2xl text-gray-200">›</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10" style="background:#1c0f09;">
            {{-- decorative --}}
            <div
                class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full border-[36px] border-red-700/20">
            </div>
            <div class="pointer-events-none absolute -bottom-8 left-1/3 h-32 w-32 rounded-full opacity-10"
                style="background:#e8671a;"></div>

            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-bold"
                        style="background:rgba(245,166,35,.12);border-color:rgba(245,166,35,.3);color:#f5a623;">
                        🍽️ {{ getSetting('cta_badge', 'পরিবারের জন্য অর্ডার করুন') }}
                    </span>
                    <h2 class="font-display text-2xl font-black leading-tight text-white sm:text-3xl">
                        {!! getSetting('cta_title', 'ফ্যামিলি প্যাক ও<br>ইভেন্ট ক্যাটারিং') !!}
                    </h2>
                    <p class="mt-2 max-w-sm text-sm leading-relaxed" style="color:rgba(255,255,255,.5);">
                        {{ getSetting('cta_description', 'কর্পোরেট লাঞ্চ, জন্মদিন, বিয়ের অনুষ্ঠান — সব আয়োজনে চিল ঘর।') }}
                    </p>

                </div>

                <div class="flex gap-3 lg:flex-col lg:items-stretch">
                    <a href="{{ route('menu.index') }}"
                        class="flex-1 rounded-full py-3.5 text-center text-sm font-bold text-white transition hover:opacity-90 active:scale-95 lg:flex-none lg:px-8"
                        style="background:#c0392b;">
                        🍽️ {{ getSetting('cta_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="flex-1 rounded-full py-3.5 text-center text-sm font-bold text-white transition hover:bg-white/10 active:scale-95 lg:flex-none lg:px-8"
                        style="border:0.5px solid rgba(255,255,255,.25);background:transparent;">
                        {{ getSetting('cta_secondary_button_text', 'যোগাযোগ করুন') }} →
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
