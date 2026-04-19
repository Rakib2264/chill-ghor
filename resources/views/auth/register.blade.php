<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>রেজিস্ট্রেশন — চিল ঘর</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { primary: '#c0392b', cream: '#faf6ef', charcoal: '#2a1d18' }, fontFamily: { sans: ['Hind Siliguri','sans-serif'], display: ['Tiro Bangla','serif'] }, backgroundImage: { 'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)' }, boxShadow: { warm: '0 10px 30px -10px rgba(192,57,43,0.35)' } } } };
  </script>
  <style>body { font-family: 'Hind Siliguri', sans-serif; } .font-display { font-family: 'Tiro Bangla', serif; }</style>
</head>
<body class="min-h-screen bg-cream">

<div class="flex min-h-screen items-center justify-center px-4 py-10">
  <div class="w-full max-w-md">

    <div class="mb-6 flex items-center justify-center gap-3">
      <div class="flex h-14 w-14 items-center justify-center rounded-2xl text-2xl shadow-warm">
        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt=""
             onerror="this.outerHTML='<div class=&quot;flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-warm text-2xl shadow-warm&quot;>☕</div>'">
      </div>
      <div class="text-left">
        <div class="font-display text-2xl font-bold">চিল ঘর</div>
        <div class="text-[10px] uppercase tracking-widest text-charcoal/60">Create Account</div>
      </div>
    </div>

    <div class="rounded-3xl border border-charcoal/10 bg-white p-8 shadow-warm">
      <h1 class="font-display text-2xl font-bold">নতুন অ্যাকাউন্ট তৈরি করুন ✨</h1>
      <p class="mt-1 text-sm text-charcoal/60">অর্ডার হিস্টরি ও দ্রুত চেকআউটের জন্য</p>

      @if ($errors->any())
        <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-3 text-sm text-primary">
          <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">নাম *</span>
          <input type="text" name="name" value="{{ old('name') }}" required autofocus
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
        </label>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">ইমেইল *</span>
          <input type="email" name="email" value="{{ old('email') }}" required
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
        </label>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">ফোন</span>
          <input type="tel" name="phone" value="{{ old('phone') }}"
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
        </label>
        <div class="grid grid-cols-2 gap-3">
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">পাসওয়ার্ড *</span>
            <input type="password" name="password" required
                   class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">নিশ্চিত করুন *</span>
            <input type="password" name="password_confirmation" required
                   class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
          </label>
        </div>
        <button class="w-full rounded-full bg-gradient-warm py-3 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">
          ✅ রেজিস্ট্রেশন করুন
        </button>
      </form>

      <div class="mt-5 text-center">
        <p class="text-sm text-charcoal/60">
          ইতিমধ্যে অ্যাকাউন্ট আছে?
          <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">লগইন করুন</a>
        </p>
      </div>

      <div class="mt-5 rounded-xl bg-cream p-4 text-xs text-charcoal/65">
        <div class="font-bold text-charcoal mb-1">🎁 যা পাবেন</div>
        <ul class="space-y-0.5">
          <li>✓ অর্ডার হিস্টরি ট্র্যাক</li>
          <li>✓ একাধিক ঠিকানা সংরক্ষণ</li>
          <li>✓ দ্রুত চেকআউট</li>
        </ul>
      </div>
    </div>

    <div class="mt-4 text-center">
      <a href="{{ route('home') }}" class="text-xs font-bold text-charcoal/60 hover:text-primary">← মূল সাইটে ফিরে যান</a>
    </div>
  </div>
</div>

</body>
</html>
