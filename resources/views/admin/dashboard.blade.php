@extends('admin.layouts.app')
@section('title', 'ড্যাশবোর্ড')
@section('header', 'ড্যাশবোর্ড')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  @php
    $cards = [
      ['label' => 'আজকের বিক্রয়', 'value' => '৳' . number_format($stats['sales_today']), 'icon' => '💰', 'tint' => 'from-primary to-primary-glow'],
      ['label' => 'আজকের অর্ডার',   'value' => $stats['orders_today'],                       'icon' => '🧾', 'tint' => 'from-spice to-amber-400'],
      ['label' => 'পেন্ডিং অর্ডার',  'value' => $stats['orders_pending'],                     'icon' => '⏳', 'tint' => 'from-orange-500 to-red-500'],
      ['label' => 'মোট পণ্য',         'value' => $stats['products_total'],                     'icon' => '🍽️', 'tint' => 'from-emerald-500 to-teal-500'],
    ];
  @endphp
  @foreach ($cards as $c)
    <div class="rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft">
      <div class="flex items-center justify-between">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $c['tint'] }} text-xl text-white shadow-warm">{{ $c['icon'] }}</div>
      </div>
      <div class="mt-4 font-display text-3xl font-bold">{{ $c['value'] }}</div>
      <div class="mt-1 text-xs font-bold uppercase tracking-widest text-charcoal/60">{{ $c['label'] }}</div>
    </div>
  @endforeach
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-3">
  <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft lg:col-span-2">
    <div class="flex items-center justify-between">
      <h2 class="font-display text-lg font-bold">সাপ্তাহিক বিক্রয়</h2>
      <span class="text-xs text-charcoal/60">গত ৭ দিন</span>
    </div>
    @php $maxSale = max($salesByDay->max('total'), 1); @endphp
    <div class="mt-6 grid grid-cols-7 gap-2">
      @foreach ($salesByDay as $d)
        @php $h = max(8, ($d['total'] / $maxSale) * 160); @endphp
        <div class="flex flex-col items-center gap-2">
          <div class="text-[10px] font-bold text-primary">৳{{ number_format($d['total']) }}</div>
          <div class="w-full rounded-t-lg bg-gradient-warm" style="height: {{ $h }}px"></div>
          <div class="text-[10px] text-charcoal/60">{{ $d['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
    <h2 class="font-display text-lg font-bold">দ্রুত নেভিগেশন</h2>
    <div class="mt-4 grid gap-2 text-sm">
      <a href="{{ route('admin.products.create') }}" class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 hover:border-primary hover:text-primary">➕ নতুন পণ্য <span>→</span></a>
      <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 hover:border-primary hover:text-primary">⏳ পেন্ডিং অর্ডার ({{ $stats['orders_pending'] }}) <span>→</span></a>
      <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 hover:border-primary hover:text-primary">🏷️ ক্যাটাগরি ({{ $stats['categories'] }}) <span>→</span></a>
    </div>
  </div>
</div>

<div class="mt-6 rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
  <div class="flex items-center justify-between">
    <h2 class="font-display text-lg font-bold">সাম্প্রতিক অর্ডার</h2>
    <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-primary hover:underline">সব দেখুন →</a>
  </div>
  <div class="mt-4 overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-xs uppercase tracking-wider text-charcoal/60">
        <tr class="border-b border-charcoal/10">
          <th class="py-2 text-left">ইনভয়েস</th><th class="text-left">কাস্টমার</th><th class="text-left">সময়</th>
          <th class="text-left">পেমেন্ট</th><th class="text-right">মোট</th><th class="text-center">স্ট্যাটাস</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recent as $o)
          <tr class="border-b border-charcoal/5 hover:bg-cream">
            <td class="py-3"><a href="{{ route('admin.orders.show', $o) }}" class="font-mono font-bold text-primary hover:underline">{{ $o->invoice_no }}</a></td>
            <td>{{ $o->customer_name }}</td>
            <td class="text-charcoal/60">{{ $o->created_at->diffForHumans() }}</td>
            <td><span class="rounded-full bg-charcoal/5 px-2 py-0.5 text-[10px] font-bold uppercase">{{ $o->payment_method }}</span></td>
            <td class="text-right font-bold">৳{{ number_format($o->total) }}</td>
            <td class="text-center">@include('admin.partials.status-badge', ['status' => $o->status])</td>
          </tr>
        @empty
          <tr><td colspan="6" class="py-8 text-center text-charcoal/50">কোনো অর্ডার নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
