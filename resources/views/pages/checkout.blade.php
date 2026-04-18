@extends('layouts.app')
@section('title', 'চেকআউট — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  <p class="text-xs font-bold uppercase tracking-widest text-primary">অর্ডার ফাইনাল</p>
  <h1 class="mt-1 font-display text-3xl font-bold sm:text-4xl">চেকআউট</h1>

  <form action="{{ route('checkout.store') }}" method="POST"
        x-data="{ method: '{{ old('payment_method', 'cod') }}' }"
        class="mt-8 grid gap-6 lg:grid-cols-[1fr,360px]">
    @csrf

    <div class="space-y-6">
      <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h2 class="font-display text-xl font-bold">📍 ডেলিভারি ঠিকানা</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">নাম *</span>
            <input name="customer_name" value="{{ old('customer_name') }}" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ফোন *</span>
            <input name="phone" value="{{ old('phone') }}" required placeholder="০১৭xxxxxxxx" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          </label>
          <label class="block sm:col-span-2">
            <span class="text-xs font-bold text-charcoal/70">পূর্ণ ঠিকানা *</span>
            <textarea name="address" required rows="3" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">{{ old('address') }}</textarea>
          </label>
          <label class="block sm:col-span-2">
            <span class="text-xs font-bold text-charcoal/70">নোট (ঐচ্ছিক)</span>
            <input name="notes" value="{{ old('notes') }}" placeholder="ঝাল কম, এক্সট্রা সালাদ ইত্যাদি" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          </label>
        </div>
      </section>

      <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h2 class="font-display text-xl font-bold">💳 পেমেন্ট মেথড</h2>

        <div class="mt-5 grid gap-3 sm:grid-cols-3">
          <label :class="method === 'cod' ? 'border-primary bg-primary/5 ring-2 ring-primary/30' : 'border-charcoal/15 hover:border-primary/40'"
                 class="cursor-pointer rounded-xl border p-4 transition">
            <input type="radio" name="payment_method" value="cod" x-model="method" class="sr-only">
            <div class="flex items-center gap-3">
              <div class="text-2xl">💵</div>
              <div>
                <div class="font-bold text-sm">ক্যাশ অন ডেলিভারি</div>
                <div class="text-[11px] text-charcoal/60">খাবার পেয়ে পেমেন্ট</div>
              </div>
            </div>
          </label>

          <label :class="method === 'bkash' ? 'border-[#e2136e] bg-[#e2136e]/5 ring-2 ring-[#e2136e]/30' : 'border-charcoal/15 hover:border-[#e2136e]/40'"
                 class="cursor-pointer rounded-xl border p-4 transition">
            <input type="radio" name="payment_method" value="bkash" x-model="method" class="sr-only">
            <div class="flex items-center gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#e2136e] text-white text-sm font-bold">bK</div>
              <div>
                <div class="font-bold text-sm">bKash</div>
                <div class="text-[11px] text-charcoal/60">মোবাইল পেমেন্ট</div>
              </div>
            </div>
          </label>

          <label :class="method === 'nagad' ? 'border-[#f47216] bg-[#f47216]/5 ring-2 ring-[#f47216]/30' : 'border-charcoal/15 hover:border-[#f47216]/40'"
                 class="cursor-pointer rounded-xl border p-4 transition">
            <input type="radio" name="payment_method" value="nagad" x-model="method" class="sr-only">
            <div class="flex items-center gap-3">
              <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#f47216] text-white text-sm font-bold">N</div>
              <div>
                <div class="font-bold text-sm">Nagad</div>
                <div class="text-[11px] text-charcoal/60">মোবাইল পেমেন্ট</div>
              </div>
            </div>
          </label>
        </div>

        <div x-show="method === 'bkash' || method === 'nagad'" x-transition x-cloak class="mt-5 rounded-xl bg-cream p-4 text-sm">
          <div class="mb-2 font-bold" x-text="method === 'bkash' ? 'bKash মার্চেন্ট: 01711-000000' : 'Nagad মার্চেন্ট: 01811-000000'"></div>
          <p class="text-xs text-charcoal/70">উপরের নম্বরে "Send Money" করে নিচে Transaction ID দিন।</p>
          <input name="trx_id" value="{{ old('trx_id') }}" placeholder="যেমন: 9F8E7D6C5B" class="mt-3 w-full rounded-lg border border-charcoal/15 bg-white px-4 py-2.5 font-mono text-sm focus:border-primary focus:outline-none">
        </div>
      </section>
    </div>

    <aside class="h-fit rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-xl font-bold">আপনার অর্ডার</h2>
      <ul class="mt-5 max-h-72 space-y-3 overflow-y-auto pr-2 text-sm">
        @foreach ($items as $item)
          @php $p = $item['product']; @endphp
          <li class="flex items-center gap-3">
            <img src="{{ $p->image_url }}" alt="" class="h-12 w-12 rounded-lg object-cover">
            <div class="min-w-0 flex-1">
              <div class="truncate font-bold text-xs">{{ $p->name }}</div>
              <div class="text-[11px] text-charcoal/60">×{{ $item['qty'] }}</div>
            </div>
            <div class="font-bold text-xs">৳{{ number_format($p->price * $item['qty']) }}</div>
          </li>
        @endforeach
      </ul>
      <dl class="mt-5 space-y-2 border-t border-charcoal/10 pt-4 text-sm">
        <div class="flex justify-between"><dt class="text-charcoal/60">সাব-টোটাল</dt><dd class="font-bold">৳{{ number_format($subtotal) }}</dd></div>
        <div class="flex justify-between"><dt class="text-charcoal/60">ডেলিভারি</dt><dd class="font-bold">{{ $deliveryFee === 0 ? 'ফ্রি 🎉' : '৳'.number_format($deliveryFee) }}</dd></div>
        <div class="flex justify-between border-t border-charcoal/10 pt-2 text-lg">
          <dt class="font-display font-bold">মোট</dt>
          <dd class="font-bold gradient-text">৳{{ number_format($total) }}</dd>
        </div>
      </dl>
      <button type="submit" class="mt-6 w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02]">
        ✅ অর্ডার কনফার্ম করুন
      </button>
      <p class="mt-3 text-center text-[11px] text-charcoal/50">অর্ডার দিলেই আপনি আমাদের শর্ত মেনে নিচ্ছেন।</p>
    </aside>

  </form>
</div>
@endsection
