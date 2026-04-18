@php
  use App\Support\Cart;
  use App\Support\Wishlist;
  $cartCount = Cart::count();
  $wishCount = Wishlist::count();
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-charcoal/10 bg-cream/90 backdrop-blur">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">

    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
      <div class="flex h-11 w-11 items-center justify-center rounded-xl text-xl shadow-warm">
        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt="">
      </div>
      <div>
        <div class="font-display text-xl font-bold leading-none">চিল ঘর</div>
        <div class="text-[10px] uppercase tracking-widest text-charcoal/60">Authentic Bangladeshi</div>
      </div>
    </a>

    <nav class="hidden items-center gap-1 md:flex">
      @php
        $links = [
          ['route' => 'home',        'label' => 'হোম'],
          ['route' => 'menu.index',  'label' => 'মেনু'],
          ['route' => 'about',       'label' => 'আমাদের গল্প'],
          ['route' => 'contact',     'label' => 'যোগাযোগ'],
        ];
      @endphp
      @foreach ($links as $l)
        <a href="{{ route($l['route']) }}"
           class="rounded-full px-4 py-2 text-sm font-medium transition
                  {{ request()->routeIs($l['route']) ? 'bg-primary text-white shadow-warm' : 'text-charcoal hover:bg-charcoal/5' }}">
          {{ $l['label'] }}
        </a>
      @endforeach
    </nav>

    <div class="flex items-center gap-2">
      @auth
        @if (auth()->user()->is_admin ?? false)
          <a href="{{ route('admin.dashboard') }}" class="hidden rounded-full bg-charcoal px-4 py-2 text-xs font-bold text-cream hover:bg-charcoal/90 md:inline-flex" title="অ্যাডমিন">⚙️ Admin</a>
        @endif
      @endauth
      <a href="{{ route('wishlist.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 hover:bg-charcoal/10" aria-label="wishlist">
        <span class="text-lg">❤️</span>
        @if ($wishCount > 0)
          <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-spice px-1 text-[10px] font-bold text-charcoal">{{ $wishCount }}</span>
        @endif
      </a>
      <a href="{{ route('cart.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 hover:bg-charcoal/10" aria-label="cart">
        <span class="text-lg">🛒</span>
        @if ($cartCount > 0)
          <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1 text-[10px] font-bold text-white">{{ $cartCount }}</span>
        @endif
      </a>
      <button @click="open = !open" class="flex h-10 w-10 items-center justify-center rounded-full bg-charcoal/5 md:hidden" aria-label="menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>

  <div x-show="open" x-transition class="border-t border-charcoal/10 bg-cream md:hidden" x-cloak>
    <nav class="mx-auto grid max-w-7xl gap-1 px-4 py-3 sm:px-6">
      @foreach ($links as $l)
        <a href="{{ route($l['route']) }}" class="rounded-lg px-4 py-2.5 text-sm font-medium {{ request()->routeIs($l['route']) ? 'bg-primary text-white' : 'hover:bg-charcoal/5' }}">{{ $l['label'] }}</a>
      @endforeach
    </nav>
  </div>
</header>
