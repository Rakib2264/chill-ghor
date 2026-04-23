@extends('layouts.app')
@php
    use App\Models\Setting;

    // Helper function to get settings with automatic JSON decoding
    function getSetting($key, $default = null)
    {
        $value = Setting::get($key, $default);

        // If value is already an array, return it directly
        if (is_array($value)) {
            return $value;
        }

        // If it's a string that looks like JSON, decode it
    if (is_string($value)) {
        $trimmed = trim($value);
        if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
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
    <section class="relative overflow-hidden bg-gradient-to-br from-charcoal via-[#3d2d28] to-primary">
        {{-- bg glow --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 left-1/4 h-96 w-96 rounded-full bg-primary/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 h-64 w-64 rounded-full bg-spice/20 blur-3xl"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
            {{-- Left --}}
            <div class="animate-slide-up text-cream">
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                    {{ getSetting('hero_tagline', '☕ চা–কফির আড্ডা, ফাস্ট ফুডের আসল স্বাদ') }}
                </span>
                <h1 class="mt-5 font-display text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {!! getSetting(
                        'hero_title',
                        'ঘরের স্বাদ,<br><span class="bg-gradient-to-r from-spice to-[#e8671a] bg-clip-text text-transparent">রেস্টুরেন্টে</span>',
                    ) !!}
                </h1>
                <p class="mt-5 max-w-lg text-sm leading-relaxed text-cream/75 sm:text-base">
                    {{ getSetting('hero_description', '🌿 গ্রামীণ পরিবেশে শহরের আধুনিক ফিল। বনগ্রাম স্কুল ও কলেজের সামনে — কাচ্চি বিরিয়ানি, ইলিশ সরিষা, ফুচকা থেকে চিকেন বার্গার। আপনাদেরই প্রিয় আড্ডাখানা!') }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('menu.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-warm px-7 py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-105 hover:shadow-[0_12px_30px_-8px_rgba(192,57,43,0.5)]">
                        🍽️ {{ getSetting('hero_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-7 py-3.5 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20">
                        {{ getSetting('hero_secondary_button_text', 'আমাদের গল্প →') }}
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap gap-6 sm:gap-10">
                    @php
                        // Now getSetting returns array directly if it's JSON
$stats = getSetting('hero_stats', [
    ['৩০+', 'বছরের অভিজ্ঞতা'],
    ['৫০+', 'পদের মেনু'],
    ['১০K+', 'খুশি গ্রাহক'],
    ['⭐৪.৮', 'গ্রাহক রেটিং'],
]);

// Ensure $stats is an array
if (!is_array($stats)) {
    $stats = [
        ['৩০+', 'বছরের অভিজ্ঞতা'],
        ['৫০+', 'পদের মেনু'],
        ['১০K+', 'খুশি গ্রাহক'],
        ['⭐৪.৮', 'গ্রাহক রেটিং'],
                            ];
                        }
                    @endphp
                    @foreach ($stats as $stat)
                        <div>
                            <span
                                class="font-display text-2xl font-bold bg-gradient-to-r from-spice to-[#e8671a] bg-clip-text text-transparent">{{ $stat[0] ?? '০' }}</span>
                            <div class="text-xs text-cream/55 mt-0.5">{{ $stat[1] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right image --}}
            <div class="relative animate-fade-in hidden lg:block">
                <div class="absolute -inset-6 rounded-3xl bg-gradient-warm opacity-15 blur-3xl"></div>
                <img src="{{ asset(getSetting('hero_image', 'images/food/hero.jpeg')) }}" alt="চিল ঘর খাবার"
                    class="relative aspect-square w-full rounded-3xl object-cover shadow-warm ring-4 ring-white/10 transition duration-500 hover:scale-[1.02]"
                    onerror="this.src='https://placehold.co/600x600/faf6ef/c0392b?text=চিল+ঘর'">

                {{-- floating badge 1 --}}
                <div class="absolute -bottom-4 -left-4 rounded-2xl bg-white px-4 py-3 shadow-warm">
                    <div class="font-display text-sm font-bold">🕐 {{ getSetting('delivery_time', '২০-৩০ মিনিট') }}</div>
                    <div class="text-xs text-charcoal/55">{{ getSetting('delivery_time_label', 'দ্রুত ডেলিভারি') }}</div>
                </div>

                {{-- floating badge 2 --}}
                <div class="absolute -right-4 top-8 rounded-2xl bg-spice px-4 py-3 shadow-warm">
                    <div class="font-display text-sm font-bold text-charcoal">🚚
                        {{ getSetting('free_delivery_text', 'ফ্রি ডেলিভারি') }}</div>
                    <div class="text-xs text-charcoal/70">{{ getSetting('free_delivery_condition', '৫০০৳+ অর্ডারে') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CATEGORIES ===== --}}
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary">
                    {{ getSetting('categories_badge', 'ক্যাটাগরি') }}</p>
                <h2 class="mt-1 font-display text-3xl font-bold sm:text-4xl">
                    {{ getSetting('categories_title', 'আপনার পছন্দ বেছে নিন') }}</h2>
            </div>
            <a href="{{ route('menu.index') }}"
                class="hidden text-sm font-bold text-primary hover:underline sm:block">{{ getSetting('categories_link_text', 'সব দেখুন →') }}</a>
        </div>

        <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-7">
            @foreach ($categories as $cat)
                <a href="{{ route('menu.index', ['category' => $cat->slug]) }}"
                    class="group flex flex-col items-center gap-2.5 rounded-2xl border border-charcoal/10 bg-white p-4 text-center shadow-soft transition hover:-translate-y-1 hover:border-primary hover:shadow-warm">
                    <div class="text-3xl transition-transform duration-300 group-hover:scale-110">{{ $cat->emoji }}</div>
                    <div class="text-xs font-bold leading-tight">{{ $cat->name }}</div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===== POPULAR ITEMS ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-14 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary">
                    {{ getSetting('popular_badge', '🏆 জনপ্রিয়') }}</p>
                <h2 class="mt-1 font-display text-3xl font-bold sm:text-4xl">
                    {{ getSetting('popular_title', 'আজকের সবচেয়ে বেশি অর্ডার') }}</h2>
            </div>
            <a href="{{ route('menu.index') }}"
                class="text-sm font-bold text-primary hover:underline">{{ getSetting('popular_link_text', 'সব দেখুন →') }}</a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($popular as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-14 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-3">
            @php
                // getSetting now returns array directly
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
            @foreach ($features as $feature)
                <div class="flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft">
                    <div
                        class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-warm text-2xl shadow-warm">
                        {{ $feature[0] ?? '🎁' }}
                    </div>
                    <div>
                        <div class="font-display font-bold">{{ $feature[1] ?? '' }}</div>
                        <div class="text-xs text-charcoal/55 mt-0.5">{{ $feature[2] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-warm px-8 py-10 shadow-warm">
            <div
                class="pointer-events-none absolute right-0 top-0 h-64 w-64 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="relative flex flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-white">
                    <h2 class="font-display text-2xl font-bold sm:text-3xl">
                        {{ getSetting('cta_title', 'পরিবারের সবার জন্য অর্ডার করুন') }}</h2>
                    <p class="mt-2 text-sm text-white/80">
                        {{ getSetting('cta_description', 'ফ্যামিলি প্যাক, কর্পোরেট লাঞ্চ ও ইভেন্ট ক্যাটারিং পাওয়া যাচ্ছে।') }}
                    </p>
                </div>
                <div class="flex gap-3 flex-shrink-0">
                    <a href="{{ route('menu.index') }}"
                        class="rounded-full bg-white px-6 py-3 text-sm font-bold text-primary shadow-sm transition hover:scale-105">
                        {{ getSetting('cta_button_text', 'মেনু দেখুন') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="rounded-full border-2 border-white/60 bg-transparent px-6 py-3 text-sm font-bold text-white transition hover:bg-white/15">
                        {{ getSetting('cta_secondary_button_text', 'যোগাযোগ') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
