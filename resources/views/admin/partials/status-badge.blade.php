@php
  $map = [
    'pending'   => ['⏳ বকেয়া',     'bg-amber-100 text-amber-700'],
    'confirmed' => ['✅ কনফার্মড',   'bg-blue-100 text-blue-700'],
    'preparing' => ['👨‍🍳 রান্নায়',    'bg-orange-100 text-orange-700'],
    'delivered' => ['🚚 ডেলিভার্ড',  'bg-emerald-100 text-emerald-700'],
    'cancelled' => ['❌ বাতিল',      'bg-red-100 text-red-600'],
  ];
  [$label, $cls] = $map[$status] ?? [$status, 'bg-charcoal/10 text-charcoal/60'];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $cls }}">
  {{ $label }}
</span>