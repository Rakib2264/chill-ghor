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

<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-charcoal/10 bg-cream/90 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm">
                <img src="{{ asset('images/logo/logo.png') }}" alt="চিল ঘর" class="h-8 w-8 object-contain"
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
                    <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-spice px-1 text-[10px] font-bold text-charcoal leading-none"
                          x-text="$store.cart?.count || {{ $wishCount }}">{{ $wishCount }}</span>
                @endif
            </a>

            {{-- Cart → সরাসরি Checkout-এ যাবে --}}
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

            {{-- Mobile hamburger --}}
            <button @click="open = !open"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 md:hidden"
                aria-label="toggle menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition class="border-t border-charcoal/10 bg-cream md:hidden" x-cloak>
        <nav class="mx-auto grid max-w-7xl gap-1 px-4 py-3 sm:px-6">
            @foreach ($navLinks as $l)
                <a href="{{ route($l['route']) }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold
                    {{ request()->routeIs($l['route']) ? 'bg-primary text-white' : 'text-charcoal hover:bg-charcoal/5' }}">
                    {{ $l['label'] }}
                </a>
            @endforeach
            @auth
                @if (auth()->user()->is_admin ?? false)
                    <a href="{{ route('admin.dashboard') }}"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold bg-charcoal text-cream">
                        ⚙️ অ্যাডমিন প্যানেল
                    </a>
                @endif
            @endauth
        </nav>
    </div>
</header>

{{-- Alpine global cart count store --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('cartCount', {{ $cartCount }});
        window.addEventListener('cart-updated', (e) => {
            Alpine.store('cartCount', e.detail.count ?? 0);
        });
    });
</script>