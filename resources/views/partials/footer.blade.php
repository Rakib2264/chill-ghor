<footer class="mt-24 border-t border-charcoal/20 bg-charcoal text-cream">
  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
    <div class="md:col-span-2">
      <div class="flex items-center gap-2.5">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm">🍛</div>
        <div>
          <div class="font-display text-xl font-bold">চিল ঘর</div>
          <div class="text-[10px] uppercase tracking-widest text-cream/60">Authentic Bangladeshi</div>
        </div>
      </div>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-cream/70">
        ঘরের স্বাদ, রেস্টুরেন্টে। ৩০+ বছরের ঐতিহ্যবাহী বাঙালি রান্নার স্বাদ — ফ্রেশ উপকরণ, খাঁটি মসলা, আর মা-নানির হাতের রেসিপি।
      </p>
      <div class="mt-5 flex gap-2">
        @foreach (['📘', '📷', '✈️'] as $icon)
          <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-cream/10 transition hover:bg-primary" aria-label="social">{{ $icon }}</a>
        @endforeach
      </div>
    </div>

    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider">ঠিকানা</h4>
      <div class="mt-4 space-y-3 text-sm text-cream/70">
        <div class="flex gap-2.5">📍 <span>হাউস ১২, রোড ৭, ধানমন্ডি, ঢাকা ১২০৫</span></div>
        <div class="flex gap-2.5">📞 <a href="tel:+8801711000000">+৮৮০ ১৭১১-০০০০০০</a></div>
      </div>
    </div>

    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider">কুইক লিংক</h4>
      <ul class="mt-4 space-y-2.5 text-sm text-cream/70">
        <li><a href="{{ route('menu.index') }}" class="hover:text-spice">মেনু</a></li>
        <li><a href="{{ route('about') }}" class="hover:text-spice">আমাদের গল্প</a></li>
        <li><a href="{{ route('contact') }}" class="hover:text-spice">যোগাযোগ</a></li>
        <li><a href="{{ route('cart.index') }}" class="hover:text-spice">কার্ট</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-cream/10 py-5 text-center text-xs text-cream/50">
    © {{ date('Y') }} চিল ঘর রেস্টুরেন্ট। সর্বস্বত্ব সংরক্ষিত।
  </div>
</footer>
