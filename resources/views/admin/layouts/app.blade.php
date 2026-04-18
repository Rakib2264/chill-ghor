<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'অ্যাডমিন') — চিল ঘর</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla:ital@0;1&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#c0392b',
                            glow: '#e8671a'
                        },
                        spice: '#f0a020',
                        cream: '#faf6ef',
                        charcoal: '#2a1d18',
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'sans-serif'],
                        display: ['Tiro Bangla', 'serif'],
                    },
                    boxShadow: {
                        warm: '0 8px 24px -8px rgba(192,57,43,0.32)',
                        soft: '0 2px 12px -2px rgba(61,45,40,0.10)',
                    },
                    backgroundImage: {
                        'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: #f0ece6;
            color: #2a1d18;
        }

        .font-display {
            font-family: 'Tiro Bangla', serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #c0392b, #e8671a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cdc0b0;
            border-radius: 99px;
        }
    </style>
</head>

<body class="min-h-screen" x-data="{ sidebar: false }">

    {{-- ===== SIDEBAR ===== --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col border-r border-white/10 transition-transform duration-300 lg:translate-x-0"
        style="background: linear-gradient(175deg, #1a0d0a 0%, #2a1d18 50%, #3d2d28 100%);"
        :class="sidebar && '!translate-x-0'">

        {{-- Brand --}}
        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            <div
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-warm shadow-warm text-lg">
                <img src="{{ asset('images/logo/logo.png') }}" alt=""
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span style="display:none">☕</span>
            </div>
            <div>
                <div class="font-display font-bold text-[#f5ddd5] leading-tight">চিল ঘর</div>
                <div class="text-[9px] uppercase tracking-widest text-white/35 mt-0.5">Admin Panel</div>
            </div>
        </div>

        {{-- Nav --}}
        @php
            $nav = [
                ['route' => 'admin.dashboard', 'label' => 'ড্যাশবোর্ড', 'icon' => '📊'],
                ['route' => 'admin.pos.index', 'label' => 'POS', 'icon' => '💳'],
                ['route' => 'admin.orders.index', 'label' => 'অর্ডার', 'icon' => '🧾'],
                ['route' => 'admin.products.index', 'label' => 'পণ্য', 'icon' => '🍽️'],
                ['route' => 'admin.categories.index', 'label' => 'ক্যাটাগরি', 'icon' => '🏷️'],
            ];
        @endphp

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <p class="mb-2 px-2 text-[9px] font-bold uppercase tracking-widest text-white/30">মেইন মেনু</p>
            @foreach ($nav as $n)
                <a href="{{ route($n['route']) }}"
                    class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                  {{ request()->routeIs($n['route']) ? 'bg-gradient-warm text-white shadow-warm' : 'text-white/55 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">{{ $n['icon'] }}</span>
                    {{ $n['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User --}}
        <div class="border-t border-white/10 p-3">
            <div class="flex items-center gap-3 rounded-xl bg-white/7 px-3 py-2.5">
                <div
                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-warm text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-[#f5ddd5]">{{ auth()->user()->name ?? 'Admin' }}
                    </div>
                    <div class="text-[10px] text-white/35">Owner</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-white/40 transition hover:bg-white/10 hover:text-white"
                        title="Logout">
                        ↪
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div x-show="sidebar" @click="sidebar = false" x-cloak
        class="fixed inset-0 z-30 bg-charcoal/50 backdrop-blur-sm lg:hidden"></div>

    {{-- ===== MAIN ===== --}}
    <div class="lg:pl-64">

        {{-- Topbar --}}
        <header
            class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-charcoal/10 bg-white/85 px-4 backdrop-blur-md sm:px-6">
            <button @click="sidebar = !sidebar"
                class="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-charcoal/8 lg:hidden transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="font-display text-lg font-bold">@yield('header', 'অ্যাডমিন')</h1>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('home') }}"
                    class="rounded-full border border-charcoal/15 bg-white px-4 py-1.5 text-xs font-bold transition hover:border-primary hover:text-primary">
                    ↗ সাইট দেখুন
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main class="p-4 sm:p-6 lg:p-8">

            {{-- Toast --}}
            @if (session('toast'))
                <div x-data="{ s: true }" x-init="setTimeout(() => s = false, 3500)" x-show="s" x-transition.opacity x-cloak
                    class="mb-4 rounded-xl border border-primary/20 bg-primary/8 px-4 py-3 text-sm font-medium text-primary">
                    {{ session('toast') }}
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-primary/25 bg-primary/8 p-4 text-sm text-primary">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>
