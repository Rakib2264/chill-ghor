@extends('admin.layouts.app')
@section('title', 'ড্যাশবোর্ড')
@section('header', 'ড্যাশবোর্ড')

@section('content')

{{-- Stat Cards --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
  @php
    $cards = [
      ['আজকের বিক্রয়',  '৳' . number_format($stats['sales_today']),    '💰', 'from-primary to-primary-glow'],
      ['আজকের অর্ডার',   $stats['orders_today'],                         '🧾', 'from-spice to-amber-400'],
      ['পেন্ডিং অর্ডার',  $stats['orders_pending'],                       '⏳', 'from-orange-500 to-red-500'],
      ['মোট পণ্য',        $stats['products_total'],                        '🍽️', 'from-emerald-500 to-teal-500'],
    ];
  @endphp

  @foreach ($cards as [$label, $value, $icon, $gradient])
    <div class="rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft hover:-translate-y-0.5 transition">
      <div class="flex items-center justify-between mb-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $gradient }} text-xl text-white shadow-warm">
          {{ $icon }}
        </div>
      </div>
      <div class="font-display text-3xl font-bold">{{ $value }}</div>
      <div class="mt-1 text-xs font-bold uppercase tracking-widest text-charcoal/50">{{ $label }}</div>
    </div>
  @endforeach
</div>

{{-- Charts Row --}}
<div class="grid gap-5 lg:grid-cols-3 mb-6">

  {{-- Weekly Sales Bar Chart --}}
  <div class="lg:col-span-2 rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-display text-lg font-bold">সাপ্তাহিক বিক্রয়</h2>
        <p class="text-xs text-charcoal/50 mt-0.5">গত ৭ দিনের তুলনা</p>
      </div>
      <span class="rounded-full bg-cream px-3 py-1 text-xs font-bold text-charcoal/60">গত ৭ দিন</span>
    </div>

    @php $maxSale = max($salesByDay->max('total'), 1); @endphp
    <div class="grid grid-cols-7 gap-2 items-end h-36">
      @foreach ($salesByDay as $d)
        @php $h = max(6, ($d['total'] / $maxSale) * 130); @endphp
        <div class="flex flex-col items-center gap-1">
          <div class="text-[9px] font-bold text-primary leading-none">৳{{ number_format($d['total'] / 1000, 1) }}K</div>
          <div class="w-full rounded-t-lg bg-gradient-warm shadow-warm transition-all hover:opacity-80"
               style="height: {{ $h }}px"></div>
          <div class="text-[9px] text-charcoal/50 font-semibold">{{ $d['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Quick Navigation --}}
  <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
    <h2 class="font-display text-lg font-bold mb-4">দ্রুত নেভিগেশন</h2>
    <div class="grid gap-2 text-sm">
      <a href="{{ route('admin.products.create') }}"
         class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 font-semibold transition hover:border-primary hover:text-primary">
        <span>➕ নতুন পণ্য যোগ</span><span class="text-charcoal/30">→</span>
      </a>
      <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
         class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 font-semibold transition hover:border-primary hover:text-primary">
        <span>⏳ পেন্ডিং অর্ডার</span>
        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">{{ $stats['orders_pending'] }}</span>
      </a>
      <a href="{{ route('admin.categories.index') }}"
         class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 font-semibold transition hover:border-primary hover:text-primary">
        <span>🏷️ ক্যাটাগরি</span>
        <span class="text-charcoal/40 text-xs">{{ $stats['categories'] }}টি →</span>
      </a>
      <a href="{{ route('home') }}" target="_blank"
         class="flex items-center justify-between rounded-xl border border-charcoal/10 bg-cream px-4 py-3 font-semibold transition hover:border-primary hover:text-primary">
        <span>↗ সাইট দেখুন</span><span class="text-charcoal/30">→</span>
      </a>
    </div>
  </div>
</div>

{{-- Recent Orders --}}
<div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft overflow-hidden">
  <div class="flex items-center justify-between border-b border-charcoal/8 px-6 py-4">
    <h2 class="font-display text-lg font-bold">সাম্প্রতিক অর্ডার</h2>
    <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-primary hover:underline">সব দেখুন →</a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-cream/80 text-xs uppercase tracking-wider text-charcoal/50">
          <th class="py-3 px-5 text-left">ইনভয়েস</th>
          <th class="py-3 px-4 text-left">কাস্টমার</th>
          <th class="py-3 px-4 text-left">সময়</th>
          <th class="py-3 px-4 text-left">পেমেন্ট</th>
          <th class="py-3 px-4 text-right">মোট</th>
          <th class="py-3 px-4 text-center">স্ট্যাটাস</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recent as $o)
          <tr class="border-t border-charcoal/5 hover:bg-cream/50 transition-colors">
            <td class="py-3 px-5">
              <a href="{{ route('admin.orders.show', $o) }}"
                 class="font-mono font-bold text-primary hover:underline">
                {{ $o->invoice_no }}
              </a>
            </td>
            <td class="px-4 py-3">{{ $o->customer_name }}</td>
            <td class="px-4 py-3 text-charcoal/55">{{ $o->created_at->diffForHumans() }}</td>
            <td class="px-4 py-3">
              <span class="rounded-full bg-charcoal/8 px-2.5 py-1 text-[10px] font-bold uppercase">
                {{ $o->payment_method }}
              </span>
            </td>
            <td class="px-4 py-3 text-right font-bold">৳{{ number_format($o->total) }}</td>
            <td class="px-4 py-3 text-center">
              @include('admin.partials.status-badge', ['status' => $o->status])
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="py-14 text-center text-charcoal/40">
              <div class="text-3xl mb-2">📭</div>
              কোনো অর্ডার নেই
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection