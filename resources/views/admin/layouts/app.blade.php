<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'অ্যাডমিন') — চিল ঘর</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary:  { DEFAULT: '#c0392b', glow: '#e8671a' },
            spice:    '#f0a020',
            cream:    '#faf6ef',
            charcoal: '#2a1d18',
          },
          fontFamily: {
            sans:    ['Hind Siliguri', 'sans-serif'],
            display: ['Tiro Bangla', 'serif'],
          },
          boxShadow: {
            warm: '0 10px 30px -10px rgba(192,57,43,0.35)',
            soft: '0 4px 16px -4px rgba(61,45,40,0.12)',
          },
          backgroundImage: {
            'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)',
          },
        },
      },
    };
  </script>
  <style>
    body { font-family: 'Hind Siliguri', sans-serif; background: #f5f2ee; color: #2a1d18; }
    .font-display { font-family: 'Tiro Bangla', serif; }
    .gradient-text { background: linear-gradient(135deg, #c0392b, #e8671a); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="min-h-screen" x-data="{ sidebar: false }">

  {{-- Sidebar --}}
  <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-charcoal/10 bg-white transition-transform lg:translate-x-0"
         :class="sidebar && '!translate-x-0'">
    <div class="flex h-16 items-center gap-2.5 border-b border-charcoal/10 px-5">
      <div class="flex h-10 w-10 items-center justify-center rounded-xl text-lg shadow-warm">
        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt="">
      </div>
      <div>
        <div class="font-display font-bold leading-none">চিল ঘর</div>
        <div class="text-[10px] uppercase tracking-widest text-charcoal/50">Admin Panel</div>
      </div>
    </div>

    @php
      $nav = [
        ['route' => 'admin.dashboard',       'label' => 'ড্যাশবোর্ড',  'icon' => '📊'],
        ['route' => 'admin.orders.index',    'label' => 'অর্ডার',       'icon' => '🧾'],
        ['route' => 'admin.products.index',  'label' => 'পণ্য',         'icon' => '🍽️'],
        ['route' => 'admin.categories.index','label' => 'ক্যাটাগরি',    'icon' => '🏷️'],
      ];
    @endphp
    <nav class="grid gap-1 p-3">
      @foreach ($nav as $n)
        <a href="{{ route($n['route']) }}"
           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                  {{ request()->routeIs($n['route']) ? 'bg-gradient-warm text-white shadow-warm' : 'hover:bg-charcoal/5' }}">
          <span class="text-lg">{{ $n['icon'] }}</span> {{ $n['label'] }}
        </a>
      @endforeach
    </nav>

    <div class="absolute inset-x-3 bottom-3 rounded-xl border border-charcoal/10 bg-cream p-3">
      <div class="flex items-center gap-2.5">
        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-charcoal text-cream text-sm font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <div class="min-w-0 flex-1">
          <div class="truncate text-sm font-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
          <div class="text-[10px] text-charcoal/60">Owner</div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-charcoal/60 hover:bg-primary/10 hover:text-primary" title="Logout">↪</button>
        </form>
      </div>
    </div>
  </aside>

  {{-- Mobile overlay --}}
  <div x-show="sidebar" @click="sidebar = false" x-cloak class="fixed inset-0 z-30 bg-charcoal/40 lg:hidden"></div>

  {{-- Main --}}
  <div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-charcoal/10 bg-white/80 px-4 backdrop-blur sm:px-6">
      <button @click="sidebar = !sidebar" class="flex h-9 w-9 items-center justify-center rounded-lg hover:bg-charcoal/5 lg:hidden">☰</button>
      <h1 class="font-display text-lg font-bold">@yield('header', 'অ্যাডমিন')</h1>
      <div class="ml-auto flex items-center gap-2">
        <a href="{{ route('home') }}" class="rounded-full border border-charcoal/15 bg-white px-4 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">↗ সাইট দেখুন</a>
      </div>
    </header>

    <main class="p-4 sm:p-6 lg:p-8">
      @if (session('toast'))
        <div x-data="{ s: true }" x-init="setTimeout(() => s = false, 3500)" x-show="s" x-transition.opacity
             class="mb-4 rounded-xl border border-primary/20 bg-primary/5 px-4 py-3 text-sm text-primary">
          {{ session('toast') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="mb-4 rounded-xl border border-primary/30 bg-primary/5 p-4 text-sm text-primary">
          <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</body>
</html>
