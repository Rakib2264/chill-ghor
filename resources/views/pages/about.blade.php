@extends('layouts.app')
@section('title', 'আমাদের সম্পর্কে — চিল ঘর')

@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden">
  <img src="{{ asset('images/about/hero.jpg') }}" alt="চিল ঘর"
       class="h-64 w-full object-cover brightness-40"
       onerror="this.style.display='none'">
  <div class="absolute inset-0 bg-gradient-to-b from-charcoal/60 to-charcoal/90"></div>
  <div class="absolute inset-0 flex items-center justify-center text-center text-white px-4">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-spice mb-3">আমাদের সম্পর্কে</p>
      <h1 class="font-display text-4xl font-bold sm:text-5xl">যেখানে স্বাদ মিশে যায়</h1>
      <p class="mt-3 text-cream/70 text-sm sm:text-base">গ্রামীণ আন্তরিকতায়, শহুরে স্বাদে</p>
    </div>
  </div>
</div>

{{-- Story --}}
<div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
  <div class="prose-like space-y-5 text-base leading-relaxed text-charcoal/75">
    <p>
      ১৯৯৩ সালে নানার ছোট্ট রান্নাঘর থেকে শুরু — আজ বনগ্রামের অন্যতম প্রিয় রেস্তোরাঁ।
      চিল ঘরে আপনি পাবেন সেই খাঁটি ঘরোয়া স্বাদ — কাচ্চি, ইলিশ সরিষা, গরুর ভুনা, ফুচকা
      — যা আপনাকে নিয়ে যাবে শৈশবের রান্নাঘরে।
    </p>
    <p>
      আমরা বিশ্বাস করি, ভালো খাবারই পরিবার ও বন্ধুত্বের সবচেয়ে বড় উপহার।
      তাই প্রতিটি প্লেটে থাকে যত্ন, ভালোবাসা আর তিন প্রজন্মের অভিজ্ঞতা।
    </p>
  </div>

  {{-- Values --}}
  <div class="mt-12 grid gap-4 sm:grid-cols-2">
    @foreach ([
      ['❤️', 'ঘরোয়া রেসিপি', 'মা-নানির হাতের স্বাদ অপরিবর্তিত।'],
      ['🌿', 'ফ্রেশ উপকরণ', 'প্রতিদিন বাজার থেকে তাজা মাছ, মাংস ও সবজি।'],
      ['👨‍🍳', '৩০+ বছরের অভিজ্ঞতা', 'তিন প্রজন্মের রন্ধনশিল্পী।'],
      ['😊', '১০,০০০+ সন্তুষ্ট গ্রাহক', 'আস্থা ও ভালোবাসা।'],
    ] as [$icon, $title, $sub])
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-2xl mb-4">{{ $icon }}</div>
        <div class="font-display font-bold text-base mb-1">{{ $title }}</div>
        <div class="text-sm text-charcoal/60">{{ $sub }}</div>
      </div>
    @endforeach
  </div>
</div>

@endsection