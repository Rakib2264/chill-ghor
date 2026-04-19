@extends('admin.layouts.app')
@section('title', 'ড্যাশবোর্ড')
@section('header', 'ড্যাশবোর্ড')

@section('content')

{{-- ==== Welcome banner ==== --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-dark p-6 sm:p-8 mb-6 shadow-warm">
  <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary/20 blur-3xl"></div>
  <div class="absolute -left-10 -bottom-20 h-56 w-56 rounded-full bg-spice/15 blur-3xl"></div>
  <div class="relative flex flex-wrap items-center justify-between gap-4">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-spice">{{ now()->format('l, M d Y') }}</p>
      <h2 class="mt-2 font-display text-2xl sm:text-3xl font-bold text-white">স্বাগতম, {{ auth()->user()->name }} 👋</h2>
      <p class="mt-1 text-sm text-white/65">আজকের পরিসংখ্যান ও সব কিছু এক নজরে।</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.products.create') }}" class="rounded-full bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105 transition">+ নতুন পণ্য</a>
      <a href="{{ route('admin.orders.index') }}" class="rounded-full border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-bold text-white backdrop-blur hover:bg-white/20 transition">অর্ডার দেখুন</a>
    </div>
  </div>
</div>

{{-- ==== Stat cards ==== --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
  @php
    $cards = [
      ['আজকের বিক্রয়',   '৳' . number_format($stats['sales_today']),   '💰', 'from-emerald-500 to-teal-500'],
      ['আজকের অর্ডার',    $stats['orders_today'],                       '🧾', 'from-primary to-primary-glow'],
      ['পেন্ডিং অর্ডার',  $stats['orders_pending'],                     '⏳', 'from-amber-500 to-orange-500'],
      ['মাসিক বিক্রয়',   '৳' . number_format($stats['sales_month']),   '📈', 'from-sky-500 to-indigo-500'],
    ];
  @endphp
  @foreach ($cards as [$label, $val, $icon, $grad])
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-soft hover:shadow-warm transition">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-charcoal/55">{{ $label }}</p>
          <p class="mt-2 font-display text-3xl font-bold text-charcoal">{{ $val }}</p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $grad }} text-2xl shadow-warm stat-pulse">{{ $icon }}</div>
      </div>
      <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r {{ $grad }} opacity-60 group-hover:opacity-100"></div>
    </div>
  @endforeach
</div>

{{-- ==== Mini stats ==== --}}
<div class="grid gap-3 grid-cols-2 lg:grid-cols-4 mb-6">
  @foreach ([
    ['মোট অর্ডার', $stats['orders_total'], '🧾'],
    ['মোট পণ্য', $stats['products_total'], '🍽️'],
    ['ক্যাটাগরি', $stats['categories'], '🏷️'],
    ['ব্যবহারকারী', $stats['users_total'], '👥'],
  ] as [$l, $v, $i])
    <div class="flex items-center gap-3 rounded-xl border border-charcoal/8 bg-white px-4 py-3 shadow-soft">
      <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-base">{{ $i }}</div>
      <div>
        <div class="text-[10px] font-bold uppercase tracking-wider text-charcoal/50">{{ $l }}</div>
        <div class="font-display text-lg font-bold">{{ $v }}</div>
      </div>
    </div>
  @endforeach
</div>

{{-- ==== Charts row ==== --}}
<div class="grid gap-4 lg:grid-cols-3 mb-6">

  {{-- Sales chart (2 cols) --}}
  <div class="lg:col-span-2 rounded-2xl bg-white p-5 shadow-soft">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="font-display text-lg font-bold">📈 বিক্রয় ট্রেন্ড</h3>
        <p class="text-xs text-charcoal/55">গত ১৪ দিন</p>
      </div>
      <div class="text-right">
        <div class="text-[10px] font-bold uppercase tracking-wider text-charcoal/50">মোট বিক্রয়</div>
        <div class="font-display text-xl font-bold text-primary">৳{{ number_format($stats['sales_total']) }}</div>
      </div>
    </div>
    <div class="h-72"><canvas id="salesChart"></canvas></div>
  </div>

  {{-- Status pie --}}
  <div class="rounded-2xl bg-white p-5 shadow-soft">
    <h3 class="font-display text-lg font-bold mb-4">🥧 অর্ডার স্ট্যাটাস</h3>
    <div class="h-56"><canvas id="statusChart"></canvas></div>
  </div>
</div>

<div class="grid gap-4 lg:grid-cols-3 mb-6">
  {{-- Top products --}}
  <div class="lg:col-span-2 rounded-2xl bg-white p-5 shadow-soft">
    <h3 class="font-display text-lg font-bold mb-4">🏆 সেরা পণ্য (পরিমাণ অনুসারে)</h3>
    @if ($topProducts->isEmpty())
      <p class="text-sm text-charcoal/55 py-8 text-center">কোনো অর্ডার নেই</p>
    @else
      <div class="space-y-3">
        @php $maxQty = $topProducts->max('qty') ?: 1; @endphp
        @foreach ($topProducts as $p)
          <div>
            <div class="flex justify-between text-sm mb-1">
              <span class="font-semibold truncate">{{ $p->product_name }}</span>
              <span class="text-charcoal/60">{{ $p->qty }} টি · ৳{{ number_format($p->revenue) }}</span>
            </div>
            <div class="h-2 rounded-full bg-cream overflow-hidden">
              <div class="h-full bg-gradient-warm rounded-full" style="width: {{ ($p->qty / $maxQty) * 100 }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Payment split --}}
  <div class="rounded-2xl bg-white p-5 shadow-soft">
    <h3 class="font-display text-lg font-bold mb-4">💳 পেমেন্ট পদ্ধতি</h3>
    <div class="h-56"><canvas id="paymentChart"></canvas></div>
  </div>
</div>

{{-- ==== Recent orders ==== --}}
<div class="rounded-2xl bg-white p-5 shadow-soft">
  <div class="flex items-center justify-between mb-4">
    <h3 class="font-display text-lg font-bold">🆕 সাম্প্রতিক অর্ডার</h3>
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-primary hover:underline">সব দেখুন →</a>
  </div>
  @if ($recent->isEmpty())
    <p class="text-sm text-charcoal/55 py-8 text-center">কোনো অর্ডার নেই</p>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs font-bold uppercase tracking-wider text-charcoal/55">
          <tr class="border-b border-charcoal/10">
            <th class="text-left py-2.5">ইনভয়েস</th>
            <th class="text-left">গ্রাহক</th>
            <th class="text-left">পদ্ধতি</th>
            <th class="text-right">টোটাল</th>
            <th class="text-center">স্ট্যাটাস</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($recent as $o)
            <tr class="border-b border-charcoal/5 hover:bg-cream/40">
              <td class="py-3 font-mono font-bold text-primary">{{ $o->invoice_no }}</td>
              <td>{{ $o->customer_name }}</td>
              <td class="uppercase text-xs">{{ $o->payment_method }}</td>
              <td class="text-right font-bold">৳{{ number_format($o->total) }}</td>
              <td class="text-center">@include('admin.partials.status-badge', ['status' => $o->status])</td>
              <td class="text-right"><a href="{{ route('admin.orders.show', $o) }}" class="text-xs font-bold text-primary hover:underline">দেখুন</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const cream = '#faf6ef', charcoal = '#2a1d18';
  const grad = (ctx, c1, c2) => {
    const g = ctx.createLinearGradient(0, 0, 0, 280);
    g.addColorStop(0, c1); g.addColorStop(1, c2); return g;
  };

  // Sales line chart
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: @json($salesByDay->pluck('label')),
      datasets: [{
        label: 'বিক্রয় (৳)',
        data: @json($salesByDay->pluck('total')),
        borderColor: '#c0392b',
        backgroundColor: grad(salesCtx, 'rgba(192,57,43,0.35)', 'rgba(232,103,26,0.02)'),
        tension: 0.4, fill: true, pointBackgroundColor: '#e8671a',
        pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: '#0001' }, ticks: { color: charcoal+'99' } },
        x: { grid: { display: false }, ticks: { color: charcoal+'99' } }
      }
    }
  });

  // Status pie
  const statusLabels = { pending:'পেন্ডিং', confirmed:'নিশ্চিত', preparing:'প্রস্তুত হচ্ছে', out_for_delivery:'ডেলিভারিতে', delivered:'ডেলিভার্ড', cancelled:'বাতিল' };
  const statusColors = ['#f59e0b','#3b82f6','#8b5cf6','#06b6d4','#10b981','#ef4444'];
  const sd = @json($statusData);
  new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
      labels: sd.map(s => statusLabels[s.status] || s.status),
      datasets: [{ data: sd.map(s => s.count), backgroundColor: statusColors, borderWidth: 0 }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '65%',
      plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 8, boxWidth: 10 } } }
    }
  });

  // Payment pie
  const pm = @json($paymentSplit);
  const pmLabels = { cod:'ক্যাশ অন ডেলিভারি', bkash:'bKash', nagad:'Nagad' };
  new Chart(document.getElementById('paymentChart'), {
    type: 'pie',
    data: {
      labels: Object.keys(pm).map(k => pmLabels[k] || k),
      datasets: [{ data: Object.values(pm), backgroundColor: ['#10b981','#ec4899','#f59e0b'], borderWidth: 0 }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 8, boxWidth: 10 } } }
    }
  });
});
</script>
@endpush

@endsection
