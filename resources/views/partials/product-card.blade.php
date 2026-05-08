@php
    use App\Support\Wishlist;
    $inWish = Wishlist::has($product->id);
    $hasDiscount = $product->old_price && $product->old_price > $product->price;
    $discountPct = $hasDiscount ? round((($product->old_price - $product->price) / $product->old_price) * 100) : 0;
@endphp
<div class="group relative overflow-hidden rounded-2xl bg-white shadow-ring lift">

    {{-- ── IMAGE ── --}}
    <a href="{{ route('menu.show', $product->slug) }}" class="relative block aspect-square overflow-hidden bg-[#f5ede5]">

        <img src="{{ $product->image_url }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="h-full w-full object-cover zoom-img"
             onerror="this.src='https://placehold.co/400x400/f5ede5/c0392b?text=🍽️'">

        {{-- subtle bottom gradient for badge legibility --}}
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-2/5"
             style="background:linear-gradient(to top, rgba(28,15,9,.42), transparent);"></div>

        {{-- Out of stock overlay --}}
        @if($product->isOutOfStock())
            <div class="absolute inset-0 flex items-center justify-center"
                 style="background:rgba(0,0,0,.55);backdrop-filter:blur(2px);">
                <span class="rounded-full bg-white px-3.5 py-1.5 text-[11px] font-black text-gray-800 shadow">
                    <i class="fa-solid fa-circle-xmark mr-1 text-red-500"></i> স্টক শেষ
                </span>
            </div>
        @endif

        {{-- Top-left badges --}}
        <div class="absolute left-2 top-2 flex flex-col gap-1.5">
            @if($product->popular && !$product->isOutOfStock())
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-black text-[#1c0f09] shadow"
                      style="background:rgba(245,166,35,.95)">
                    <i class="fa-solid fa-trophy text-[9px]"></i> জনপ্রিয়
                </span>
            @endif
            @if(!$product->isOutOfStock() && $product->stock !== -1 && $product->stock <= 5)
                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-black text-white shadow"
                      style="background:rgba(234,88,12,.95)">
                    <i class="fa-solid fa-bolt text-[9px]"></i> {{ $product->stock }}টি বাকি
                </span>
            @endif
        </div>

        {{-- Discount badge (top right) --}}
        @if($hasDiscount)
            <div class="absolute right-2 top-2">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-black text-white shadow"
                      style="background:#c0392b">
                    {{ $discountPct }}% ছাড়
                </span>
            </div>
        @endif

        {{-- View count (bottom right) --}}
        <div class="absolute bottom-2 right-2 flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-bold text-white"
             style="background:rgba(0,0,0,.4)">
            <i class="fa-regular fa-eye text-[9px]"></i> {{ $product->views_label }}
        </div>
    </a>

    {{-- Wishlist (floating) --}}
    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute right-2 top-2 z-10 opacity-0 group-hover:opacity-100 sm:opacity-0 transition" style="transform: translateY(0);">
        @csrf
        <button type="submit"
            class="flex h-8 w-8 items-center justify-center rounded-full backdrop-blur-md shadow transition hover:scale-110 active:scale-95
            {{ $inWish ? 'bg-red-600 text-white' : 'bg-white/90 text-charcoal/70 hover:text-red-500' }}"
            title="{{ $inWish ? 'উইশলিস্ট থেকে সরান' : 'উইশলিস্টে যোগ করুন' }}">
            <i class="fa-{{ $inWish ? 'solid' : 'regular' }} fa-heart text-[12px]"></i>
        </button>
    </form>

    {{-- ── BODY ── --}}
    <div class="p-3 sm:p-3.5">
        <div class="mb-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider" style="color:#c0392b">
            <span class="inline-block h-1 w-1 rounded-full bg-primary"></span>
            {{ $product->category->name ?? '' }}
        </div>

        <a href="{{ route('menu.show', $product->slug) }}"
           class="block text-sm font-black leading-snug text-[#1c0f09] hover:text-[#c0392b] transition line-clamp-2 min-h-[2.6em]">
            {{ $product->name }}
        </a>

        @if($product->spicy)
            <div class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold text-orange-600">
                <span>🌶️</span> ঝাল
            </div>
        @endif

        {{-- Price + Add button --}}
        <div class="mt-3 flex items-center justify-between gap-2">
            <div>
                <div class="font-display text-base font-black leading-none" style="color:#c0392b">
                    ৳{{ number_format($product->price) }}
                </div>
                @if($product->old_price)
                    <div class="text-[10px] text-gray-400 line-through leading-none mt-0.5">
                        ৳{{ number_format($product->old_price) }}
                    </div>
                @endif
            </div>

            {{-- Add to cart OR out of stock --}}
            @if($product->isOutOfStock())
                <button disabled
                    class="flex h-9 w-9 flex-shrink-0 cursor-not-allowed items-center justify-center rounded-full text-gray-300 bg-gray-100">
                    <i class="fa-solid fa-bag-shopping text-[12px]"></i>
                </button>
            @else
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <button type="submit"
                        class="group/btn flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full text-white transition-all hover:w-auto hover:px-3 hover:gap-1.5 active:scale-95 shadow-warm"
                        style="background:linear-gradient(135deg,#c0392b,#e8671a);"
                        title="কার্টে যোগ করুন">
                        <i class="fa-solid fa-plus text-[11px] font-black"></i>
                        <span class="hidden group-hover/btn:inline text-[11px] font-black whitespace-nowrap">যোগ করুন</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>