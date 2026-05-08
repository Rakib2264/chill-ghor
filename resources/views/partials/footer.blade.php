@php
    use App\Models\Setting;

    $siteName = Setting::get('site_name', 'চিল ঘর');
    $logo = Setting::get('logo', 'images/logo/logo.png');
    $footerDesc = Setting::get(
        'footer_description',
        'ঘরের স্বাদ, রেস্টুরেন্টে। বনগ্রাম স্কুল ও কলেজের সামনে — চা থেকে কাচ্চি, ফুচকা থেকে চিকেন বার্গার। গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।',
    );
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

    $socialLinks = [];
    if ($socialFacebook && $socialFacebook != '#') $socialLinks['facebook'] = $socialFacebook;
    if ($socialInstagram && $socialInstagram != '#') $socialLinks['instagram'] = $socialInstagram;
    if ($socialTwitter && $socialTwitter != '#') $socialLinks['twitter'] = $socialTwitter;
    if ($socialYoutube && $socialYoutube != '#') $socialLinks['youtube'] = $socialYoutube;
    if (empty($socialLinks)) {
        $socialLinks = ['facebook' => '#', 'instagram' => '#', 'twitter' => '#'];
    }
@endphp

<footer class="relative mt-16 overflow-hidden text-cream"
        style="background: linear-gradient(160deg, #1c0f09 0%, #2a1d18 60%, #3d2010 100%);">

    {{-- decorative orbs --}}
    <div class="pointer-events-none absolute -top-24 -right-20 h-72 w-72 rounded-full opacity-30 blur-3xl"
         style="background: radial-gradient(circle, #e8671a, transparent 60%);"></div>
    <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full opacity-25 blur-3xl"
         style="background: radial-gradient(circle, #f0a020, transparent 60%);"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-12 lg:px-8">

        {{-- Brand --}}
        <div class="md:col-span-5">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl shadow-warm overflow-hidden ring-1 ring-white/10"
                     style="background: linear-gradient(135deg,#c0392b,#e8671a);">
                    @if ($logo && ($logo != 'images/logo/logo.png' || file_exists(public_path($logo))))
                        <img src="{{ asset($logo) }}" alt="{{ $siteName }}" class="h-8 w-8 object-contain"
                            onerror="this.style.display='none';this.parentElement.innerHTML='☕'">
                    @else
                        ☕
                    @endif
                </div>
                <div>
                    <div class="font-display text-2xl font-bold">{{ $siteName }}</div>
                    <div class="text-[10px] uppercase tracking-[0.22em] text-cream/55 mt-1 font-latin">CHILL · GHOR</div>
                </div>
            </div>
            <p class="mt-5 max-w-md text-sm leading-relaxed text-cream/65">
                {{ $footerDesc }}
            </p>

            {{-- Social --}}
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($socialLinks as $key => $href)
                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
                       class="group flex h-10 w-10 items-center justify-center rounded-xl bg-white/8 ring-1 ring-white/10 transition hover:bg-primary hover:ring-primary hover:-translate-y-0.5 hover:shadow-warm">
                        @if ($key == 'facebook')   <i class="fab fa-facebook-f text-cream/85 group-hover:text-white"></i>
                        {{-- @elseif($key == 'instagram') <i class="fab fa-instagram text-cream/85 group-hover:text-white"></i>
                        @elseif($key == 'twitter')  <i class="fab fa-x-twitter text-cream/85 group-hover:text-white"></i>
                        @elseif($key == 'youtube')  <i class="fab fa-youtube text-cream/85 group-hover:text-white"></i> --}}
                        @endif
                    </a>
                @endforeach
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-spice px-3 text-[11px] font-black text-charcoal hover:opacity-90 transition">
                    <i class="fa-regular fa-paper-plane"></i> বার্তা পাঠান
                </a>
            </div>
        </div>

        {{-- Address / Contact --}}
        <div class="md:col-span-4" x-data="{ open: true }">
            <h4 class="font-display text-sm font-black uppercase tracking-[0.18em] text-spice/90 mb-4 flex items-center justify-between md:cursor-default cursor-pointer"
                @click="open = !open">
                <span><i class="fa-regular fa-compass mr-2 text-spice"></i> ঠিকানা ও যোগাযোগ</span>
                <i class="fa-solid fa-chevron-down md:hidden text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </h4>
            <ul class="space-y-3 text-sm text-cream/70" x-show="open" x-collapse>
                <li class="flex gap-3 items-start">
                    <span class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-spice flex-shrink-0"><i class="fa-solid fa-location-dot text-[12px]"></i></span>
                    <span>{{ $contactAddress }}</span>
                </li>
                <li class="flex gap-3 items-center">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-spice flex-shrink-0"><i class="fa-solid fa-phone text-[12px]"></i></span>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="hover:text-spice transition">{{ $contactPhone }}</a>
                </li>
                <li class="flex gap-3 items-center">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-spice flex-shrink-0"><i class="fa-regular fa-envelope text-[12px]"></i></span>
                    <a href="mailto:{{ $contactEmail }}" class="hover:text-spice transition break-all">{{ $contactEmail }}</a>
                </li>
                <li class="flex gap-3 items-center">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-spice flex-shrink-0"><i class="fa-regular fa-clock text-[12px]"></i></span>
                    <span>{{ $openingHours }}</span>
                </li>
            </ul>
        </div>

        {{-- Quick Links --}}
        <div class="md:col-span-3" x-data="{ open: true }">
            <h4 class="font-display text-sm font-black uppercase tracking-[0.18em] text-spice/90 mb-4 flex items-center justify-between md:cursor-default cursor-pointer"
                @click="open = !open">
                <span><i class="fa-regular fa-bookmark mr-2 text-spice"></i> কুইক লিংক</span>
                <i class="fa-solid fa-chevron-down md:hidden text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </h4>
            <ul class="space-y-2.5 text-sm text-cream/70" x-show="open" x-collapse>
                <li><a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 hover:text-spice hover:translate-x-1 transition"><i class="fa-solid fa-utensils text-[11px]"></i> মেনু</a></li>
                <li><a href="{{ route('about') }}" class="inline-flex items-center gap-2 hover:text-spice hover:translate-x-1 transition"><i class="fa-regular fa-circle-question text-[11px]"></i> আমাদের সম্পর্কে</a></li>
                <li><a href="{{ route('contact') }}" class="inline-flex items-center gap-2 hover:text-spice hover:translate-x-1 transition"><i class="fa-regular fa-comment text-[11px]"></i> যোগাযোগ</a></li>
                <li><a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 hover:text-spice hover:translate-x-1 transition"><i class="fa-solid fa-bag-shopping text-[11px]"></i> কার্ট</a></li>
                <li><a href="{{ route('order.track.form') }}" class="inline-flex items-center gap-2 hover:text-spice hover:translate-x-1 transition"><i class="fa-solid fa-truck-fast text-[11px]"></i> অর্ডার ট্র্যাক</a></li>
            </ul>
        </div>

    </div>

    <div class="relative border-t border-white/8">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-xs text-cream/50 sm:flex-row sm:px-6 lg:px-8">
            <div>© {{ date('Y') }} {{ $footerCopyright }}</div>
            <div class="flex items-center gap-4">
                <span class="hidden sm:inline">তৈরি <span class="text-red-400">❤️</span> দিয়ে</span>
                <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
                    class="inline-flex items-center gap-1.5 rounded-full bg-white/8 px-3 py-1.5 text-[11px] font-bold text-cream/80 hover:bg-spice hover:text-charcoal transition">
                    উপরে যান <i class="fa-solid fa-arrow-up text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>
</footer>