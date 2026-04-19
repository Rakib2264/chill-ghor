@php
  $tabs = [
    ['key'=>'profile',   'label'=>'প্রোফাইল',  'icon'=>'👤', 'route'=>'profile.index'],
    ['key'=>'addresses', 'label'=>'ঠিকানা',     'icon'=>'📍', 'route'=>'profile.addresses.index'],
    ['key'=>'orders',    'label'=>'অর্ডার',     'icon'=>'📦', 'route'=>'profile.orders'],
  ];
@endphp
<div class="flex gap-1 overflow-x-auto rounded-2xl bg-white p-1.5 shadow-soft border border-charcoal/8">
  @foreach ($tabs as $t)
    <a href="{{ route($t['route']) }}"
       class="flex-1 whitespace-nowrap rounded-xl px-4 py-2.5 text-center text-sm font-bold transition
              {{ ($active ?? '') === $t['key'] ? 'bg-gradient-warm text-white shadow-warm' : 'text-charcoal/65 hover:bg-cream' }}">
      {{ $t['icon'] }} {{ $t['label'] }}
    </a>
  @endforeach
</div>
