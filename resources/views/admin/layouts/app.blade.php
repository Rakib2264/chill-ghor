{{-- resources/views/admin/layouts/app.blade.php --}}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

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
                        glow: '0 0 30px rgba(232,103,26,0.25)',
                    },
                    backgroundImage: {
                        'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)',
                        'gradient-dark': 'linear-gradient(170deg, #1a0d0a 0%, #2a1d18 60%, #3d2d28 100%)',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: linear-gradient(180deg, #f4ede2, #ebe2d2);
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
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c5b8a6;
            border-radius: 99px;
        }

        .stat-pulse {
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: .85
            }

            50% {
                opacity: 1
            }
        }
    </style>
</head>

<body class="min-h-screen" x-data="{ sidebar: false }">

    {{-- ===== SIDEBAR ===== --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col border-r border-white/5 bg-gradient-dark transition-transform duration-300 lg:translate-x-0"
        :class="sidebar && '!translate-x-0'">

        <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-warm shadow-warm text-lg">☕
            </div>
            <div>
                <div class="font-display font-bold text-[#f5ddd5] leading-tight">চিল ঘর</div>
                <div class="text-[9px] uppercase tracking-widest text-white/35 mt-0.5">চিল ঘর</div>
            </div>
        </div>

        @php
            $nav = [
                ['route' => 'admin.dashboard', 'label' => 'ড্যাশবোর্ড', 'icon' => '📊'],
                ['route' => 'admin.orders.index', 'label' => 'অর্ডার', 'icon' => '🧾'],
                ['route' => 'admin.products.index', 'label' => 'পণ্য', 'icon' => '🍽️'],
                ['route' => 'admin.categories.index', 'label' => 'ক্যাটাগরি', 'icon' => '🏷️'],
                ['route' => 'admin.users.index', 'label' => 'ব্যবহারকারী', 'icon' => '👥'],
                ['route' => 'admin.settings.index', 'label' => 'সেটিংস', 'icon' => '⚙️'],
                ['route' => 'admin.contacts.index', 'label' => 'বার্তা', 'icon' => '✉️'],
                ['route' => 'admin.delivery-zones.index', 'label' => 'ডেলিভারি জোন', 'icon' => '🚚'],
            ];
        @endphp

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <p class="mb-2 px-2 text-[9px] font-bold uppercase tracking-widest text-white/30">মেইন মেনু</p>
            @foreach ($nav as $n)
                <a href="{{ route($n['route']) }}"
                    class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                  {{ request()->routeIs($n['route']) ? 'bg-gradient-warm text-white shadow-warm' : 'text-white/60 hover:bg-white/8 hover:text-white' }}">
                    <span class="text-base w-5 text-center">{{ $n['icon'] }}</span>
                    {{ $n['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-3">
            <div class="flex items-center gap-3 rounded-xl bg-white/5 px-3 py-2.5">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-warm text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-[#f5ddd5]">{{ auth()->user()->name ?? 'Admin' }}
                    </div>
                    <div class="text-[10px] text-white/35">Administrator</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-white/40 hover:bg-white/10 hover:text-white transition"
                        title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div x-show="sidebar" @click="sidebar = false" x-cloak
        class="fixed inset-0 z-30 bg-charcoal/50 backdrop-blur-sm lg:hidden"></div>

    <div class="lg:pl-64">
        <header
            class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-charcoal/10 bg-white/80 px-4 backdrop-blur-md sm:px-6">
            <button @click="sidebar = !sidebar"
                class="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-charcoal/8 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="font-display text-lg font-bold">@yield('header', 'অ্যাডমিন প্যানেল')</h1>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank"
                    class="rounded-full border border-charcoal/15 bg-white px-4 py-1.5 text-xs font-bold hover:border-primary hover:text-primary transition">
                    🌐 সাইট দেখুন
                </a>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            @if (session('toast'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)" x-show="show" x-transition.opacity x-cloak
                    class="mb-4 rounded-xl border border-primary/20 bg-primary/8 px-4 py-3 text-sm font-medium text-primary backdrop-blur-sm">
                    {{ session('toast') }}
                </div>
            @endif

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

    @stack('scripts')
</body>

</html>
