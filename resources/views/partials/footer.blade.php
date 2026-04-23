@php
    use App\Models\Setting;
    
    // Get all settings dynamically with fallback values
    $siteName = Setting::get('site_name', 'চিল ঘর');
    $logo = Setting::get('logo', 'images/logo/logo.png');
    $footerDesc = Setting::get('footer_description', 'ঘরের স্বাদ, রেস্টুরেন্টে। বনগ্রাম স্কুল ও কলেজের সামনে — চা থেকে কাচ্চি, ফুচকা থেকে চিকেন বার্গার। গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।');
    $footerCopyright = Setting::get('footer_copyright', 'চিল ঘর রেস্টুরেন্ট। সর্বস্বত্ব সংরক্ষিত।');
    
    // Contact info
    $contactPhone = Setting::get('contact_phone', Setting::get('phone', '+৮৮০ ১৭১১-০০০০০০'));
    $contactEmail = Setting::get('contact_email', Setting::get('email', 'hello@chillghor.com'));
    $contactAddress = Setting::get('contact_address', Setting::get('address', 'বনগ্রাম স্কুল ও কলেজের সামনে'));
    $openingHours = Setting::get('opening_hours', 'সকাল ৭টা – রাত ১১টা');
    
    // Social links
    $socialFacebook = Setting::get('social_facebook', Setting::get('facebook', '#'));
    $socialInstagram = Setting::get('social_instagram', Setting::get('instagram', '#'));
    $socialTwitter = Setting::get('social_twitter', '#');
    $socialYoutube = Setting::get('social_youtube', '#');
    
    // Collect active social links
    $socialLinks = [];
    if($socialFacebook && $socialFacebook != '#') $socialLinks['📘'] = $socialFacebook;
    if($socialInstagram && $socialInstagram != '#') $socialLinks['📷'] = $socialInstagram;
    if($socialTwitter && $socialTwitter != '#') $socialLinks['🐦'] = $socialTwitter;
    if($socialYoutube && $socialYoutube != '#') $socialLinks['📺'] = $socialYoutube;
    
    // If no social links, show default empty or hide
    if(empty($socialLinks)) {
        $socialLinks = ['📘' => '#', '📷' => '#', '🐦' => '#'];
    }
@endphp

<footer class="mt-20 border-t border-charcoal/20 bg-charcoal text-cream">
  <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">

    {{-- Brand --}}
    <div class="md:col-span-2">
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm">
          @if($logo && ($logo != 'images/logo/logo.png' || file_exists(public_path($logo))))
            <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-8 w-8 object-contain"
                 onerror="this.style.display='none';this.parentElement.innerHTML='☕'">
          @else
            ☕
          @endif
        </div>
        <div>
          <div class="font-display text-xl font-bold">{{ $siteName }}</div>
          <div class="text-[10px] uppercase tracking-widest text-cream/50 mt-0.5">চা–কফির আড্ডা</div>
        </div>
      </div>
      <p class="mt-4 max-w-sm text-sm leading-relaxed text-cream/65">
        {{ $footerDesc }}
      </p>
      <div class="mt-5 flex gap-2">
        @foreach ($socialLinks as $icon => $href)
          <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
             class="flex h-9 w-9 items-center justify-center rounded-full bg-cream/10 text-sm transition hover:bg-primary hover:scale-110">
            {{ $icon }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- Address / Contact --}}
    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider text-cream/60 mb-4">ঠিকানা</h4>
      <ul class="space-y-3 text-sm text-cream/65">
        <li class="flex gap-2.5 items-start"><span>📍</span> {{ $contactAddress }}</li>
        <li class="flex gap-2.5 items-center">
          <span>📞</span> 
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="hover:text-spice transition">{{ $contactPhone }}</a>
        </li>
        <li class="flex gap-2.5 items-center">
          <span>✉️</span> 
          <a href="mailto:{{ $contactEmail }}" class="hover:text-spice transition">{{ $contactEmail }}</a>
        </li>
        <li class="flex gap-2.5 items-center"><span>🕐</span> {{ $openingHours }}</li>
      </ul>
    </div>

    {{-- Quick Links --}}
    <div>
      <h4 class="font-display text-sm font-bold uppercase tracking-wider text-cream/60 mb-4">কুইক লিংক</h4>
      <ul class="space-y-2.5 text-sm text-cream/65">
        <li><a href="{{ route('menu.index') }}" class="hover:text-spice transition">🍽️ মেনু</a></li>
        <li><a href="{{ route('about') }}" class="hover:text-spice transition">📖 আমাদের সম্পর্কে</a></li>
        <li><a href="{{ route('contact') }}" class="hover:text-spice transition">📞 যোগাযোগ</a></li>
        <li><a href="{{ route('cart.index') }}" class="hover:text-spice transition">🛒 কার্ট</a></li>
      </ul>
    </div>
  </div>

  <div class="border-t border-cream/10 py-5 text-center text-xs text-cream/40">
    © {{ date('Y') }} {{ $footerCopyright }}
  </div>
</footer>