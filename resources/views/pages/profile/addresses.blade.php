@extends('layouts.app')
@section('title', 'আমার ঠিকানা — চিল ঘর')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8" x-data="{ adding: false, editing: null }">

  @include('pages.profile._tabs', ['active' => 'addresses'])

  <div class="mt-6 flex items-center justify-between">
    <div>
      <h1 class="font-display text-2xl font-bold">📍 আমার ঠিকানা</h1>
      <p class="text-sm text-charcoal/55">একাধিক ঠিকানা সংরক্ষণ করুন — চেকআউটে দ্রুত বেছে নিন।</p>
    </div>
    <button @click="adding = true; editing = null"
            class="rounded-full bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02]">
      + নতুন ঠিকানা
    </button>
  </div>

  {{-- ─── Add / Edit Form ──────────────────────────────────────────────────── --}}
  <div x-show="adding || editing" x-cloak x-transition
       class="mt-5 rounded-2xl border-2 border-primary/30 bg-white p-6 shadow-warm">

    <h3 class="font-display font-bold text-lg mb-4"
        x-text="editing ? '✏️ ঠিকানা সম্পাদনা' : '➕ নতুন ঠিকানা'"></h3>

    <form :action="editing ? `/profile/addresses/${editing}` : '{{ route('profile.addresses.store') }}'"
          method="POST" class="grid gap-3 sm:grid-cols-2">
      @csrf
      <template x-if="editing"><input type="hidden" name="_method" value="PATCH"></template>

      {{-- লেবেল --}}
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">লেবেল *</span>
        <select name="label" id="addr_label"
                class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          <option>Home</option>
          <option>Office</option>
          <option>Other</option>
        </select>
      </label>

      {{-- প্রাপকের নাম --}}
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">প্রাপকের নাম *</span>
        <input type="text" name="recipient_name" required
               value="{{ auth()->user()->name }}" id="addr_recipient"
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
      </label>

      {{-- ফোন --}}
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">ফোন *</span>
        <input type="tel" name="phone" required
               value="{{ auth()->user()->phone }}" id="addr_phone"
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
      </label>

      {{-- এলাকা --}}
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">এলাকা (ঐচ্ছিক)</span>
        <input type="text" name="area" placeholder="যেমন: ধানমন্ডি" id="addr_area"
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
      </label>

      {{-- ডেলিভারি জোন ── নতুন ── --}}
      <label class="block sm:col-span-2">
        <span class="text-xs font-bold text-charcoal/70">ডেলিভারি জোন *</span>
        <select name="delivery_zone_id" id="addr_zone" required
                class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          <option value="">— জোন বেছে নিন —</option>
          @foreach ($deliveryZones as $zone)
            <option value="{{ $zone->id }}">
              {{ $zone->zone_name }}
              (ডেলিভারি চার্জ: ৳{{ number_format($zone->delivery_charge) }}
              · ৳{{ number_format($zone->min_order_for_free) }}+ হলে ফ্রি)
            </option>
          @endforeach
        </select>
        <p class="mt-1 text-[11px] text-charcoal/50">
          ⚡ এই জোনটি চেকআউটে স্বয়ংক্রিয়ভাবে ডেলিভারি চার্জ সেট করবে।
        </p>
      </label>

      {{-- পূর্ণ ঠিকানা --}}
      <label class="block sm:col-span-2">
        <span class="text-xs font-bold text-charcoal/70">পূর্ণ ঠিকানা *</span>
        <textarea name="address_line" rows="2" required id="addr_line"
                  class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none"></textarea>
      </label>

      {{-- ডিফল্ট --}}
      <label class="flex items-center gap-2 text-sm sm:col-span-2">
        <input type="checkbox" name="is_default" value="1" id="addr_default"
               class="h-4 w-4 accent-primary">
        ডিফল্ট ঠিকানা হিসেবে সেট করুন
      </label>

      <div class="flex gap-2 sm:col-span-2">
        <button type="submit"
                class="flex-1 rounded-full bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02]">
          💾 সংরক্ষণ
        </button>
        <button type="button" @click="adding = false; editing = null"
                class="rounded-full border border-charcoal/15 px-6 py-2.5 text-sm font-bold hover:border-primary hover:text-primary">
          বাতিল
        </button>
      </div>
    </form>
  </div>

  {{-- ─── Saved Addresses List ──────────────────────────────────────────────── --}}
  @if ($addresses->isEmpty())
    <div class="mt-8 rounded-3xl border-2 border-dashed border-charcoal/15 bg-white py-16 text-center">
      <div class="text-6xl mb-4">📭</div>
      <p class="font-display text-lg font-bold">কোনো ঠিকানা সংরক্ষিত নেই</p>
      <p class="text-sm text-charcoal/55 mt-1">প্রথম ঠিকানা যোগ করুন।</p>
    </div>
  @else
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
      @foreach ($addresses as $a)
        <div class="rounded-2xl border-2 p-5 shadow-soft transition
                    {{ $a->is_default ? 'border-primary bg-primary/5' : 'border-charcoal/10 bg-white' }}">

          <div class="flex items-start justify-between mb-2">
            <div class="flex items-center gap-2">
              <span class="font-display font-bold">{{ $a->label }}</span>
              @if($a->is_default)
                <span class="rounded-full bg-primary px-2 py-0.5 text-[10px] font-bold text-white">ডিফল্ট</span>
              @endif
            </div>
            <div class="flex gap-1">
              <button @click='editing = {{ $a->id }}; adding = false; setTimeout(() => fillForm(@json($a)), 50)'
                      class="h-7 w-7 rounded-lg text-charcoal/50 hover:bg-cream hover:text-primary" title="Edit">✏️</button>
              <form action="{{ route('profile.addresses.destroy', $a) }}" method="POST"
                    onsubmit="return confirm('মুছবেন?')">
                @csrf @method('DELETE')
                <button class="h-7 w-7 rounded-lg text-charcoal/50 hover:bg-red-50 hover:text-red-500" title="Delete">🗑️</button>
              </form>
            </div>
          </div>

          <div class="text-sm text-charcoal/80">{{ $a->recipient_name }}</div>
          <div class="text-xs text-charcoal/60 mt-0.5">{{ $a->phone }}</div>
          <div class="text-sm text-charcoal/70 mt-2">
            {{ $a->area ? $a->area . ', ' : '' }}{{ $a->address_line }}
          </div>

          {{-- ─── ডেলিভারি জোন badge ─── --}}
          @if ($a->deliveryZone)
            <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
              🚚 {{ $a->deliveryZone->zone_name }} — ৳{{ number_format($a->deliveryZone->delivery_charge) }}
            </div>
          @else
            <div class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-xs text-amber-700">
              ⚠️ জোন নির্ধারিত নেই
            </div>
          @endif

          @if (!$a->is_default)
            <form action="{{ route('profile.addresses.default', $a) }}" method="POST" class="mt-3">
              @csrf @method('PATCH')
              <button class="text-xs font-bold text-primary hover:underline">⭐ ডিফল্ট হিসেবে সেট করুন</button>
            </form>
          @endif
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
function fillForm(a) {
  document.getElementById('addr_label').value     = a.label;
  document.getElementById('addr_recipient').value = a.recipient_name;
  document.getElementById('addr_phone').value     = a.phone;
  document.getElementById('addr_area').value      = a.area || '';
  document.getElementById('addr_zone').value      = a.delivery_zone_id || '';
  document.getElementById('addr_line').value      = a.address_line;
  document.getElementById('addr_default').checked = !!a.is_default;
}
</script>
@endsection