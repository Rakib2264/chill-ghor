<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>লগইন — চিল ঘর অ্যাডমিন</title>
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

<div class="flex min-h-screen items-center justify-center px-4">
  <div class="w-full max-w-md">

    <div class="mb-6 flex items-center justify-center gap-3">
      <div class="flex h-14 w-14 items-center justify-center rounded-2xl text-2xl shadow-warm">
        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt="">
      </div>
      <div class="text-left">
        <div class="font-display text-2xl font-bold">চিল ঘর</div>
        <div class="text-[10px] uppercase tracking-widest text-charcoal/60">Admin Login</div>
      </div>
    </div>

    <div class="rounded-3xl border border-charcoal/10 bg-white p-8 shadow-warm">
      <h1 class="font-display text-2xl font-bold">স্বাগতম 👋</h1>
      <p class="mt-1 text-sm text-charcoal/60">অ্যাডমিন প্যানেলে প্রবেশ করুন</p>

      @if ($errors->any())
        <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-3 text-sm text-primary">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">ইমেইল</span>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
        </label>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">পাসওয়ার্ড</span>
          <input type="password" name="password" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
        </label>
        <label class="flex items-center gap-2 text-xs">
          <input type="checkbox" name="remember" class="h-4 w-4 accent-primary">
          আমাকে মনে রাখুন
        </label>
        <button class="w-full rounded-full bg-gradient-warm py-3 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">লগইন করুন →</button>
      </form>

      {{-- ✅ Register Link Added Here --}}
      <div class="mt-5 text-center">
        <p class="text-sm text-charcoal/60">
          অ্যাকাউন্ট নেই? 
          <a href="{{ route('register') }}" class="font-bold text-primary hover:underline transition">
            রেজিস্ট্রেশন করুন
          </a>
        </p>
      </div>

      <div class="mt-5 rounded-xl bg-cream p-4 text-xs text-charcoal/70">
        <div class="font-bold text-charcoal">🔑 ডিফল্ট অ্যাডমিন</div>
        <div class="mt-1">ইমেইল: <span class="font-mono">admin@biyaibari.com</span></div>
        <div>পাসওয়ার্ড: <span class="font-mono">password</span></div>
      </div>
    </div>

    <div class="mt-4 text-center">
      <a href="{{ route('home') }}" class="text-xs font-bold text-charcoal/60 hover:text-primary">← মূল সাইটে ফিরে যান</a>
    </div>
  </div>
</div>

</body>
</html>