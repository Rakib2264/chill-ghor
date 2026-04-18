@php
  $map = [
    'pending'   => ['বকেয়া', 'bg-spice/15 text-spice'],
    'confirmed' => ['কনফার্মড', 'bg-blue-100 text-blue-700'],
    'preparing' => ['রান্নায়', 'bg-amber-100 text-amber-700'],
    'delivered' => ['ডেলিভার্ড', 'bg-emerald-100 text-emerald-700'],
    'cancelled' => ['বাতিল', 'bg-red-100 text-red-700'],
  ];
  [$label, $cls] = $map[$status] ?? [$status, 'bg-charcoal/10'];
@endphp
<span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $cls }}">{{ $label }}</span>
