{{--
  partials/product-card.blade.php
  Variables: $product (Product model)
--}}
<div class="group relative overflow-hidden rounded-2xl border border-black/8 bg-white transition hover:-translate-y-0.5 hover:shadow-md">

    {{-- ── IMAGE ── --}}
    <a href="{{ route('menu.show', $product->slug) }}" class="relative block aspect-square overflow-hidden bg-[#f5ede5]">

        <img src="{{ $product->image_url }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
             onerror="this.src='https://placehold.co/400x400/f5ede5/c0392b?text=🍽️'">

        {{-- Out of stock overlay --}}
        @if($product->isOutOfStock())
            <div class="absolute inset-0 flex items-center justify-center"
                 style="background:rgba(0,0,0,.52)">
                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-black text-gray-800 shadow">
                    ❌ স্টক শেষ
                </span>
            </div>
        @endif

        {{-- Popular badge --}}
        @if($product->popular && !$product->isOutOfStock())
            <div class="absolute left-2 top-2">
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-black text-[#1c0f09] shadow"
                      style="background:rgba(245,166,35,.92)">
                    🏆 জনপ্রিয়
                </span>
            </div>
        @endif

        {{-- Low stock badge --}}
        @if(!$product->isOutOfStock() && $product->stock !== -1 && $product->stock <= 5)
            <div class="absolute bottom-2 left-2">
                <span class="rounded-full px-2.5 py-1 text-[10px] font-black text-white shadow"
                      style="background:rgba(234,88,12,.92)">
                    ⚡ {{ $product->stock }}টি বাকি
                </span>
            </div>
        @endif

        {{-- Discount badge --}}
        @if($product->old_price && $product->old_price > $product->price)
            <div class="absolute right-2 top-2">
                <span class="rounded-full px-2.5 py-1 text-[10px] font-black text-white shadow"
                      style="background:#c0392b">
                    {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}% ছাড়
                </span>
            </div>
        @endif

        {{-- View count (bottom right) --}}
        <div class="absolute bottom-2 right-2 flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold text-white"
             style="background:rgba(0,0,0,.4)">
            👁 {{ $product->views_label }}
        </div>
    </a>

    {{-- ── BODY ── --}}
    <div class="p-3">
        <div class="mb-1 text-[10px] font-bold uppercase tracking-wider" style="color:#c0392b">
            {{ $product->category->name ?? '' }}
        </div>

        <a href="{{ route('menu.show', $product->slug) }}"
           class="block text-sm font-black leading-snug text-[#1c0f09] hover:text-[#c0392b] transition line-clamp-2">
            {{ $product->name }}
        </a>

        @if($product->spicy)
            <div class="mt-1 text-xs text-orange-600 font-bold">🌶️ ঝাল</div>
        @endif

        {{-- Price + Add button --}}
        <div class="mt-3 flex items-center justify-between gap-2">
            <div>
                <div class="font-display text-base font-black" style="color:#c0392b">
                    ৳{{ number_format($product->price) }}
                </div>
                @if($product->old_price)
                    <div class="text-[10px] text-gray-400 line-through leading-none">
                        ৳{{ number_format($product->old_price) }}
                    </div>
                @endif
            </div>

            {{-- Add to cart OR out of stock --}}
            @if($product->isOutOfStock())
                <button disabled
                    class="flex h-9 w-9 flex-shrink-0 cursor-not-allowed items-center justify-center rounded-full text-gray-300"
                    style="background:#f0f0f0;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </button>
            @else
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <button type="submit"
                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-white transition hover:opacity-90 active:scale-95 shadow-sm"
                        style="background:#c0392b;"
                        title="কার্টে যোগ করুন">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>