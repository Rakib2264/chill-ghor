@php
    use App\Support\Cart;
    use App\Support\Wishlist;
    $cartCount = Cart::count();
    $wishCount = Wishlist::count();
    $navLinks = [
        ['route' => 'home', 'label' => 'হোম'],
        ['route' => 'menu.index', 'label' => 'মেনু'],
        ['route' => 'about', 'label' => 'আমাদের সম্পর্কে'],
        ['route' => 'contact', 'label' => 'যোগাযোগ'],
    ];
@endphp

{{-- ════ DESKTOP HEADER ════ --}}
<header
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 8"
    :class="scrolled ? 'shadow-soft border-charcoal/10' : 'border-transparent'"
    class="sticky top-0 z-40 hidden md:block transition-all duration-300 border-b glass">

    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-3 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group">
            <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl shadow-warm overflow-hidden ring-1 ring-white/10 transition-transform duration-300 group-hover:scale-105"
                style="background: linear-gradient(135deg, #c0392b 0%, #e8671a 100%);">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর" class="h-7 w-7 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none; font-size:18px;">☕</span>
            </div>
            <div class="leading-none">
                <div class="font-display text-[19px] font-bold text-charcoal tracking-tight group-hover:text-primary transition-colors duration-200">
                    চিল ঘর
                </div>
                <div class="text-[9px] uppercase tracking-[0.22em] text-charcoal/45 mt-1 font-latin">CHILL · GHOR</div>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <nav class="flex items-center gap-1">
            @foreach ($navLinks as $l)
                <a href="{{ route($l['route']) }}"
                    class="relative rounded-xl px-4 py-2 text-[13px] font-bold transition-all duration-200 hover:-translate-y-0.5
                    {{ request()->routeIs($l['route']) ? 'text-primary' : 'text-charcoal/65 hover:text-charcoal' }}">
                    {{ $l['label'] }}
                    @if (request()->routeIs($l['route']))
                        <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-6 h-[3px] rounded-full"
                              style="background: linear-gradient(90deg,#c0392b,#e8671a);"></span>
                    @endif
                </a>
            @endforeach

            @auth
                @if (auth()->user()->is_admin ?? false)
                    <a href="{{ route('admin.dashboard') }}"
                        class="ml-3 inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-[12px] font-bold text-white transition-all hover:opacity-95 hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #2a1d18, #4a3028);">
                        <i class="fa-solid fa-screwdriver-wrench text-[10px]"></i> অ্যাডমিন
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Right Actions --}}
        <div class="flex items-center gap-1.5">

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}"
                class="group relative flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-200 hover:bg-primary/10"
                aria-label="wishlist">
                <i class="fa-regular fa-heart text-[15px] text-charcoal/70 group-hover:text-primary transition-colors"></i>
                @if ($wishCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-spice px-1 text-[10px] font-black text-charcoal leading-none ring-2 ring-cream">{{ $wishCount }}</span>
                @endif
            </a>

            {{-- Cart --}}
            <a href="{{ route('checkout.index') }}"
                class="group relative flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-200 hover:bg-primary/10"
                aria-label="cart">
                <i class="fa-solid fa-bag-shopping text-[15px] text-charcoal/70 group-hover:text-primary transition-colors"></i>
                <span class="absolute -right-1 -top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-primary px-1 text-[10px] font-black text-white leading-none ring-2 ring-cream transition-all"
                    x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="ml-1 flex h-10 w-10 items-center justify-center rounded-xl overflow-hidden ring-2 ring-transparent hover:ring-primary/30 transition-all duration-200"
                    aria-label="profile">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-9 w-9 rounded-lg object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="ml-1 rounded-xl border border-charcoal/15 bg-white px-4 py-2 text-[12px] font-bold text-charcoal/75 hover:border-primary/40 hover:text-primary transition-all duration-200">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════ MOBILE TOP BAR ════ --}}
<header
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 8"
    :class="scrolled ? 'shadow-soft border-charcoal/10' : 'border-transparent'"
    class="sticky top-0 z-40 md:hidden transition-all duration-300 border-b glass">
    <div class="flex items-center justify-between px-4 py-2.5">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl overflow-hidden shadow-warm"
                style="background: linear-gradient(135deg, #c0392b 0%, #e8671a 100%);">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর" class="h-6 w-6 object-contain"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none; font-size:16px;">☕</span>
            </div>
            <div class="leading-none">
                <div class="font-display text-[17px] font-bold text-charcoal">চিল ঘর</div>
                <div class="text-[8px] uppercase tracking-widest text-charcoal/45 mt-0.5 font-latin">CHILL · GHOR</div>
            </div>
        </a>

        <div class="flex items-center gap-1">
            {{-- Search → Menu --}}
            <a href="{{ route('menu.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-xl text-charcoal/65 hover:bg-primary/10 hover:text-primary transition" aria-label="search">
                <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
            </a>

            <a href="{{ route('checkout.index') }}"
                class="relative flex h-9 w-9 items-center justify-center rounded-xl text-charcoal/65 hover:bg-primary/10 hover:text-primary transition" aria-label="cart">
                <i class="fa-solid fa-bag-shopping text-[14px]"></i>
                <span class="absolute -right-1 -top-1 flex h-[16px] min-w-[16px] items-center justify-center rounded-full bg-primary px-1 text-[9px] font-black text-white leading-none ring-2 ring-cream"
                    x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                    @cart-updated.window="$store.cartCount = $event.detail.count"
                    x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            </a>

            @auth
                <a href="{{ route('profile.index') }}"
                    class="ml-0.5 flex h-9 w-9 items-center justify-center rounded-xl overflow-hidden ring-1 ring-charcoal/15">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                        class="h-8 w-8 rounded-lg object-cover">
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="ml-0.5 rounded-xl border border-charcoal/15 bg-white px-3 py-1.5 text-[11px] font-bold text-charcoal/75 hover:border-primary/40 hover:text-primary transition">
                    লগইন
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ════ MOBILE BOTTOM NAV ════ --}}
<nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden glass"
    style="border-top: 1px solid rgba(42,29,24,0.08); padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-around px-2 py-1.5">

        {{-- হোম --}}
        <a href="{{ route('home') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('home') ? 'text-primary' : 'text-charcoal/50' }}">
            <i class="fa-solid fa-house text-[16px]"></i>
            <span class="text-[9px] font-bold leading-none">হোম</span>
        </a>

        {{-- মেনু --}}
        <a href="{{ route('menu.index') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('menu.*') ? 'text-primary' : 'text-charcoal/50' }}">
            <i class="fa-solid fa-utensils text-[16px]"></i>
            <span class="text-[9px] font-bold leading-none">মেনু</span>
        </a>

        {{-- কার্ট (center) --}}
        <a href="{{ route('checkout.index') }}" class="relative flex flex-col items-center -mt-6">
            <span class="flex items-center justify-center rounded-2xl shadow-glow text-white"
                style="background: linear-gradient(135deg, #c0392b, #e8671a); width: 56px; height: 56px; border: 4px solid #faf6ef;">
                <i class="fa-solid fa-bag-shopping text-[18px]"></i>
            </span>
            <span class="absolute top-0 right-0 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-spice px-1 text-[10px] font-black text-charcoal leading-none ring-2 ring-cream"
                x-data x-show="$store.cartCount > 0 || {{ $cartCount }} > 0"
                @cart-updated.window="$store.cartCount = $event.detail.count"
                x-text="$store.cartCount ?? {{ $cartCount }}">{{ $cartCount }}</span>
            <span class="text-[9px] font-bold text-charcoal/50 mt-1 leading-none">কার্ট</span>
        </a>

        {{-- পছন্দ --}}
        <a href="{{ route('wishlist.index') }}"
            class="relative flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('wishlist.*') ? 'text-primary' : 'text-charcoal/50' }}">
            <i class="fa-{{ request()->routeIs('wishlist.*') ? 'solid' : 'regular' }} fa-heart text-[16px]"></i>
            <span class="text-[9px] font-bold leading-none">পছন্দ</span>
            @if ($wishCount > 0)
                <span class="absolute top-0.5 right-1 flex h-[15px] min-w-[15px] items-center justify-center rounded-full bg-spice px-1 text-[8px] font-black text-charcoal leading-none">{{ $wishCount }}</span>
            @endif
        </a>

        @auth
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-charcoal/50' }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                    class="h-5 w-5 rounded-lg object-cover {{ request()->routeIs('profile.*') ? 'ring-2 ring-primary' : 'ring-1 ring-charcoal/20' }}">
                <span class="text-[9px] font-bold leading-none">প্রোফাইল</span>
            </a>
        @else
            <a href="{{ route('login') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-charcoal/50">
                <i class="fa-regular fa-circle-user text-[16px]"></i>
                <span class="text-[9px] font-bold leading-none">লগইন</span>
            </a>
        @endauth
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('cartCount', {{ $cartCount }});
        window.addEventListener('cart-updated', (e) => {
            Alpine.store('cartCount', e.detail.count ?? 0);
        });
    });
</script>