<footer class="mt-20 border-t border-charcoal/20 bg-charcoal text-cream">
  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">

    {{-- Brand --}}
    <div class="md:col-span-2">
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm">☕</div>
        <div>
          <div class="font-display text-xl font-bold">চিল ঘর</div>
          <div class="text-[10px] uppercase tracking-widest text-cream/50 mt-0.5">চা–কফির আড্ডা</div>
        </div>
      </div>
      <p class="mt-4 max-w-sm text-sm leading-relaxed text-cream/65">
        ঘরের স্বাদ, রেস্টুরেন্টে। বনগ্রাম স্কুল ও কলেজের সামনে — চা থেকে কাচ্চি, ফুচকা থেকে চিকেন বার্গার। গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।
      </p>
      <div class="mt-5 flex gap-2">
        @foreach (['📘' => '#', '📷' => '#', '✈️' => '#'] as $icon => $href)
          <a href="{{ $href }}"
             class="flex h-9 w-9 items-center justify-center rounded-full bg-cream/10 text-sm transition hover:bg-primary">
            {{ $icon }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- Address --}}
    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider text-cream/60 mb-4">ঠিকানা</h4>
      <ul class="space-y-3 text-sm text-cream/65">
        <li class="flex gap-2.5 items-start"><span>📍</span> বনগ্রাম স্কুল ও কলেজের সামনে</li>
        <li class="flex gap-2.5 items-center"><span>📞</span> <a href="tel:+8801711000000" class="hover:text-spice transition">+৮৮০ ১৭১১-০০০০০০</a></li>
        <li class="flex gap-2.5 items-center"><span>✉️</span> <a href="mailto:hello@chillghar.com" class="hover:text-spice transition">hello@chillghar.com</a></li>
        <li class="flex gap-2.5 items-center"><span>🕐</span> সকাল ৭টা – রাত ১১টা</li>
      </ul>
    </div>

    {{-- Quick Links --}}
    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider text-cream/60 mb-4">কুইক লিংক</h4>
      <ul class="space-y-2.5 text-sm text-cream/65">
        <li><a href="{{ route('menu.index') }}" class="hover:text-spice transition">মেনু</a></li>
        <li><a href="{{ route('about') }}" class="hover:text-spice transition">আমাদের সম্পর্কে</a></li>
        <li><a href="{{ route('contact') }}" class="hover:text-spice transition">যোগাযোগ</a></li>
        <li><a href="{{ route('cart.index') }}" class="hover:text-spice transition">কার্ট</a></li>
        <li><a href="{{ route('wishlist.index') }}" class="hover:text-spice transition">উইশলিস্ট</a></li>
      </ul>
    </div>
  </div>

  <div class="border-t border-cream/10 py-5 text-center text-xs text-cream/40">
    © {{ date('Y') }} চিল ঘর রেস্টুরেন্ট। সর্বস্বত্ব সংরক্ষিত।
  </div>
</footer>