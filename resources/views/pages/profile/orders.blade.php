@extends('layouts.app')
@section('title', 'অর্ডার হিস্টরি — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  @include('pages.profile._tabs', ['active' => 'orders'])


  <div class="mb-8">
    <p class="text-xs font-bold uppercase tracking-widest text-primary">অর্ডার</p>
    <h1 class="mt-1 font-display text-3xl font-bold">আমার অর্ডার হিস্টরি</h1>
  </div>

  <div class="flex flex-wrap items-center gap-4 mb-6">
    <a href="{{ route('profile.index') }}" class="text-sm font-bold text-charcoal/60 hover:text-primary flex items-center gap-1">
      ← প্রোফাইলে ফিরুন
    </a>
  </div>

  @if($orders->isEmpty())
    <div class="rounded-3xl border border-dashed border-charcoal/20 bg-white p-16 text-center">
      <div class="text-6xl mb-4">📋</div>
      <h2 class="font-display text-2xl font-bold mb-2">কোনো অর্ডার নেই</h2>
      <p class="text-charcoal/60 mb-6">আপনি এখনো কোনো অর্ডার করেননি</p>
      <a href="{{ route('menu.index') }}" class="inline-flex rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm">
        মেনু দেখুন →
      </a>
    </div>
  @else
    <div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
            <tr>
              <th class="py-4 px-5 text-left">ইনভয়েস</th>
              <th class="text-left">তারিখ</th>
              <th class="text-left">পেমেন্ট</th>
              <th class="text-right">মোট</th>
              <th class="text-center">স্ট্যাটাস</th>
              <th class="text-right pr-5">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
              <tr class="border-t border-charcoal/5 hover:bg-cream/50">
                <td class="py-4 px-5">
                  <span class="font-mono font-bold text-primary">{{ $order->invoice_no }}</span>
                </td>
                <td class="text-charcoal/60">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                <td>
                  <span class="rounded-full bg-charcoal/5 px-2.5 py-1 text-[10px] font-bold uppercase">
                    {{ $order->payment_method }}
                  </span>
                </td>
                <td class="text-right font-bold">৳{{ number_format($order->total) }}</td>
                <td class="text-center">
                  @include('admin.partials.status-badge', ['status' => $order->status])
                </td>
                <td class="py-4 pr-5 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('profile.orders.show', $order) }}" 
                       class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">
                      দেখুন
                    </a>
                    <a href="{{ route('profile.orders.print', $order) }}" target="_blank"
                       class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">
                      🖨️ প্রিন্ট
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <div class="border-t border-charcoal/10 p-4">
        {{ $orders->links() }}
      </div>
    </div>
  @endif

</div>
@endsection