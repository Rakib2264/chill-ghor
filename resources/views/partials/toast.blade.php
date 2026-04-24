{{-- Toast Message - Always Bottom Right --}}
@if (session('toast'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2 translate-x-full"
        x-transition:enter-end="opacity-100 translate-y-0 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-full" x-cloak
        class="fixed bottom-4 right-4 z-50 rounded-full bg-charcoal px-5 py-2.5 text-sm font-semibold text-cream shadow-warm max-w-[calc(100%-2rem)]">
        {{ session('toast') }}
    </div>
@endif

{{-- Error Toast - Always Bottom Right --}}
@if ($errors->any())
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2 translate-x-full"
        x-transition:enter-end="opacity-100 translate-y-0 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-full" x-cloak
        class="fixed bottom-4 right-4 z-50 rounded-2xl bg-primary px-5 py-3 text-sm text-white shadow-warm max-w-[calc(100%-2rem)] w-80">
        <ul class="space-y-1">
            @foreach ($errors->all() as $err)
                <li class="flex items-start gap-2"><span>⚠️</span> {{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
