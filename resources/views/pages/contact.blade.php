@extends('layouts.app')
@section('title', 'যোগাযোগ — চিল ঘর')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
  <p class="text-xs font-bold uppercase tracking-widest text-primary">যোগাযোগ</p>
  <h1 class="mt-1 font-display text-4xl font-bold sm:text-5xl">আমাদের সাথে কথা বলুন</h1>

  <div class="mt-10 grid gap-6 sm:grid-cols-3">
    <a href="tel:+8801711000000" class="rounded-2xl border border-charcoal/10 bg-white p-6 text-center shadow-soft transition hover:-translate-y-1 hover:border-primary hover:shadow-warm">
      <div class="text-3xl">📞</div><div class="mt-3 font-display font-bold">কল করুন</div><div class="mt-1 text-sm text-charcoal/60">+৮৮০ ১৭১১-০০০০০০</div>
    </a>
    <a href="mailto:hello@biyaibari.com" class="rounded-2xl border border-charcoal/10 bg-white p-6 text-center shadow-soft transition hover:-translate-y-1 hover:border-primary hover:shadow-warm">
      <div class="text-3xl">✉️</div><div class="mt-3 font-display font-bold">ইমেইল</div><div class="mt-1 text-sm text-charcoal/60">hello@biyaibari.com</div>
    </a>
    <div class="rounded-2xl border border-charcoal/10 bg-white p-6 text-center shadow-soft">
      <div class="text-3xl">📍</div><div class="mt-3 font-display font-bold">ঠিকানা</div><div class="mt-1 text-sm text-charcoal/60">ধানমন্ডি, ঢাকা</div>
    </div>
  </div>
</div>
@endsection
