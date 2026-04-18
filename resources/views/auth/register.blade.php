<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>রেজিস্ট্রেশন — চিল ঘর</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { primary: '#c0392b', cream: '#faf6ef', charcoal: '#2a1d18' }, fontFamily: { sans: ['Hind Siliguri','sans-serif'], display: ['Tiro Bangla','serif'] }, backgroundImage: { 'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)' }, boxShadow: { warm: '0 10px 30px -10px rgba(192,57,43,0.35)' } } } };
  </script>
</head>
<body class="min-h-screen bg-cream flex items-center justify-center p-4">

<div class="w-full max-w-md">
  <div class="text-center mb-6">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
      <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-warm text-2xl shadow-warm">☕</div>
      <span class="font-display text-2xl font-bold">চিল ঘর</span>
    </a>
  </div>

  <div class="rounded-3xl border border-charcoal/10 bg-white p-8 shadow-warm">
    <h1 class="font-display text-2xl font-bold">নতুন অ্যাকাউন্ট</h1>
    <p class="mt-1 text-sm text-charcoal/60">রেজিস্ট্রেশন করে অর্ডার হিস্টরি দেখুন</p>

    @if ($errors->any())
      <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-600">
        <ul class="list-disc pl-5 space-y-1">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="mt-6 space-y-4">
      @csrf
      
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">নাম *</span>
        <input type="text" name="name" value="{{ old('name') }}" required 
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
      </label>

      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">ইমেইল *</span>
        <input type="email" name="email" value="{{ old('email') }}" required 
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
      </label>

      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">ফোন (ঐচ্ছিক)</span>
        <input type="text" name="phone" value="{{ old('phone') }}" 
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
      </label>

      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">ঠিকানা (ঐচ্ছিক)</span>
        <textarea name="address" rows="2" 
                  class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">{{ old('address') }}</textarea>
      </label>

      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">পাসওয়ার্ড *</span>
        <input type="password" name="password" required 
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
      </label>

      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">পাসওয়ার্ড নিশ্চিত করুন *</span>
        <input type="password" name="password_confirmation" required 
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
      </label>

      <button type="submit" 
              class="w-full rounded-full bg-gradient-warm py-3 text-sm font-bold text-white shadow-warm hover:scale-[1.02] transition">
        ✅ রেজিস্ট্রেশন করুন
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-charcoal/60">
      ইতিমধ্যে অ্যাকাউন্ট আছে? 
      <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">লগইন করুন</a>
    </p>
  </div>

  <div class="mt-4 text-center">
    <a href="{{ route('home') }}" class="text-xs font-bold text-charcoal/60 hover:text-primary">← হোমে ফিরে যান</a>
  </div>
</div>

</body>
</html>