@extends('layouts.app')
@section('title', 'অর্ডার সফল — চিল ঘর')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6 lg:px-8 animate-slide-up">
  <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-warm text-5xl shadow-warm">✅</div>
  <h1 class="mt-6 font-display text-3xl font-bold sm:text-4xl">অর্ডার সফল হয়েছে!</h1>
  <p class="mt-3 text-charcoal/70">আপনার অর্ডার আমরা পেয়েছি। শীঘ্রই কনফার্ম কলের জন্য অপেক্ষা করুন।</p>

  <div class="mt-8 rounded-2xl border border-charcoal/10 bg-white p-6 text-left shadow-soft">
    <div class="flex items-center justify-between border-b border-dashed border-charcoal/15 pb-4">
      <div>
        <div class="text-xs font-bold uppercase tracking-widest text-primary">ইনভয়েস</div>
        <div class="font-display text-xl font-bold">{{ $order->invoice_no }}</div>
      </div>
      <div class="text-right">
        <div class="text-xs text-charcoal/60">তারিখ</div>
        <div class="text-sm font-bold">{{ $order->created_at->format('d M Y, h:i A') }}</div>
      </div>
    </div>

    <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
      <div><dt class="text-xs text-charcoal/60">নাম</dt><dd class="font-bold">{{ $order->customer_name }}</dd></div>
      <div><dt class="text-xs text-charcoal/60">ফোন</dt><dd class="font-bold">{{ $order->phone }}</dd></div>
      <div class="col-span-2"><dt class="text-xs text-charcoal/60">ঠিকানা</dt><dd class="font-bold">{{ $order->address }}</dd></div>
      <div><dt class="text-xs text-charcoal/60">পেমেন্ট</dt>
        <dd class="font-bold">
          @switch($order->payment_method)
            @case('cod') 💵 ক্যাশ অন ডেলিভারি @break
            @case('bkash') bKash ({{ $order->trx_id }}) @break
            @case('nagad') Nagad ({{ $order->trx_id }}) @break
          @endswitch
        </dd>
      </div>
      <div><dt class="text-xs text-charcoal/60">স্ট্যাটাস</dt><dd class="font-bold">⏳ পেন্ডিং</dd></div>
    </dl>

    <ul class="mt-4 space-y-2 border-t border-dashed border-charcoal/15 pt-4 text-sm">
      @foreach ($order->items as $it)
        <li class="flex justify-between">
          <span>{{ $it->product_name }} <span class="text-charcoal/50">×{{ $it->quantity }}</span></span>
          <span class="font-bold">৳{{ number_format($it->line_total) }}</span>
        </li>
      @endforeach
    </ul>

    <div class="mt-4 flex justify-between border-t border-charcoal/15 pt-4 text-lg">
      <span class="font-display font-bold">মোট</span>
      <span class="font-bold gradient-text">৳{{ number_format($order->total) }}</span>
    </div>
  </div>

  <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm">হোমে ফিরে যান</a>
</div>
@endsection
