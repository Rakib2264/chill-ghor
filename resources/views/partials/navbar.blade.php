@php
    use App\Support\Cart;
    use App\Support\Wishlist;
    $cartCount = Cart::count();
    $wishCount = Wishlist::count();
    $navLinks = [
        ['route' => 'home', 'label' => 'হোম', 'icon' => 'home'],
        ['route' => 'menu.index', 'label' => 'মেনু', 'icon' => 'menu'],
        ['route' => 'about', 'label' => 'আমাদের সম্পর্কে', 'icon' => 'info'],
        ['route' => 'contact', 'label' => 'যোগাযোগ', 'icon' => 'contact'],
    ];
@endphp

{{-- ════ DESKTOP HEADER ════ --}}
<header class="sticky top-0 z-40 hidden md:block"
    style="background: rgba(250,246,239,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(192,57,43,0.08);">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-3 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group">
            <div class="relative flex h-10 w-10 items-center justify-center rounded-2xl shadow-warm overflow-hidden"
                style="background: linear-gradient(135deg, #c0392b 0%, #e8671a 100%);">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর" class="h-7 w-7 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none; font-size:18px;">☕</span>
            </div>
            <div class="leading-none">
                <div
                    class="font-display text-[18px] font-bold text-charcoal tracking-tight group-hover:text-primary transition-colors duration-200">
                    চিল ঘর</div>
                <div class="text-[9px] uppercase tracking-[0.18em] text-charcoal/40 mt-0.5">চা–কফির আড্ডা</div>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <nav class="flex items-center gap-1">
            @foreach ($navLinks as $l)
                <a href="{{ route($l['route']) }}"
                    class="relative rounded-xl px-4 py-2 text-[13px] font-semibold transition-all duration-200
                    {{ request()->routeIs($l['route']) ? 'text-primary bg-primary/8' : 'text-charcoal/65 hover:text-charcoal hover:bg-charcoal/5' }}">
                    {{ $l['label'] }}
                    @if (request()->routeIs($l['route']))
                        <span
                            class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary"></span>
                    @endif
                </a>
            @endforeach

            @auth
                @if (auth()->user()->is_admin ?? false)
                    <a href="{{ route('admin.dashboard') }}"
                        class="ml-3 rounded-xl px-4 py-2 text-[12px] font-bold text-white transition-all hover:opacity-90"
                        style="background: linear-gradient(135deg, #2a1d18, #4a3028);">
                        ⚙️ অ্যাডমিন
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Right Actions --}}
        <div class="flex items-center gap-2">

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}"
                class="group relative flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-200 hover:bg-primary/8"
                aria-label="wishlist">
                <svg class="w-[18px] h-[18px] text-charcoal/60 group-hover:text-primary transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                @if ($wishCount > 0)
                    <span
                        class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-spice px-1 text-[9px] font-bold text-charcoal leading-none">{{ $wishCount }}</span>
                @endif
            </a>

            {{-- Cart --}}
            <a href="{{ route('checkout.index') }}"
                class="group relative flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-200 hover:bg-primary/8"
                aria-label="cart">
                <svg class="w-[18px] h-[18px] text-charcoal/60 group-hover:text-primary transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-primary px-1 text-[9px] font-bold text-white leading-none transition-all"
                    x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-xl overflow-hidden ring-2 ring-transparent hover:ring-primary/30 transition-all duration-200"
                    aria-label="profile">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="rounded-xl border border-charcoal/15 bg-white px-4 py-2 text-[12px] font-bold text-charcoal/70 hover:border-primary/40 hover:text-primary transition-all duration-200">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════ MOBILE TOP BAR ════ --}}
<header class="sticky top-0 z-40 md:hidden"
    style="background: rgba(250,246,239,0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(192,57,43,0.08);">
    <div class="flex items-center justify-between px-4 py-2.5">

        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl overflow-hidden"
                style="background: linear-gradient(135deg, #c0392b 0%, #e8671a 100%);">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর" class="h-6 w-6 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none; font-size:16px;">☕</span>
            </div>
            <div class="leading-none">
                <div class="font-display text-[17px] font-bold text-charcoal">চিল ঘর</div>
                <div class="text-[8px] uppercase tracking-widest text-charcoal/40">চা–কফির আড্ডা</div>
            </div>
        </a>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('checkout.index') }}"
                class="relative flex h-9 w-9 items-center justify-center rounded-xl" aria-label="cart">
                <svg class="w-5 h-5 text-charcoal/65" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-primary px-1 text-[9px] font-bold text-white leading-none"
                    x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-xl overflow-hidden ring-1 ring-charcoal/15">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="rounded-xl border border-charcoal/15 bg-white px-3 py-1.5 text-[11px] font-bold text-charcoal/70">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════ MOBILE BOTTOM NAV ════ --}}
<nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden"
    style="background: rgba(250,246,239,0.97); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-top: 1px solid rgba(42,29,24,0.08); padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-around px-2 py-1.5">

        {{-- হোম --}}
        <a href="{{ route('home') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('home') ? 'text-primary' : 'text-charcoal/45' }}">
            <svg class="h-5 w-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[9px] font-bold leading-none">হোম</span>
        </a>

        {{-- মেনু --}}
        <a href="{{ route('menu.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('menu.*') ? 'text-primary' : 'text-charcoal/45' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="text-[9px] font-bold leading-none">মেনু</span>
        </a>

        {{-- কার্ট (center) --}}
        <a href="{{ route('checkout.index') }}" class="relative flex flex-col items-center -mt-5">
            <span class="flex h-13 w-13 items-center justify-center rounded-2xl shadow-warm text-white text-xl"
                style="background: linear-gradient(135deg, #c0392b, #e8671a); width: 52px; height: 52px; border: 3px solid #f7f2eb;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </span>
            <span
                class="absolute -top-0.5 -right-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-spice px-1 text-[9px] font-bold text-charcoal leading-none"
                x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                @cart-updated.window="$store.cartCount = $event.detail.count"
                x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            <span class="text-[9px] font-bold text-charcoal/45 mt-1.5 leading-none">কার্ট</span>
        </a>

        {{-- পছন্দ --}}
        <a href="{{ route('wishlist.index') }}"
            class="relative flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('wishlist.*') ? 'text-primary' : 'text-charcoal/45' }}">
            <svg class="h-5 w-5" fill="{{ request()->routeIs('wishlist.*') ? 'currentColor' : 'none' }}"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-[9px] font-bold leading-none">পছন্দ</span>
            @if ($wishCount > 0)
                <span
                    class="absolute top-1 right-0.5 flex h-3.5 min-w-[14px] items-center justify-center rounded-full bg-spice px-0.5 text-[8px] font-bold text-charcoal leading-none">{{ $wishCount }}</span>
            @endif
        </a>

        @auth
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-charcoal/45' }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                    class="h-5 w-5 rounded-lg object-cover {{ request()->routeIs('profile.*') ? 'ring-2 ring-primary' : 'ring-1 ring-charcoal/20' }}">
                <span class="text-[9px] font-bold leading-none">প্রোফাইল</span>
            </a>
        @else
            <div class="flex items-center gap-2">
                {{-- Login --}}
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center gap-0.5 px-2 py-1.5 rounded-xl text-charcoal/45">
                    <span class="text-[9px] font-bold leading-none">লগইন</span>
                </a>

                {{-- Register --}}
                <a href="{{ route('register') }}"
                    class="flex flex-col items-center gap-0.5 px-2 py-1.5 rounded-xl text-primary">
                    <span class="text-[9px] font-bold leading-none">রেজিস্টার</span>
                </a>
            </div>
        @endauth
    </div>
</nav>

<div class="md:hidden"></div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('cartCount', {{ $cartCount }});
        window.addEventListener('cart-updated', (e) => {
            Alpine.store('cartCount', e.detail.count ?? 0);
        });
    });
</script>
