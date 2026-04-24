@extends('layouts.app')
@php
    use App\Models\Setting;

    function getSetting($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    $pageTitle = getSetting('about_title', 'আমাদের সম্পর্কে — চিল ঘর');
    $heroBadge = getSetting('about_hero_badge', 'আমাদের সম্পর্কে');
    $heroTitle = getSetting('about_hero_title', 'যেখানে স্বাদ মিশে যায়');
    $heroSubtitle = getSetting('about_hero_subtitle', 'গ্রামীণ আন্তরিকতায়, শহুরে স্বাদে');
    $heroImage = getSetting('about_hero_image', 'images/about/hero.jpg');

    $storyText = getSetting(
        'about_story',
        '<p>১৯৯৩ সালে নানার ছোট্ট রান্নাঘর থেকে শুরু — আজ বনগ্রামের অন্যতম প্রিয় রেস্তোরাঁ। চিল ঘরে আপনি পাবেন সেই খাঁটি ঘরোয়া স্বাদ — কাচ্চি, ইলিশ সরিষা, গরুর ভুনা, ফুচকা — যা আপনাকে নিয়ে যাবে শৈশবের রান্নাঘরে।</p><p>আমরা বিশ্বাস করি, ভালো খাবারই পরিবার ও বন্ধুত্বের সবচেয়ে বড় উপহার। তাই প্রতিটি প্লেটে থাকে যত্ন, ভালোবাসা আর তিন প্রজন্মের অভিজ্ঞতা।</p>',
    );

    // ✅ FIXED: Get values directly (no json_encode/json_decode needed)
    $values = getSetting('about_values', null);

    // Check if already an array, otherwise use default
    if (!is_array($values) || empty($values)) {
        $values = [
            ['❤️', 'ঘরোয়া রেসিপি', 'মা-নানির হাতের স্বাদ অপরিবর্তিত।'],
            ['🌿', 'ফ্রেশ উপকরণ', 'প্রতিদিন বাজার থেকে তাজা মাছ, মাংস ও সবজি।'],
            ['👨‍🍳', '৩০+ বছরের অভিজ্ঞতা', 'তিন প্রজন্মের রন্ধনশিল্পী।'],
            ['😊', '১০,০০০+ সন্তুষ্ট গ্রাহক', 'আস্থা ও ভালোবাসা।'],
        ];
    }
@endphp

@section('title', $pageTitle)

@section('content')

    {{-- Hero --}}
    {{-- <div class="relative overflow-hidden">
        <img src="{{ asset($heroImage) }}" alt="চিল ঘর" class="h-64 w-full object-cover brightness-40"
            onerror="this.src='https://placehold.co/1200x400/faf6ef/c0392b?text=চিল+ঘর'">
        <div class="absolute inset-0 bg-gradient-to-b from-charcoal/60 to-charcoal/90"></div>
        <div class="absolute inset-0 flex items-center justify-center text-center text-white px-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-spice mb-3">{{ $heroBadge }}</p>
                <h1 class="font-display text-4xl font-bold sm:text-5xl">{{ $heroTitle }}</h1>
                <p class="mt-3 text-cream/70 text-sm sm:text-base">{{ $heroSubtitle }}</p>
            </div>
        </div>
    </div> --}}

    {{-- Story --}}
    <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="prose-like space-y-5 text-base leading-relaxed text-charcoal/75">
            {!! $storyText !!}
        </div>

        {{-- Values --}}
        <div class="mt-12 grid gap-4 sm:grid-cols-2">
            @foreach ($values as $value)
                <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-2xl mb-4">
                        {{ $value[0] ?? '❤️' }}
                    </div>
                    <div class="font-display font-bold text-base mb-1">{{ $value[1] ?? '' }}</div>
                    <div class="text-sm text-charcoal/60">{{ $value[2] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
