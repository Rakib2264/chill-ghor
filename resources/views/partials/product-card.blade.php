@php
  use App\Support\Wishlist;
  $inWish = Wishlist::has($product->id);
@endphp

<div class="group relative flex flex-col overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-warm">

  {{-- Image --}}
  <a href="{{ route('menu.show', $product) }}" class="relative block aspect-square overflow-hidden bg-charcoal/5">
    @if ($product->image)
      <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
           class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
    @else
      <div class="flex h-full w-full items-center justify-center text-5xl bg-gradient-to-br from-cream to-spice/10">
        🍽️
      </div>
    @endif

    @if ($product->popular)
      <span class="absolute left-3 top-3 rounded-full bg-spice px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-charcoal shadow-sm">
        জনপ্রিয়
      </span>
    @endif

    @if ($product->spicy)
      <span class="absolute bottom-3 left-3 flex items-center gap-1 rounded-full bg-primary/90 px-2.5 py-1 text-[10px] font-semibold text-white backdrop-blur-sm">
        🌶️ ঝাল
      </span>
    @endif
  </a>

  {{-- Wishlist button --}}
  <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
        class="absolute right-3 top-3 z-10">
    @csrf
    <button type="submit"
            class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow-soft backdrop-blur-sm transition hover:scale-110"
            aria-label="wishlist">
      <span class="text-base">{{ $inWish ? '❤️' : '🤍' }}</span>
    </button>
  </form>

  {{-- Body --}}
  <div class="flex flex-1 flex-col gap-2 p-4">
    <a href="{{ route('menu.show', $product) }}">
      <h3 class="font-display text-base font-bold leading-snug hover:text-primary transition-colors">
        {{ $product->name }}
      </h3>
    </a>
    <p class="line-clamp-2 text-xs leading-relaxed text-charcoal/60">
      {{ $product->description }}
    </p>

    <div class="mt-auto flex items-end justify-between pt-2">
      <div>
        <div class="text-lg font-bold text-primary">৳{{ number_format($product->price) }}</div>
        @if ($product->old_price)
          <div class="text-xs text-charcoal/40 line-through">৳{{ number_format($product->old_price) }}</div>
        @endif
      </div>

      <form action="{{ route('cart.add', $product) }}" method="POST">
        @csrf
        <button type="submit"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-warm text-white shadow-warm transition hover:scale-110 active:scale-95"
                aria-label="কার্টে যোগ করুন">
          <span class="text-xl font-light leading-none">+</span>
        </button>
      </form>
    </div>
  </div>
</div>