@php
    use App\Models\Setting;

    $promoEnabled = (bool) Setting::get('promo_bar_enabled', false);
    $promoText = Setting::get('promo_bar_text', '🎉 আজকের অফার — কাচ্চি বিরিয়ানিতে ১৫% ছাড়!');
    $promoLink = Setting::get('promo_bar_link', '/menu');
    $promoButtonText = Setting::get('promo_button_text', 'অর্ডার করুন →');
@endphp

@if ($promoEnabled)
    <div x-data="{
            show: !sessionStorage.getItem('cg_promo_dismissed'),
            close() { this.show = false; sessionStorage.setItem('cg_promo_dismissed', '1'); }
        }"
        x-show="show" x-cloak
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 -translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-full"
        class="relative overflow-hidden text-white text-[13px] font-semibold"
        style="background: linear-gradient(90deg, #c0392b 0%, #e8671a 50%, #c0392b 100%); background-size: 200% 100%; animation: shimmer 8s linear infinite;">

        <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-2.5">
            {{-- Marquee text + button --}}
            <div class="flex-1 overflow-hidden">
                <div class="marquee-track whitespace-nowrap">
                    <span class="inline-flex items-center gap-3 pr-12">
                        <i class="fa-solid fa-bolt text-spice"></i>
                        <span>{{ $promoText }}</span>
                        <a href="{{ $promoLink }}"
                           class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-black tracking-wide ring-1 ring-white/20 hover:bg-white hover:text-primary transition">
                            {{ $promoButtonText }}
                        </a>
                    </span>
                    <span class="inline-flex items-center gap-3 pr-12">
                        <i class="fa-solid fa-bolt text-spice"></i>
                        <span>{{ $promoText }}</span>
                        <a href="{{ $promoLink }}"
                           class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-black tracking-wide ring-1 ring-white/20 hover:bg-white hover:text-primary transition">
                            {{ $promoButtonText }}
                        </a>
                    </span>
                </div>
            </div>

            <button @click="close()" aria-label="বন্ধ করুন"
                class="flex-shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-white/12 text-white/90 hover:bg-white hover:text-primary transition">
                <i class="fa-solid fa-xmark text-[12px]"></i>
            </button>
        </div>
    </div>
@endif