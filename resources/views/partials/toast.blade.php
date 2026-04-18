@if (session('toast'))
  <div x-data="{ show: true }"
       x-init="setTimeout(() => show = false, 3500)"
       x-show="show"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-end="opacity-0"
       x-cloak
       class="fixed left-1/2 top-5 z-50 -translate-x-1/2 rounded-full bg-charcoal px-6 py-3 text-sm font-semibold text-cream shadow-warm">
    {{ session('toast') }}
  </div>
@endif

@if ($errors->any())
  <div x-data="{ show: true }"
       x-init="setTimeout(() => show = false, 5000)"
       x-show="show"
       x-transition.opacity
       x-cloak
       class="fixed left-1/2 top-5 z-50 -translate-x-1/2 w-full max-w-sm rounded-2xl bg-primary px-5 py-3.5 text-sm text-white shadow-warm">
    <ul class="space-y-1">
      @foreach ($errors->all() as $err)
        <li class="flex items-start gap-2"><span>⚠️</span> {{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif