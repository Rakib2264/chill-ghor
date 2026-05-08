<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1c0f09">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <title>@yield('title', 'চিল ঘর — চা–কফির আড্ডা, ফাস্ট ফুডের আসল স্বাদ')</title>

    <meta name="description" content="@yield('description', 'চিল ঘর — বনগ্রাম স্কুল ও কলেজের সামনে, চা–কফি, কাচ্চি, ফুচকা, ফাস্টফুড। গ্রামীণ পরিবেশে শহরের আধুনিক ফিল।')">

    <meta name="keywords" content="@yield('keywords', 'রেস্তোরাঁ, চা, কফি, ফাস্ট ফুড, কাচ্চি, ফুচকা, বিরিয়ানি, বনগ্রাম')">
    <meta name="author" content="চিল ঘর">

    {{-- ✅ Open Graph (Facebook) --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'চিল ঘর — সেরা খাবারের অভিজ্ঞতা')">
    <meta property="og:image" content="@yield('og_image', url('images/logo/logo-removebg-preview.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="চিল ঘর">

    {{-- ✅ Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description')">
    <meta name="twitter:image" content="@yield('twitter_image', url('images/logo/logo-removebg-preview.png'))">

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-removebg-preview.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700;800&family=Tiro+Bangla:ital@0;1&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#c0392b',
                            glow: '#e8671a',
                            soft: '#fff1ec'
                        },
                        spice: {
                            DEFAULT: '#f0a020',
                            soft: '#fff7e6'
                        },
                        cream: '#faf6ef',
                        charcoal: '#2a1d18',
                        ink: '#1c0f09',
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'sans-serif'],
                        display: ['Tiro Bangla', 'serif'],
                        latin: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    boxShadow: {
                        warm: '0 10px 30px -10px rgba(192,57,43,0.30)',
                        soft: '0 4px 16px -4px rgba(61,45,40,0.10)',
                        glow: '0 20px 60px -20px rgba(232,103,26,0.45)',
                        ring: '0 0 0 1px rgba(42,29,24,0.06), 0 2px 8px rgba(42,29,24,0.04)',
                    },
                    backgroundImage: {
                        'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)',
                        'gradient-ember': 'linear-gradient(135deg, #c0392b 0%, #e8671a 50%, #f5a623 100%)',
                        'gradient-night': 'linear-gradient(160deg, #1c0f09 0%, #3d1f10 60%, #5a2912 100%)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.4s ease-out forwards',
                        'slide-up': 'slideUp 0.5s cubic-bezier(.4,0,.2,1) forwards',
                        'slide-down': 'slideDown 0.4s cubic-bezier(.4,0,.2,1) forwards',
                        'pop-in': 'popIn 0.45s cubic-bezier(.34,1.56,.64,1) forwards',
                        'shimmer': 'shimmer 2.5s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'marquee': 'marquee 28s linear infinite',
                        'spin-slow': 'spin 18s linear infinite',
                    },
                    keyframes: {
                        fadeIn: { from: { opacity: '0' }, to: { opacity: '1' } },
                        slideUp: { from: { opacity: '0', transform: 'translateY(18px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                        slideDown: { from: { opacity: '0', transform: 'translateY(-12px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                        popIn: { '0%': { opacity: '0', transform: 'scale(.92)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        marquee: { from: { transform: 'translateX(0)' }, to: { transform: 'translateX(-50%)' } },
                    },
                },
            },
        };
    </script>

    <style>
        :root {
            --c-primary: #c0392b;
            --c-glow: #e8671a;
            --c-spice: #f0a020;
            --c-cream: #faf6ef;
            --c-charcoal: #2a1d18;
            --c-ink: #1c0f09;
        }

        html { -webkit-text-size-adjust: 100%; }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background:
                radial-gradient(900px 600px at 8% -10%, rgba(232,103,26,0.06), transparent 60%),
                radial-gradient(700px 500px at 100% 20%, rgba(245,166,35,0.05), transparent 65%),
                #faf6ef;
            color: #2a1d18;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        ::selection { background: rgba(192, 57, 43, 0.18); color: #1c0f09; }

        .font-display { font-family: 'Tiro Bangla', serif; letter-spacing: -0.01em; }

        .gradient-text {
            background: linear-gradient(135deg, #c0392b, #e8671a 55%, #f5a623);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* glass + grain helpers */
        .glass {
            background: rgba(250, 246, 239, 0.72);
            backdrop-filter: saturate(180%) blur(16px);
            -webkit-backdrop-filter: saturate(180%) blur(16px);
            border: 1px solid rgba(42, 29, 24, 0.06);
        }
        .glass-dark {
            background: rgba(28, 15, 9, 0.55);
            backdrop-filter: saturate(180%) blur(18px);
            -webkit-backdrop-filter: saturate(180%) blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .grain {
            position: relative;
        }
        .grain::after {
            content: \"\";
            pointer-events: none;
            position: absolute; inset: 0;
            background-image: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 0.55 0'/></filter><rect width='100%' height='100%' filter='url(%23n)' opacity='0.45'/></svg>\");
            opacity: .055;
            mix-blend-mode: overlay;
            border-radius: inherit;
        }

        /* scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #d8c8b6, #c8b29c);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #c0392b; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { scrollbar-width: none; -ms-overflow-style: none; }

        [x-cloak] { display: none !important; }

        /* Reveal on scroll */
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .6s ease, transform .6s cubic-bezier(.4,0,.2,1); }
        .reveal.in { opacity: 1; transform: translateY(0); }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #c0392b 0%, #e8671a 100%);
            color: #fff;
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 16px 30px -12px rgba(192,57,43,.45); filter: brightness(1.04); }
        .btn-primary:active { transform: translateY(0) scale(.98); }

        /* Focus ring */
        :focus-visible { outline: 2px solid #c0392b; outline-offset: 3px; border-radius: 6px; }

        /* Tap highlight off on iOS */
        a, button { -webkit-tap-highlight-color: transparent; }

        /* Scroll progress bar (top) */
        .scroll-progress {
            position: fixed; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #c0392b, #e8671a, #f5a623);
            transform-origin: 0 50%; transform: scaleX(0);
            z-index: 60; pointer-events: none;
        }

        /* Hide scrollbar utility */
        .no-scroll-x { overflow-x: hidden; }

        /* Ticker / Marquee */
        .marquee-track { display: flex; gap: 3rem; width: max-content; animation: marquee 28s linear infinite; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* Card hover lift */
        .lift { transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s ease; }
        .lift:hover { transform: translateY(-4px); box-shadow: 0 22px 40px -22px rgba(28,15,9,0.25); }

        /* Image zoom on hover (use with overflow-hidden parent) */
        .zoom-img { transition: transform .7s cubic-bezier(.22,1,.36,1); }
        .group:hover .zoom-img { transform: scale(1.08); }

        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #efe6d8 25%, #f8f1e2 37%, #efe6d8 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s linear infinite;
        }

        /* Reduce motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
            .reveal { opacity: 1; transform: none; }
        }

        /* Bengali serif headlines feel */
        h1, h2, h3 { letter-spacing: -0.005em; }

        /* Safer mobile padding (bottom nav clearance) */
        .pb-mobile-nav { padding-bottom: calc(5.25rem + env(safe-area-inset-bottom)); }

        /* Chip */
        .chip {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .5rem .9rem; border-radius: 9999px;
            background: #fff; border: 1px solid rgba(42,29,24,.10);
            font-weight: 700; font-size: .75rem; transition: all .2s ease;
        }
        .chip:hover { border-color: rgba(192,57,43,.4); transform: translateY(-1px); }
        .chip-active { background: linear-gradient(135deg,#c0392b,#e8671a); color: #fff; border-color: transparent; box-shadow: 0 8px 18px -8px rgba(192,57,43,.5); }
    </style>

    @stack('head')
</head>

<body class="min-h-screen flex flex-col">

    {{-- Scroll Progress --}}
    <div id="scrollProgress" class="scroll-progress"></div>

    @include('partials.promo-bar')
    @include('partials.navbar')

    <main class="flex-1 pb-mobile-nav md:pb-0">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.toast')

    <script>
        // Scroll progress bar
        (function () {
            const bar = document.getElementById('scrollProgress');
            if (!bar) return;
            let ticking = false;
            const update = () => {
                const h = document.documentElement;
                const max = h.scrollHeight - h.clientHeight;
                const sc = max > 0 ? h.scrollTop / max : 0;
                bar.style.transform = `scaleX(${sc})`;
                ticking = false;
            };
            window.addEventListener('scroll', () => {
                if (!ticking) { requestAnimationFrame(update); ticking = true; }
            }, { passive: true });
            update();
        })();

        // Reveal-on-scroll observer
        (function () {
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('.reveal').forEach(el => el.classList.add('in'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('in');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });
            const observe = () => document.querySelectorAll('.reveal:not(.in)').forEach(el => io.observe(el));
            observe();
            // Re-observe when Alpine renders dynamic content
            document.addEventListener('DOMContentLoaded', observe);
        })();
    </script>

    @stack('scripts')
</body>

</html>