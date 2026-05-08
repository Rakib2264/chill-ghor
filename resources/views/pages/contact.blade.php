@extends('layouts.app')
@php
    use App\Models\Setting;

    function getSetting($key, $default = null)
    {
        $value = Setting::get($key, $default);
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            $t = trim($value);
            if (str_starts_with($t, '[') || str_starts_with($t, '{')) {
                $d = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $d;
                }
            }
        }
        return $value;
    }

    $pageTitle = getSetting('contact_title', 'যোগাযোগ — চিল ঘর');
    $pageBadge = getSetting('contact_badge', 'যোগাযোগ');
    $pageHeading = getSetting('contact_heading', 'আমাদের সাথে কথা বলুন');
    $pageSubheading = getSetting(
        'contact_subheading',
        'অর্ডার, ক্যাটারিং, ফিডব্যাক — যেকোনো প্রয়োজনে আমাদের জানান। সাধারণত ২ ঘণ্টার মধ্যে সাড়া দিই।',
    );

    $contactAddress = getSetting('contact_address', 'বনগ্রাম স্কুল ও কলেজের সামনে');
    $contactPhone = getSetting('contact_phone', '+৮৮০ ১৭১১-০০০০০০');
    $contactEmail = getSetting('contact_email', 'hello@chillghor.com');
    $openingHours = getSetting('opening_hours', 'সকাল ৭টা – রাত ৯টা');
    $openingHoursFri = getSetting('opening_hours_friday', 'দুপুর ১টা – রাত ৯টা');

    $socialFacebook = getSetting('social_facebook', '#');
    $socialInstagram = getSetting('social_instagram', '#');
    $socialYoutube = getSetting('social_youtube', '#');

    $formTitle = getSetting('contact_form_title', 'বার্তা পাঠান');
    $nameLabel = getSetting('contact_name_label', 'আপনার নাম');
    $emailLabel = getSetting('contact_email_label', 'ইমেইল');
    $msgLabel = getSetting('contact_message_label', 'আপনার বার্তা');
    $btnText = getSetting('contact_button_text', 'বার্তা পাঠান');

    $subjects = getSetting('contact_subjects', [
        ['icon' => 'fa-bag-shopping', 'label' => 'অর্ডার'],
        ['icon' => 'fa-calendar-check', 'label' => 'ক্যাটারিং'],
        ['icon' => 'fa-star', 'label' => 'ফিডব্যাক'],
        ['icon' => 'fa-circle-question', 'label' => 'অন্যান্য'],
    ]);
    if (!is_array($subjects)) {
        $subjects = [
            ['icon' => 'fa-bag-shopping', 'label' => 'অর্ডার'],
            ['icon' => 'fa-calendar-check', 'label' => 'ক্যাটারিং'],
            ['icon' => 'fa-star', 'label' => 'ফিডব্যাক'],
            ['icon' => 'fa-circle-question', 'label' => 'অন্যান্য'],
        ];
    }

    $rawPhone = preg_replace('/[^0-9+]/', '', $contactPhone);
    $waNumber = ltrim($rawPhone, '+');
@endphp

@section('title', is_string($pageTitle) ? $pageTitle : 'যোগাযোগ — চিল ঘর')
@section('description', 'চিল ঘর রেস্টুরেন্টে অর্ডার, ক্যাটারিং, ফিডব্যাক বা যেকোনো তথ্যের জন্য আমাদের সাথে যোগাযোগ
    করুন।')

    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <style>
            .cg-info-card {
                transition: border-color .15s, box-shadow .15s;
            }

            .cg-info-card:hover {
                border-color: rgba(192, 57, 43, .25) !important;
                box-shadow: 0 4px 20px -6px rgba(192, 57, 43, .12);
            }

            .cg-action-btn {
                transition: background .15s, color .15s, border-color .15s;
            }

            .cg-action-btn:hover {
                background: rgba(192, 57, 43, .09) !important;
                color: #c0392b !important;
                border-color: rgba(192, 57, 43, .22) !important;
            }

            .cg-soc {
                transition: background .15s, border-color .15s, color .15s;
            }

            .cg-soc-fb:hover {
                background: #1877f2 !important;
                border-color: #1877f2 !important;
                color: #fff !important;
            }

            .cg-soc-ig:hover {
                background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888) !important;
                border-color: transparent !important;
                color: #fff !important;
            }

            .cg-soc-yt:hover {
                background: #ff0000 !important;
                border-color: #ff0000 !important;
                color: #fff !important;
            }

            .cg-chip {
                transition: background .15s, border-color .15s, color .15s;
                cursor: pointer;
            }

            .cg-chip.active,
            .cg-chip:hover {
                background: rgba(192, 57, 43, .08) !important;
                border-color: rgba(192, 57, 43, .28) !important;
                color: #c0392b !important;
            }

            .cg-alt-btn {
                transition: background .15s, border-color .15s, color .15s;
            }

            .cg-alt-btn:hover {
                background: rgba(192, 57, 43, .05) !important;
                border-color: rgba(192, 57, 43, .25) !important;
                color: #c0392b !important;
            }

            .cg-input:focus,
            .cg-textarea:focus {
                border-color: #c0392b !important;
                box-shadow: 0 0 0 3px rgba(192, 57, 43, .1) !important;
                background: #fff !important;
                outline: none !important;
            }

            @keyframes cgpulse {

                0%,
                100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: .3;
                    transform: scale(.8);
                }
            }

            .cg-open-dot {
                animation: cgpulse 1.6s ease-in-out infinite;
            }
        </style>
    @endpush

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:py-16 lg:px-8">

        {{-- Success message --}}
        @if (session('contact_success'))
            <div
                class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                <i class="fa-solid fa-circle-check text-lg"></i>
                {{ session('contact_success') }}
            </div>
        @endif

        {{-- Page header --}}
        <div class="mb-10">
            <span class="mb-4 inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-xs font-bold"
                style="background:rgba(192,57,43,.07);border-color:rgba(192,57,43,.2);color:#c0392b;">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                {{ $pageBadge }}
            </span>
            <h1 class="font-display mt-3 text-3xl font-bold sm:text-4xl lg:text-5xl">
                {{ $pageHeading }}
            </h1>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-charcoal/55 sm:text-base">
                {{ $pageSubheading }}
            </p>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1fr_430px] lg:items-start">

            {{-- ===== LEFT: Info cards ===== --}}
            <div class="space-y-3">

                {{-- Address --}}
                <div
                    class="cg-info-card flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl"
                        style="background:rgba(192,57,43,.09);color:#c0392b;font-size:17px;">
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="mb-0.5 text-[10px] font-bold uppercase tracking-wider text-charcoal/40">ঠিকানা</div>
                        <div class="text-sm font-bold">{{ $contactAddress }}</div>
                    </div>
                </div>

                {{-- Phone --}}
                <div
                    class="cg-info-card flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl"
                        style="background:rgba(217,119,6,.09);color:#b45309;font-size:17px;">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="mb-0.5 text-[10px] font-bold uppercase tracking-wider text-charcoal/40">ফোন</div>
                        <a href="tel:{{ $rawPhone }}"
                            class="text-sm font-bold hover:text-primary transition">{{ $contactPhone }}</a>
                    </div>
                    <a href="tel:{{ $rawPhone }}"
                        class="cg-action-btn flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl border border-charcoal/10 bg-cream text-charcoal/40 text-sm">
                        <i class="fa-solid fa-phone-flip" aria-hidden="true"></i>
                    </a>
                </div>

                {{-- Email --}}
                <div
                    class="cg-info-card flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl"
                        style="background:rgba(37,99,235,.08);color:#1d4ed8;font-size:17px;">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="mb-0.5 text-[10px] font-bold uppercase tracking-wider text-charcoal/40">ইমেইল</div>
                        <a href="mailto:{{ $contactEmail }}"
                            class="text-sm font-bold hover:text-primary transition">{{ $contactEmail }}</a>
                    </div>
                    <a href="mailto:{{ $contactEmail }}"
                        class="cg-action-btn flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl border border-charcoal/10 bg-cream text-charcoal/40 text-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                    </a>
                </div>

                {{-- Social --}}
                <div class="rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">
                    <div class="mb-3 text-[10px] font-bold uppercase tracking-wider text-charcoal/40">সোশ্যাল মিডিয়া</div>
                    <div class="flex gap-2">
                        <a href="{{ $socialFacebook !== '#' ? $socialFacebook : '#' }}"
                            @if ($socialFacebook !== '#') target="_blank" rel="noopener noreferrer" @endif
                            class="cg-soc cg-soc-fb flex h-10 w-10 items-center justify-center rounded-xl border border-charcoal/10 bg-cream text-charcoal/55 text-base"
                            aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                        </a>
                        <a href="{{ $socialInstagram !== '#' ? $socialInstagram : '#' }}"
                            @if ($socialInstagram !== '#') target="_blank" rel="noopener noreferrer" @endif
                            class="cg-soc cg-soc-ig flex h-10 w-10 items-center justify-center rounded-xl border border-charcoal/10 bg-cream text-charcoal/55 text-base"
                            aria-label="Instagram">
                            <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="{{ $socialYoutube !== '#' ? $socialYoutube : '#' }}"
                            @if ($socialYoutube !== '#') target="_blank" rel="noopener noreferrer" @endif
                            class="cg-soc cg-soc-yt flex h-10 w-10 items-center justify-center rounded-xl border border-charcoal/10 bg-cream text-charcoal/55 text-base"
                            aria-label="YouTube">
                            <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                {{-- Opening hours --}}
                <div class="rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft">
                    <div class="mb-3 flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-charcoal/40">
                            <i class="fa-regular fa-clock" style="font-size:14px;" aria-hidden="true"></i>
                            খোলার সময়
                        </div>
                        <div class="flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold"
                            style="background:rgba(22,163,74,.1);color:#15803d;">
                            <span class="cg-open-dot inline-block h-1.5 w-1.5 rounded-full"
                                style="background:#22c55e;"></span>
                            এখন খোলা
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between border-b border-charcoal/8 pb-2 text-sm">
                            <span class="text-charcoal/55">শনি — বৃহস্পতি</span>
                            <span class="font-bold">{{ $openingHours }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-charcoal/55">শুক্রবার</span>
                            <span class="font-bold">{{ $openingHoursFri }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== RIGHT: Form ===== --}}
            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">

                <div class="mb-5 flex items-start justify-between">
                    <div>
                        <h2 class="font-display text-xl font-bold">{{ $formTitle }}</h2>
                        <p class="mt-1 text-xs text-charcoal/45">সাধারণত ২ ঘণ্টায় উত্তর দেওয়া হয়</p>
                    </div>
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-base"
                        style="background:rgba(192,57,43,.08);border:0.5px solid rgba(192,57,43,.15);color:#c0392b;">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    </div>
                </div>

                {{-- Subject chips --}}
                <div class="mb-4">
                    <div class="mb-2 text-xs font-bold text-charcoal/45">বিষয় বেছে নিন</div>
                    <div class="flex flex-wrap gap-2" id="cg-chips">
                        @foreach ($subjects as $i => $subject)
                            <button type="button" data-subject="{{ $subject['label'] ?? '' }}"
                                class="cg-chip inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-xs font-bold {{ $i === 0 ? 'active' : '' }}"
                                style="{{ $i === 0
                                    ? 'background:rgba(192,57,43,.08);border-color:rgba(192,57,43,.28);color:#c0392b;'
                                    : 'background:#faf6ef;border-color:rgba(42,29,24,.1);color:rgba(42,29,24,.55);' }}">
                                <i class="fa-solid {{ $subject['icon'] ?? 'fa-tag' }}" style="font-size:11px;"
                                    aria-hidden="true"></i>
                                {{ $subject['label'] ?? '' }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="subject" id="cg-subject-val"
                        value="{{ $subjects[0]['label'] ?? 'অর্ডার' }}">

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-charcoal/50">
                                {{ $nameLabel }} <span class="text-primary">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="নাম লিখুন"
                                class="cg-input w-full rounded-xl border border-charcoal/15 bg-cream px-3.5 py-2.5 text-sm transition">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-bold text-charcoal/50">ফোন</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="০১XXXXXXXXX"
                                class="cg-input w-full rounded-xl border border-charcoal/15 bg-cream px-3.5 py-2.5 text-sm transition">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-charcoal/50">
                            {{ $emailLabel }} <span class="text-primary">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="your@email.com"
                            class="cg-input w-full rounded-xl border border-charcoal/15 bg-cream px-3.5 py-2.5 text-sm transition">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-charcoal/50">
                            {{ $msgLabel }} <span class="text-primary">*</span>
                        </label>
                        <textarea name="message" rows="4" required placeholder="বিস্তারিত লিখুন..."
                            class="cg-textarea w-full resize-none rounded-xl border border-charcoal/15 bg-cream px-3.5 py-2.5 text-sm transition">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-full py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02] hover:shadow-[0_12px_30px_-8px_rgba(192,57,43,.45)]"
                        style="background:linear-gradient(135deg,#c0392b,#e8671a);">
                        <i class="fa-solid fa-paper-plane" style="font-size:13px;" aria-hidden="true"></i>
                        {{ $btnText }}
                    </button>

                    <div class="flex items-center gap-3 py-1">
                        <div class="h-px flex-1 bg-charcoal/10"></div>
                        <span class="text-xs font-bold text-charcoal/35">অথবা সরাসরি</span>
                        <div class="h-px flex-1 bg-charcoal/10"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="tel:{{ $rawPhone }}"
                            class="cg-alt-btn flex items-center justify-center gap-2 rounded-xl border border-charcoal/10 bg-cream py-3 text-xs font-bold text-charcoal/55 transition">
                            <i class="fa-solid fa-phone-volume" style="font-size:13px;" aria-hidden="true"></i>
                            সরাসরি কল
                        </a>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer"
                            class="cg-alt-btn flex items-center justify-center gap-2 rounded-xl border border-charcoal/10 bg-cream py-3 text-xs font-bold text-charcoal/55 transition">
                            <i class="fa-brands fa-whatsapp" style="font-size:15px;color:#25d366;"
                                aria-hidden="true"></i>
                            WhatsApp
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            var chips = document.querySelectorAll('#cg-chips .cg-chip');
            var input = document.getElementById('cg-subject-val');
            chips.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    chips.forEach(function(b) {
                        b.classList.remove('active');
                        b.style.background = '#faf6ef';
                        b.style.borderColor = 'rgba(42,29,24,.1)';
                        b.style.color = 'rgba(42,29,24,.55)';
                    });
                    this.classList.add('active');
                    this.style.background = 'rgba(192,57,43,.08)';
                    this.style.borderColor = 'rgba(192,57,43,.28)';
                    this.style.color = '#c0392b';
                    if (input) input.value = this.dataset.subject;
                });
            });
        })();
    </script>
@endpush
