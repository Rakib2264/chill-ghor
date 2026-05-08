<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1c0f09">
    <title>লগইন — চিল ঘর</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-removebg-preview.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700;800&family=Tiro+Bangla&family=Plus+Jakarta+Sans:wght@600;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#c0392b',
                        spice: '#f0a020',
                        cream: '#faf6ef',
                        charcoal: '#2a1d18',
                        ink: '#1c0f09'
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'sans-serif'],
                        display: ['Tiro Bangla', 'serif'],
                        latin: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    backgroundImage: {
                        'gradient-warm': 'linear-gradient(135deg, #c0392b 0%, #e8671a 100%)'
                    },
                    boxShadow: {
                        warm: '0 14px 40px -10px rgba(192,57,43,0.40)',
                        glow: '0 30px 80px -25px rgba(232,103,26,0.55)'
                    }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .font-display {
            font-family: 'Tiro Bangla', serif;
        }

        .font-latin {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :focus-visible {
            outline: 2px solid #c0392b;
            outline-offset: 3px;
            border-radius: 6px;
        }
    </style>
</head>

<body class="min-h-screen bg-cream">

    <div class="grid min-h-screen lg:grid-cols-2">

        {{-- Left visual panel (desktop only) --}}
        <div class="relative hidden overflow-hidden lg:block"
            style="background: linear-gradient(160deg, #1c0f09 0%, #2a1812 60%, #3d2010 100%);">
            <div class="absolute -top-20 -left-10 h-80 w-80 rounded-full opacity-30 blur-3xl"
                style="background: radial-gradient(circle,#e8671a,transparent 60%);"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full opacity-25 blur-3xl"
                style="background: radial-gradient(circle,#f0a020,transparent 60%);"></div>
            <div class="absolute inset-0 opacity-[0.05]"
                style="background-image: radial-gradient(rgba(255,255,255,.5) 1px, transparent 1px); background-size: 22px 22px;">
            </div>

            <div class="relative flex h-full flex-col justify-between p-12 text-white">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 w-fit">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-warm overflow-hidden ring-1 ring-white/15"
                        style="background: linear-gradient(135deg,#c0392b,#e8671a);">
                        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt=""
                            class="h-8 w-8 object-contain"
                            onerror="this.style.display='none';this.parentElement.innerHTML='☕'">
                    </div>
                    <div>
                        <div class="font-display text-2xl font-bold">চিল ঘর</div>
                        <div class="text-[10px] uppercase tracking-[0.22em] text-cream/55 mt-1 font-latin">CHILL · GHOR
                        </div>
                    </div>
                </a>

                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-spice mb-4">
                        <span class="inline-block h-px w-8 align-middle bg-spice/60 mr-2"></span> স্বাগতম
                    </p>
                    <h2 class="font-display text-4xl font-black leading-tight xl:text-5xl">
                        ঘরের স্বাদ,<br>
                        <span class="text-spice">আপনার দরজায়।</span>
                    </h2>
                    <p class="mt-4 max-w-md text-sm text-cream/65 leading-relaxed">
                        লগইন করে পান দ্রুত চেকআউট, অর্ডার ট্র্যাক এবং বিশেষ ডিসকাউন্ট।
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-3 max-w-md">
                        <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10 backdrop-blur-sm">
                            <i class="fa-solid fa-truck-fast text-spice text-lg"></i>
                            <div class="mt-2 text-sm font-black">দ্রুত ডেলিভারি</div>
                            <div class="text-[11px] text-cream/55">২০-৩০ মিনিটে</div>
                        </div>
                        <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10 backdrop-blur-sm">
                            <i class="fa-solid fa-percent text-spice text-lg"></i>
                            <div class="mt-2 text-sm font-black">সদস্য ছাড়</div>
                            <div class="text-[11px] text-cream/55">প্রতি অর্ডারে</div>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-cream/40">© {{ date('Y') }} চিল ঘর — বনগ্রাম, বাংলাদেশ</div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">

                {{-- Mobile logo --}}
                <div class="mb-6 flex items-center justify-center gap-3 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-warm overflow-hidden"
                        style="background: linear-gradient(135deg,#c0392b,#e8671a);">
                        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt=""
                            class="h-8 w-8 object-contain"
                            onerror="this.style.display='none';this.parentElement.innerHTML='☕'">
                    </div>
                    <div class="text-left">
                        <div class="font-display text-2xl font-bold">চিল ঘর</div>
                        <div class="text-[10px] uppercase tracking-[0.22em] text-charcoal/55 mt-0.5 font-latin">CHILL ·
                            GHOR</div>
                    </div>
                </div>

                <div class="rounded-3xl border border-charcoal/8 bg-white p-7 sm:p-9 shadow-glow">
                    <h1 class="font-display text-3xl font-black">স্বাগতম 👋</h1>
                    <p class="mt-2 text-sm text-charcoal/60">আপনার অ্যাকাউন্টে লগইন করুন</p>

                    @if ($errors->any())
                        <div
                            class="mt-5 flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4" x-data="{ show: false }">
                        @csrf
                        <label class="block">
                            <span class="text-[11px] font-black uppercase tracking-wider text-charcoal/65">ইমেইল</span>
                            <div class="relative mt-1.5">
                                <span
                                    class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/40">
                                    <i class="fa-regular fa-envelope text-[13px]"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="you@example.com"
                                    class="w-full rounded-xl border border-charcoal/12 bg-cream/50 px-4 py-3 pl-11 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 transition">
                            </div>
                        </label>

                        <label class="block">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[11px] font-black uppercase tracking-wider text-charcoal/65">পাসওয়ার্ড</span>
                            </div>
                            <div class="relative mt-1.5">
                                <span
                                    class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/40">
                                    <i class="fa-solid fa-lock text-[13px]"></i>
                                </span>
                                <input :type="show ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="w-full rounded-xl border border-charcoal/12 bg-cream/50 px-4 py-3 pl-11 pr-11 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 transition">
                                <button type="button" @click="show=!show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-lg text-charcoal/45 hover:text-primary">
                                    <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fa-regular text-[13px]"></i>
                                </button>
                            </div>
                        </label>

                        <label class="flex items-center gap-2 text-xs font-bold text-charcoal/65 select-none">
                            <input type="checkbox" name="remember" class="h-4 w-4 accent-primary">
                            আমাকে মনে রাখুন
                        </label>

                        <button type="submit"
                            class="group inline-flex w-full items-center justify-center gap-2 rounded-full py-3.5 text-sm font-black text-white shadow-warm transition hover:-translate-y-0.5 hover:shadow-glow active:scale-[.98]"
                            style="background: linear-gradient(135deg,#c0392b,#e8671a);">
                            লগইন করুন
                            <i
                                class="fa-solid fa-arrow-right text-[12px] transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </form>

                    <div class="my-6 flex items-center gap-3">
                        <div class="h-px flex-1 bg-charcoal/10"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-charcoal/40">অথবা</span>
                        <div class="h-px flex-1 bg-charcoal/10"></div>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-charcoal/65">
                            অ্যাকাউন্ট নেই?
                            <a href="{{ route('register') }}"
                                class="font-black text-primary hover:underline">রেজিস্ট্রেশন করুন</a>
                        </p>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-charcoal/55 hover:text-primary transition">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> মূল সাইটে ফিরে যান
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
