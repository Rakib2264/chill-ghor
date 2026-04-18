@extends('layouts.app')
@section('title', 'অর্ডার ' . $order->invoice_no . ' — চিল ঘর')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

  <div class="mb-6 flex items-center justify-between">
    <div>
      <a href="{{ route('profile.orders') }}" class="text-sm font-bold text-charcoal/60 hover:text-primary flex items-center gap-1 mb-3">
        ← অর্ডার হিস্টরিতে ফিরুন
      </a>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">অর্ডার ডিটেইল</p>
      <h1 class="mt-1 font-display text-3xl font-bold">{{ $order->invoice_no }}</h1>
    </div>
    <a href="{{ route('profile.orders.print', $order) }}" target="_blank"
       class="rounded-full bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm">
      🖨️ প্রিন্ট করুন
    </a>
  </div>

  <div class="grid gap-6 lg:grid-cols-[1fr,300px]">
    
    <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <div class="flex items-start justify-between border-b border-dashed border-charcoal/15 pb-4">
        <div>
          <div class="text-xs font-bold uppercase tracking-widest text-primary">ইনভয়েস</div>
          <div class="mt-1 font-mono text-xl font-bold">{{ $order->invoice_no }}</div>
          <div class="mt-1 text-xs text-charcoal/60">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="text-right">
          @include('admin.partials.status-badge', ['status' => $order->status])
        </div>
      </div>

      <table class="mt-6 w-full text-sm">
        <thead class="text-xs uppercase tracking-wider text-charcoal/60">
          <tr class="border-b border-charcoal/10">
            <th class="py-2 text-left">পণ্য</th>
            <th class="text-center">মূল্য</th>
            <th class="text-center">পরিমাণ</th>
            <th class="text-right">মোট</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
            <tr class="border-b border-charcoal/5">
              <td class="py-3 font-bold">{{ $item->product_name }}</td>
              <td class="text-center">৳{{ number_format($item->price) }}</td>
              <td class="text-center">×{{ $item->quantity }}</td>
              <td class="text-right font-bold">৳{{ number_format($item->line_total) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <dl class="mt-4 ml-auto grid w-full max-w-xs gap-2 text-sm">
        <div class="flex justify-between">
          <dt class="text-charcoal/60">সাব-টোটাল</dt>
          <dd class="font-bold">৳{{ number_format($order->subtotal) }}</dd>
        </div>
        <div class="flex justify-between">
          <dt class="text-charcoal/60">ডেলিভারি</dt>
          <dd class="font-bold">{{ $order->delivery_fee == 0 ? 'ফ্রি' : '৳'.number_format($order->delivery_fee) }}</dd>
        </div>
        <div class="flex justify-between border-t border-charcoal/10 pt-2 text-lg">
          <dt class="font-display font-bold">মোট</dt>
          <dd class="font-bold text-primary">৳{{ number_format($order->total) }}</dd>
        </div>
      </dl>
    </div>

    <aside class="space-y-6">
      <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h3 class="font-display font-bold">ডেলিভারি তথ্য</h3>
        <dl class="mt-4 space-y-3 text-sm">
          <div><dt class="text-xs text-charcoal/60">নাম</dt><dd class="font-bold">{{ $order->customer_name }}</dd></div>
          <div><dt class="text-xs text-charcoal/60">ফোন</dt><dd class="font-bold">{{ $order->phone }}</dd></div>
          <div><dt class="text-xs text-charcoal/60">ঠিকানা</dt><dd class="font-bold">{{ $order->address }}</dd></div>
          @if($order->notes)
            <div><dt class="text-xs text-charcoal/60">নোট</dt><dd class="text-charcoal/80">{{ $order->notes }}</dd></div>
          @endif
          <div>
            <dt class="text-xs text-charcoal/60">পেমেন্ট মেথড</dt>
            <dd class="font-bold">
              @switch($order->payment_method)
                @case('cod') 💵 ক্যাশ অন ডেলিভারি @break
                @case('bkash') <span class="text-[#e2136e]">bKash</span> @break
                @case('nagad') <span class="text-[#f47216]">Nagad</span> @break
              @endswitch
              @if($order->trx_id)
                <div class="font-mono text-xs text-charcoal/60 mt-1">{{ $order->trx_id }}</div>
              @endif
            </dd>
          </div>
        </dl>
      </section>
    </aside>
  </div>
</div>
@endsection