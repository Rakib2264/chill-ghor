@extends('layouts.app')
@section('title', 'আমার প্রোফাইল — চিল ঘর')

@push('styles')
    <style>
        /* ── Spinner ── */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 0.7s linear infinite;
        }

        /* ── Avatar ring ── */
        @keyframes ring-rotate {
            to {
                transform: rotate(360deg);
            }
        }

        .avatar-ring-wrap {
            position: relative;
            width: 96px;
            height: 96px;
            border-radius: 9999px;
            padding: 3px;
            background: conic-gradient(#e74c3c, #e67e22, #f1c40f, #e74c3c);
            animation: ring-rotate 4s linear infinite;
        }

        .avatar-ring-inner {
            border-radius: 9999px;
            background: white;
            padding: 3px;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .avatar-ring-inner img {
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            object-fit: cover;
            display: block;
            transition: opacity 0.3s;
        }

        /* ── Slide up entrance ── */
        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-enter {
            animation: slide-up 0.45s ease both;
        }

        .delay-1 {
            animation-delay: 0.05s;
        }

        .delay-2 {
            animation-delay: 0.12s;
        }

        .delay-3 {
            animation-delay: 0.19s;
        }

        .delay-4 {
            animation-delay: 0.26s;
        }

        /* ── Input focus ── */
        .inp {
            width: 100%;
            border-radius: 14px;
            border: 1.5px solid rgba(0, 0, 0, 0.09);
            background: #faf9f7;
            padding: 11px 16px;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }

        .inp:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.10);
            background: #fff;
        }

        /* ── Stat pill ── */
        .stat-pill {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: default;
        }

        .stat-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.09);
        }

        /* ── Order row ── */
        .order-row {
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }

        .order-row:hover {
            border-color: #fca5a5;
            background: #fff5f5;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.08);
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto max-w-6xl px-4 pb-14 sm:px-6 lg:px-8">

        {{-- ── Tabs ── --}}
        <div class="pt-6">
            @include('pages.profile._tabs', ['active' => 'profile'])
        </div>

        {{-- ── Hero Banner ── --}}
        <div class="mt-5 rounded-3xl p-7 text-white shadow-xl relative overflow-hidden"
            style="background: linear-gradient(135deg, #c0392b 0%, #e67e22 60%, #f39c12 100%);">
            {{-- decorative dots --}}
            <div class="pointer-events-none absolute inset-0"
                style="background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
                background-size: 22px 22px;">
            </div>
            {{-- decorative circle blobs --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full"
                style="background: rgba(255,255,255,0.07);"></div>
            <div class="pointer-events-none absolute -bottom-8 right-24 h-32 w-32 rounded-full"
                style="background: rgba(255,255,255,0.05);"></div>

            <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.75);">
                        প্রোফাইল</p>
                    <h1 class="mt-1 font-display text-2xl font-bold text-white sm:text-3xl"
                        style="text-shadow: 0 1px 4px rgba(0,0,0,0.2);">
                        আমার অ্যাকাউন্ট
                    </h1>
                    <p class="mt-1 text-sm" style="color: rgba(255,255,255,0.8);">স্বাগতম, {{ $user->name }} 👋</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full px-5 py-2.5 text-sm font-bold text-white transition"
                        style="background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.35); backdrop-filter: blur(4px);"
                        onmouseover="this.style.background='rgba(255,255,255,0.28)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.18)'">
                        লগআউট →
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Main Grid ── --}}
        <div class="mt-7 grid gap-6 lg:grid-cols-[300px,1fr]">

            {{-- ────────────── Sidebar ────────────── --}}
            <div class="space-y-5">

                {{-- Profile Card --}}
                <div class="card-enter rounded-3xl bg-white shadow-md overflow-hidden">

                    {{-- Card top gradient strip --}}
                    <div class="h-20 bg-gradient-to-r from-red-500 via-orange-400 to-amber-400 relative">
                        <div class="absolute inset-0 opacity-20"
                            style="background-image: radial-gradient(circle at 70% 50%, white 1px, transparent 1px); background-size: 18px 18px;">
                        </div>
                    </div>

                    {{-- Avatar — overlaps the strip --}}
                    <div class="flex flex-col items-center -mt-14 px-6 pb-6">
                        <div class="avatar-ring relative">
                            <div class="avatar-ring-inner">
                                <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                    class="h-24 w-24 rounded-full object-cover transition-opacity duration-300">
                            </div>
                            <label for="avatar-input" id="avatar-label" title="ছবি পরিবর্তন"
                                class="absolute bottom-1 right-1 flex h-7 w-7 cursor-pointer items-center justify-center
                          rounded-full bg-gradient-to-br from-red-500 to-orange-400 text-white shadow-lg
                          hover:scale-110 transition-transform select-none z-10">
                                <span id="avatar-icon" class="text-xs">📷</span>
                                <span id="avatar-spinner" class="hidden">
                                    <svg class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                </span>
                            </label>
                        </div>
                        <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/gif,image/webp"
                            class="hidden">

                        <h2 class="mt-3 font-display text-xl font-bold text-charcoal">{{ $user->name }}</h2>
                        <p class="text-xs text-charcoal/55 mt-0.5">{{ $user->email }}</p>
                        @if ($user->phone)
                            <p class="text-xs text-charcoal/55">{{ $user->phone }}</p>
                        @endif

                        {{-- Stats ── 3 pills ── --}}
                        <div class="mt-5 grid w-full grid-cols-3 gap-2">
                            <div class="stat-pill rounded-2xl bg-red-50 p-3 text-center border border-red-100">
                                <div class="text-lg font-bold text-red-500">{{ $totalOrders }}</div>
                                <div class="text-[10px] font-semibold text-red-400 mt-0.5">অর্ডার</div>
                            </div>
                            <div class="stat-pill rounded-2xl bg-orange-50 p-3 text-center border border-orange-100">
                                <div class="text-lg font-bold text-orange-500">৳{{ number_format($totalSpent) }}</div>
                                <div class="text-[10px] font-semibold text-orange-400 mt-0.5">খরচ</div>
                            </div>
                            <div class="stat-pill rounded-2xl bg-amber-50 p-3 text-center border border-amber-100">
                                <div class="text-lg font-bold text-amber-500">{{ $user->created_at->format('Y') }}</div>
                                <div class="text-[10px] font-semibold text-amber-400 mt-0.5">যোগদান</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Card --}}
                <div class="card-enter rounded-3xl bg-white p-2 shadow-md">
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}"
                            class="nav-active flex items-center gap-3 rounded-2xl bg-gradient-to-r from-red-500 to-orange-400 px-4 py-3 text-sm font-bold text-white shadow-sm">
                            👤 প্রোফাইল
                        </a>
                        <a href="{{ route('profile.orders') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-charcoal/65 hover:bg-charcoal/5 hover:text-charcoal transition">
                            📋 অর্ডার হিস্টরি
                            @if ($totalOrders > 0)
                                <span
                                    class="ml-auto rounded-full bg-charcoal/8 px-2 py-0.5 text-[10px] font-bold text-charcoal/60">{{ $totalOrders }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.addresses.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-charcoal/65 hover:bg-charcoal/5 hover:text-charcoal transition">
                            📍 আমার ঠিকানা
                        </a>
                    </nav>
                </div>

                {{-- Member Badge --}}
                <div class="card-enter rounded-3xl bg-gradient-to-br from-amber-400 to-orange-500 p-5 text-white shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/20 text-2xl">⭐</div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-wider text-white/70">সদস্যপদ</div>
                            <div class="font-display font-bold">নিয়মিত গ্রাহক</div>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-white/70">{{ $user->created_at->diffForHumans() }} থেকে চিল ঘর পরিবারের অংশ
                    </p>
                </div>
            </div>

            {{-- ────────────── Main Content ────────────── --}}
            <div class="space-y-5">

                {{-- Edit Profile ── --}}
                <div class="card-enter rounded-3xl bg-white p-7 shadow-md">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-red-50 text-lg">✏️</div>
                        <div>
                            <h3 class="font-display text-lg font-bold">প্রোফাইল এডিট</h3>
                            <p class="text-xs text-charcoal/50">আপনার তথ্য আপডেট করুন</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">নাম</span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                            </label>
                            <label class="block">
                                <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">ইমেইল</span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">ফোন</span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="+880 1XXX-XXXXXX"
                                class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">ঠিকানা</span>
                            <textarea name="address" rows="3" placeholder="আপনার ঠিকানা লিখুন..."
                                class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm resize-none">{{ old('address', $user->address) }}</textarea>
                        </label>

                        <button type="submit"
                            class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-500 to-orange-400 px-7 py-3 text-sm font-bold text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
                            💾 আপডেট করুন
                        </button>
                    </form>
                </div>

                {{-- Change Password ── --}}
                <div class="card-enter rounded-3xl bg-white p-7 shadow-md">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-orange-50 text-lg">🔐</div>
                        <div>
                            <h3 class="font-display text-lg font-bold">পাসওয়ার্ড পরিবর্তন</h3>
                            <p class="text-xs text-charcoal/50">নিরাপদ থাকতে মাঝে মাঝে পরিবর্তন করুন</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <label class="block">
                            <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">বর্তমান
                                পাসওয়ার্ড</span>
                            <input type="password" name="current_password" required
                                class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                        </label>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">নতুন
                                    পাসওয়ার্ড</span>
                                <input type="password" name="password" required
                                    class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                            </label>
                            <label class="block">
                                <span class="text-xs font-bold text-charcoal/60 uppercase tracking-wide">নিশ্চিত
                                    করুন</span>
                                <input type="password" name="password_confirmation" required
                                    class="input-fancy mt-1.5 w-full rounded-2xl border border-charcoal/12 bg-stone-50 px-4 py-3 text-sm">
                            </label>
                        </div>

                        <button type="submit"
                            class="flex items-center gap-2 rounded-2xl border-2 border-orange-400 bg-white px-7 py-3 text-sm font-bold text-orange-500 hover:bg-gradient-to-r hover:from-red-500 hover:to-orange-400 hover:text-white hover:border-transparent transition-all">
                            🔐 পাসওয়ার্ড পরিবর্তন
                        </button>
                    </form>
                </div>

                {{-- Recent Orders ── --}}
                <div class="card-enter rounded-3xl bg-white p-7 shadow-md">
                    <div class="mb-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-50 text-lg">📦</div>
                            <div>
                                <h3 class="font-display text-lg font-bold">সাম্প্রতিক অর্ডার</h3>
                                <p class="text-xs text-charcoal/50">শেষ {{ $recentOrders->count() }}টি অর্ডার</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.orders') }}"
                            class="rounded-full bg-charcoal/5 px-4 py-1.5 text-xs font-bold text-charcoal/70 hover:bg-red-50 hover:text-red-500 transition">
                            সব দেখুন →
                        </a>
                    </div>

                    @if ($recentOrders->isEmpty())
                        <div class="rounded-2xl border-2 border-dashed border-charcoal/10 py-12 text-center">
                            <div class="text-4xl mb-2">📋</div>
                            <p class="text-sm text-charcoal/50">এখনো কোনো অর্ডার নেই</p>
                            <a href="{{ route('menu.index') }}"
                                class="mt-3 inline-block rounded-full bg-gradient-to-r from-red-500 to-orange-400 px-5 py-2 text-xs font-bold text-white">
                                মেনু দেখুন →
                            </a>
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach ($recentOrders as $order)
                                <a href="{{ route('profile.orders.show', $order) }}"
                                    class="group flex items-center justify-between rounded-2xl border border-charcoal/8 bg-stone-50 p-4
                        hover:border-red-200 hover:bg-red-50/40 hover:shadow-sm transition-all">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm text-base group-hover:scale-110 transition-transform">
                                            📦
                                        </div>
                                        <div>
                                            <div class="font-mono text-sm font-bold text-red-500">{{ $order->invoice_no }}
                                            </div>
                                            <div class="text-[11px] text-charcoal/50 mt-0.5">
                                                {{ $order->created_at->format('d M Y, h:i A') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-sm">৳{{ number_format($order->total) }}</div>
                                        <div class="mt-1">
                                            @include('admin.partials.status-badge', [
                                                'status' => $order->status,
                                            ])
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ── Toast Container ── --}}
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2 pointer-events-none"></div>

    @push('scripts')
        <script>
            (function() {
                /* ── Toast ── */
                function showToast(message, type = 'success') {
                    const c = document.getElementById('toast-container');
                    const t = document.createElement('div');
                    const colors = {
                        success: 'from-red-500 to-orange-400 text-white',
                        error: 'from-red-700 to-red-500 text-white',
                    };
                    t.className = `pointer-events-auto flex items-center gap-2.5 rounded-2xl bg-gradient-to-r
      px-5 py-3 text-sm font-bold shadow-xl translate-y-4 opacity-0 transition-all duration-300
      ${colors[type] ?? colors.success}`;
                    t.textContent = message;
                    c.appendChild(t);
                    requestAnimationFrame(() => requestAnimationFrame(() => {
                        t.classList.remove('translate-y-4', 'opacity-0');
                    }));
                    setTimeout(() => {
                        t.classList.add('translate-y-4', 'opacity-0');
                        setTimeout(() => t.remove(), 300);
                    }, 3000);
                }

                /* ── Avatar AJAX ── */
                const input = document.getElementById('avatar-input');
                const preview = document.getElementById('avatar-preview');
                const label = document.getElementById('avatar-label');
                const icon = document.getElementById('avatar-icon');
                const spinner = document.getElementById('avatar-spinner');
                if (!input) return;

                input.addEventListener('change', async function() {
                    const file = this.files[0];
                    if (!file) return;

                    /* ১. Instant local preview */
                    const reader = new FileReader();
                    reader.onload = e => preview.src = e.target.result;
                    reader.readAsDataURL(file);

                    /* ২. Loading state */
                    icon.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    label.classList.add('pointer-events-none', 'opacity-70');
                    preview.classList.add('opacity-50');

                    /* ৩. Upload */
                    const fd = new FormData();
                    fd.append('avatar', file);
                    fd.append('_token', '{{ csrf_token() }}');

                    try {
                        const res = await fetch('{{ route('profile.avatar') }}', {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                        });
                        const data = await res.json();
                        if (res.ok && data.success) {
                            preview.src = data.avatar_url + '?t=' + Date.now();
                            showToast(data.message ?? '✅ ছবি আপডেট হয়েছে', 'success');
                        } else {
                            const msg = data.errors?.avatar?.[0] ?? data.message ?? '❌ আপলোড ব্যর্থ';
                            showToast(msg, 'error');
                            preview.src = '{{ $user->avatar_url }}';
                        }
                    } catch {
                        showToast('❌ নেটওয়ার্ক সমস্যা, আবার চেষ্টা করুন', 'error');
                        preview.src = '{{ $user->avatar_url }}';
                    } finally {
                        icon.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        label.classList.remove('pointer-events-none', 'opacity-70');
                        preview.classList.remove('opacity-50');
                        input.value = '';
                    }
                });
            })();
        </script>
    @endpush

@endsection
