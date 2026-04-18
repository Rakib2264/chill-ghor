@extends('admin.layouts.app')
@section('title', $order->invoice_no)
@section('header', 'অর্ডার: ' . $order->invoice_no)

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr,360px]">

  <div class="space-y-6">
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-xs font-bold uppercase tracking-widest text-primary">ইনভয়েস</div>
          <div class="mt-1 font-mono text-2xl font-bold">{{ $order->invoice_no }}</div>
          <div class="mt-1 text-xs text-charcoal/60">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="text-right">@include('admin.partials.status-badge', ['status' => $order->status])</div>
      </div>

      <table class="mt-6 w-full text-sm">
        <thead class="text-xs uppercase tracking-wider text-charcoal/60">
          <tr class="border-b border-charcoal/10"><th class="py-2 text-left">পণ্য</th><th class="text-center">মূল্য</th><th class="text-center">পরিমাণ</th><th class="text-right">মোট</th></tr>
        </thead>
        <tbody>
          @foreach ($order->items as $it)
            <tr class="border-b border-charcoal/5">
              <td class="py-3 font-bold">{{ $it->product_name }}</td>
              <td class="text-center">৳{{ number_format($it->price) }}</td>
              <td class="text-center">×{{ $it->quantity }}</td>
              <td class="text-right font-bold">৳{{ number_format($it->line_total) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <dl class="mt-4 ml-auto grid w-full max-w-xs gap-2 text-sm">
        <div class="flex justify-between"><dt class="text-charcoal/60">সাব-টোটাল</dt><dd class="font-bold">৳{{ number_format($order->subtotal) }}</dd></div>
        <div class="flex justify-between"><dt class="text-charcoal/60">ডেলিভারি</dt><dd class="font-bold">{{ $order->delivery_fee == 0 ? 'ফ্রি' : '৳'.number_format($order->delivery_fee) }}</dd></div>
        <div class="flex justify-between border-t border-charcoal/10 pt-2 text-lg"><dt class="font-display font-bold">মোট</dt><dd class="font-bold gradient-text">৳{{ number_format($order->total) }}</dd></div>
      </dl>
    </section>
  </div>

  <aside class="space-y-6">
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h3 class="font-display font-bold">কাস্টমার তথ্য</h3>
      <dl class="mt-4 space-y-3 text-sm">
        <div><dt class="text-xs text-charcoal/60">নাম</dt><dd class="font-bold">{{ $order->customer_name }}</dd></div>
        <div><dt class="text-xs text-charcoal/60">ফোন</dt><dd class="font-bold"><a href="tel:{{ $order->phone }}" class="hover:text-primary">{{ $order->phone }}</a></dd></div>
        <div><dt class="text-xs text-charcoal/60">ঠিকানা</dt><dd class="font-bold">{{ $order->address }}</dd></div>
        @if ($order->notes)
          <div><dt class="text-xs text-charcoal/60">নোট</dt><dd class="text-charcoal/80">{{ $order->notes }}</dd></div>
        @endif
        <div><dt class="text-xs text-charcoal/60">পেমেন্ট</dt><dd class="font-bold">{{ strtoupper($order->payment_method) }} @if($order->trx_id)<span class="font-mono text-xs text-charcoal/60">({{ $order->trx_id }})</span>@endif</dd></div>
      </dl>
    </section>

    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h3 class="font-display font-bold">স্ট্যাটাস আপডেট</h3>
      <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-4 space-y-3">
        @csrf @method('PATCH')
        <select name="status" class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          @foreach (['pending' => 'বকেয়া','confirmed' => 'কনফার্মড','preparing' => 'রান্নায়','delivered' => 'ডেলিভার্ড','cancelled' => 'বাতিল'] as $val => $label)
            <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
          @endforeach
        </select>
        <button class="w-full rounded-full bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm">আপডেট করুন</button>
      </form>

      <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('অর্ডারটি মুছে ফেলবেন?')" class="mt-3">
        @csrf @method('DELETE')
        <button class="w-full rounded-full border border-red-200 bg-white py-2.5 text-sm font-bold text-red-600 hover:bg-red-50">🗑️ অর্ডার মুছুন</button>
      </form>
    </section>

    <a href="{{ route('admin.orders.index') }}" class="block text-center text-xs font-bold text-charcoal/60 hover:text-primary">← সব অর্ডার</a>
  </aside>
</div>
@endsection
