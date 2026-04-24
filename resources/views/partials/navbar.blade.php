@php
    use App\Support\Cart;
    use App\Support\Wishlist;
    $cartCount = Cart::count();
    $wishCount = Wishlist::count();
    $navLinks = [
        ['route' => 'home',       'label' => 'হোম',           'icon' => '🏠'],
        ['route' => 'menu.index', 'label' => 'মেনু',           'icon' => '🍽️'],
        ['route' => 'about',      'label' => 'আমাদের সম্পর্কে', 'icon' => 'ℹ️'],
        ['route' => 'contact',    'label' => 'যোগাযোগ',        'icon' => '📞'],
    ];
@endphp

{{-- ════════════════════════════════════════════
     DESKTOP / TABLET HEADER  (hidden on mobile)
     ════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40 border-b border-charcoal/10 bg-cream/90 backdrop-blur-md hidden md:block">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর"
                    class="h-8 w-8 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span style="display:none">☕</span>
            </div>
            <div>
                <div class="font-display text-xl font-bold leading-none">চিল ঘর</div>
                <div class="text-[10px] uppercase tracking-widest text-charcoal/50 leading-tight mt-0.5">চা–কফির আড্ডা</div>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden items-center gap-1 md:flex">
            @foreach ($navLinks as $l)
                <a href="{{ route($l['route']) }}"
                    class="rounded-full px-4 py-2 text-sm font-medium transition
                    {{ request()->routeIs($l['route']) ? 'bg-primary text-white shadow-warm' : 'text-charcoal hover:bg-charcoal/5' }}">
                    {{ $l['label'] }}
                </a>
            @endforeach

            @auth
                @if (auth()->user()->is_admin ?? false)
                    <a href="{{ route('admin.dashboard') }}"
                        class="ml-2 rounded-full bg-charcoal px-4 py-2 text-xs font-bold text-cream hover:bg-charcoal/90 transition">
                        ⚙️ অ্যাডমিন
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Right icons --}}
        <div class="flex items-center gap-2">

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}"
                class="relative flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 hover:bg-charcoal/10 transition"
                aria-label="wishlist">
                <span class="text-[17px]">❤️</span>
                @if ($wishCount > 0)
                    <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-spice px-1 text-[10px] font-bold text-charcoal leading-none">
                        {{ $wishCount }}
                    </span>
                @endif
            </a>

            {{-- Cart --}}
            <a href="{{ route('checkout.index') }}"
                class="relative flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 hover:bg-charcoal/10 transition"
                aria-label="cart">
                <span class="text-[17px]">🛒</span>
                <span
                    class="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-primary px-1 text-[10px] font-bold text-white leading-none transition-all"
                    x-data
                    x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="relative flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 hover:bg-charcoal/10 transition"
                    aria-label="profile">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-7 w-7 rounded-full object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="rounded-full border border-charcoal/15 bg-white px-4 py-2 text-xs font-bold hover:border-primary hover:text-primary transition">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════════════════════════════════════════════
     MOBILE TOP BAR  (only on mobile)
     ════════════════════════════════════════════ --}}
<header class="sticky top-0 z-40 border-b border-charcoal/10 bg-cream/90 backdrop-blur-md md:hidden">
    <div class="flex items-center justify-between px-4 py-3">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-warm shadow-warm">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর"
                    class="h-7 w-7 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span style="display:none">☕</span>
            </div>
            <div>
                <div class="font-display text-lg font-bold leading-none">চিল ঘর</div>
                <div class="text-[9px] uppercase tracking-widest text-charcoal/50 leading-tight">চা–কফির আড্ডা</div>
            </div>
        </a>

        {{-- Mobile top-right: cart + profile/login only --}}
        <div class="flex items-center gap-2">

            {{-- Cart --}}
            <a href="{{ route('checkout.index') }}"
                class="relative flex h-9 w-9 items-center justify-center rounded-full bg-charcoal/5"
                aria-label="cart">
                <span class="text-[16px]">🛒</span>
                <span
                    class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-primary px-1 text-[9px] font-bold text-white leading-none"
                    x-data
                    x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-charcoal/5"
                    aria-label="profile">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-6 w-6 rounded-full object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="rounded-full border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary transition">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════════════════════════════════════════════
     MOBILE BOTTOM NAVIGATION BAR
     ════════════════════════════════════════════ --}}
<nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden
            bg-cream/95 backdrop-blur-md border-t border-charcoal/10
            safe-area-pb"
     style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-around px-2 py-1">

        {{-- হোম --}}
        <a href="{{ route('home') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition
            {{ request()->routeIs('home') ? 'text-primary' : 'text-charcoal/50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] font-semibold leading-none">হোম</span>
            @if(request()->routeIs('home'))
                <span class="block h-1 w-1 rounded-full bg-primary mt-0.5"></span>
            @endif
        </a>

        {{-- মেনু --}}
        <a href="{{ route('menu.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition
            {{ request()->routeIs('menu.*') ? 'text-primary' : 'text-charcoal/50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-[10px] font-semibold leading-none">মেনু</span>
            @if(request()->routeIs('menu.*'))
                <span class="block h-1 w-1 rounded-full bg-primary mt-0.5"></span>
            @endif
        </a>

        {{-- কার্ট (centre, highlighted) --}}
        <a href="{{ route('checkout.index') }}"
            class="relative flex flex-col items-center -mt-4">
            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-primary shadow-warm text-white text-2xl">
                🛒
            </span>
            {{-- badge --}}
            <span
                class="absolute top-0 right-0 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-spice px-1 text-[10px] font-bold text-charcoal leading-none"
                x-data
                x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                @cart-updated.window="$store.cartCount = $event.detail.count"
                x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            <span class="text-[10px] font-semibold text-charcoal/50 mt-1 leading-none">কার্ট</span>
        </a>

        {{-- পছন্দ (Wishlist) --}}
        <a href="{{ route('wishlist.index') }}"
            class="relative flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition
            {{ request()->routeIs('wishlist.*') ? 'text-primary' : 'text-charcoal/50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ request()->routeIs('wishlist.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span class="text-[10px] font-semibold leading-none">পছন্দ</span>
            @if ($wishCount > 0)
                <span class="absolute top-1 right-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-spice px-1 text-[9px] font-bold text-charcoal leading-none">
                    {{ $wishCount }}
                </span>
            @endif
            @if(request()->routeIs('wishlist.*'))
                <span class="block h-1 w-1 rounded-full bg-primary mt-0.5"></span>
            @endif
        </a>

        {{-- প্রোফাইল --}}
        @auth
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition
                {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-charcoal/50' }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                    class="h-6 w-6 rounded-full object-cover ring-2 {{ request()->routeIs('profile.*') ? 'ring-primary' : 'ring-charcoal/20' }}">
                <span class="text-[10px] font-semibold leading-none">প্রোফাইল</span>
                @if(request()->routeIs('profile.*'))
                    <span class="block h-1 w-1 rounded-full bg-primary mt-0.5"></span>
                @endif
            </a>
        @else
            <a href="{{ route('login') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition text-charcoal/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-semibold leading-none">প্রোফাইল</span>
            </a>
        @endauth

    </div>
</nav>

{{-- Bottom nav height spacer — prevents content from hiding behind the bar --}}
<div class="md:hidden"></div>

{{-- Alpine global cart count store --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('cartCount', {{ $cartCount }});
        window.addEventListener('cart-updated', (e) => {
            Alpine.store('cartCount', e.detail.count ?? 0);
        });
    });
</script>