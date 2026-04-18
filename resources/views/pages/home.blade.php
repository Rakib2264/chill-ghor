@extends('layouts.app')
@section('title', 'চিল ঘর — ঘরের স্বাদ, রেস্টুরেন্টে')

@section('content')

<section class="relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-cream to-spice/10"></div>
  <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
    <div class="animate-slide-up">
      <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-primary">
        🌶️ ঐতিহ্যবাহী বাঙালি রেস্টুরেন্ট
      </span>
      <h1 class="mt-5 font-display text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
        ঘরের স্বাদ,<br><span class="gradient-text">রেস্টুরেন্টে</span>
      </h1>
      <p class="mt-5 max-w-lg text-base text-charcoal/70 sm:text-lg">
        কাচ্চি বিরিয়ানি থেকে ইলিশ সরিষা — ৩০+ বছরের ঐতিহ্যবাহী রেসিপিতে রান্না করা প্রতিটি পদ। ফ্রেশ ইনগ্রেডিয়েন্ট, খাঁটি মসলা, মা-নানির হাতের স্বাদ।
      </p>
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-warm px-7 py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-105">
          🍽️ মেনু দেখুন
        </a>
        <a href="{{ route('about') }}" class="inline-flex items-center gap-2 rounded-full border border-charcoal/20 bg-white px-7 py-3.5 text-sm font-bold transition hover:border-primary hover:text-primary">
          আমাদের গল্প →
        </a>
      </div>
      <div class="mt-10 flex flex-wrap gap-6 text-sm">
        <div><span class="font-display text-2xl font-bold gradient-text">৩০+</span><div class="text-xs text-charcoal/60">বছরের অভিজ্ঞতা</div></div>
        <div><span class="font-display text-2xl font-bold gradient-text">৫০+</span><div class="text-xs text-charcoal/60">পদের মেনু</div></div>
        <div><span class="font-display text-2xl font-bold gradient-text">১০K+</span><div class="text-xs text-charcoal/60">খুশি গ্রাহক</div></div>
      </div>
    </div>
    <div class="relative animate-fade-in">
      <div class="absolute -inset-4 rounded-3xl bg-gradient-warm opacity-20 blur-3xl"></div>
      <img src="{{ asset('images/food/hero-feast.jpg') }}" alt="Bangladeshi feast"
           class="relative aspect-square w-full rounded-3xl object-cover shadow-warm">
    </div>
  </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  <div class="flex items-end justify-between">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">ক্যাটাগরি</p>
      <h2 class="mt-1 font-display text-3xl font-bold sm:text-4xl">কী খাবেন আজ?</h2>
    </div>
    <a href="{{ route('menu.index') }}" class="hidden text-sm font-bold text-primary hover:underline sm:block">সব দেখুন →</a>
  </div>
  <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
    @foreach ($categories as $cat)
      <a href="{{ route('menu.index', ['category' => $cat->slug]) }}"
         class="group flex flex-col items-center gap-2 rounded-2xl border border-charcoal/10 bg-white p-4 text-center shadow-soft transition hover:-translate-y-1 hover:border-primary hover:shadow-warm">
        <div class="text-3xl transition group-hover:scale-110">{{ $cat->emoji }}</div>
        <div class="text-xs font-bold leading-tight">{{ $cat->name }}</div>
      </a>
    @endforeach
  </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  <div class="flex items-end justify-between">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">🏆 জনপ্রিয়</p>
      <h2 class="mt-1 font-display text-3xl font-bold sm:text-4xl">টপ পদসমূহ</h2>
    </div>
    <a href="{{ route('menu.index') }}" class="text-sm font-bold text-primary hover:underline">সব দেখুন →</a>
  </div>
  <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($popular as $product)
      @include('partials.product-card', ['product' => $product])
    @endforeach
  </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  <div class="grid gap-4 sm:grid-cols-3">
    @foreach ([['🚚', 'ফ্রি ডেলিভারি', '৫০০ টাকার উপরে অর্ডারে'], ['⏱️', '২০-৩০ মিনিট', 'দ্রুত ডেলিভারি গ্যারান্টি'], ['💰', 'ক্যাশ অন ডেলিভারি', 'খাবার পেয়ে পেমেন্ট করুন']] as $item)
      <div class="flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft">
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-warm text-2xl shadow-warm">{{ $item[0] }}</div>
        <div>
          <div class="font-display font-bold">{{ $item[1] }}</div>
          <div class="text-xs text-charcoal/60">{{ $item[2] }}</div>
        </div>
      </div>
    @endforeach
  </div>
</section>

@endsection
