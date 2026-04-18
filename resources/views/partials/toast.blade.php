@if (session('toast'))
  <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition.opacity
       class="fixed left-1/2 top-5 z-50 -translate-x-1/2 rounded-full bg-charcoal px-5 py-2.5 text-sm font-medium text-cream shadow-warm">
    {{ session('toast') }}
  </div>
@endif

@if ($errors->any())
  <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity
       class="fixed left-1/2 top-5 z-50 -translate-x-1/2 rounded-2xl bg-primary px-5 py-3 text-sm text-white shadow-warm">
    <ul class="space-y-1">
      @foreach ($errors->all() as $err)
        <li>⚠️ {{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif
