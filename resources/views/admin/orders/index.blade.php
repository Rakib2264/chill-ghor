@extends('admin.layouts.app')
@section('title', 'অর্ডার')
@section('header', 'অর্ডার ম্যানেজমেন্ট')

@section('content')
<div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft">
  <div class="flex flex-wrap items-center gap-3 border-b border-charcoal/10 p-4">
    <form method="GET" class="flex flex-1 flex-wrap gap-2 min-w-[280px]">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="ইনভয়েস / ফোন / নাম..."
             class="flex-1 rounded-full border border-charcoal/15 bg-cream px-4 py-2 text-sm focus:border-primary focus:outline-none">
      <button class="rounded-full bg-charcoal px-5 text-sm font-bold text-cream">খুঁজুন</button>
    </form>
    <div class="flex flex-wrap gap-1">
      @foreach ($statuses as $s)
        <a href="{{ route('admin.orders.index', ['status' => $s, 'q' => request('q')]) }}"
           class="rounded-full px-3 py-1.5 text-xs font-bold uppercase tracking-wider transition
                  {{ (request('status', 'all') === $s) ? 'bg-primary text-white shadow-warm' : 'bg-charcoal/5 hover:bg-charcoal/10' }}">
          {{ $s }}
        </a>
      @endforeach
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
        <tr>
          <th class="py-3 px-4 text-left">ইনভয়েস</th>
          <th class="text-left">কাস্টমার</th>
          <th class="text-left">সময়</th>
          <th class="text-left">পেমেন্ট</th>
          <th class="text-right">মোট</th>
          <th class="text-center">স্ট্যাটাস</th>
          <th class="text-right pr-4">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $o)
          <tr class="border-t border-charcoal/5 hover:bg-cream/50">
            <td class="py-3 px-4 font-mono font-bold text-primary">{{ $o->invoice_no }}</td>
            <td>
              <div class="font-bold">{{ $o->customer_name }}</div>
              <div class="text-xs text-charcoal/50">{{ $o->phone }}</div>
            </td>
            <td class="text-charcoal/60">{{ $o->created_at->format('d M, h:i A') }}</td>
            <td>
              <span class="rounded-full bg-charcoal/5 px-2 py-0.5 text-[10px] font-bold uppercase">{{ $o->payment_method }}</span>
              @if ($o->trx_id)<div class="text-[10px] text-charcoal/50 mt-1">{{ $o->trx_id }}</div>@endif
            </td>
            <td class="text-right font-bold">৳{{ number_format($o->total) }}</td>
            <td class="text-center">@include('admin.partials.status-badge', ['status' => $o->status])</td>
            <td class="py-3 pr-4 text-right">
              <a href="{{ route('admin.orders.show', $o) }}" class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">দেখুন →</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="py-12 text-center text-charcoal/50">কোনো অর্ডার নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="border-t border-charcoal/10 p-4">{{ $orders->links() }}</div>
</div>
@endsection
