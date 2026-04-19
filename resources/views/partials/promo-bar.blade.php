@php
    use App\Models\Setting;
    
    $promoEnabled = Setting::get('promo_bar_enabled', true);
    $promoText = Setting::get('promo_bar_text', '🎉 আজকের অফার — কাচ্চি বিরিয়ানিতে ১৫% ছাড়!');
    $promoLink = Setting::get('promo_bar_link', '/menu');
    $promoButtonText = Setting::get('promo_button_text', 'অর্ডার করুন →');
@endphp

@if($promoEnabled)
<div class="bg-gradient-warm text-white text-center py-2.5 px-4 text-sm font-semibold flex items-center justify-center gap-3">
  <span>{{ $promoText }}</span>
  <a href="{{ $promoLink }}" class="underline underline-offset-2 font-bold hover:opacity-80 transition">{{ $promoButtonText }}</a>
</div>
@endif