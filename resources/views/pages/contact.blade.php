@extends('layouts.app')
@php
    use App\Models\Setting;

    function getSetting($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    $pageTitle = getSetting('contact_title', 'যোগাযোগ — চিল ঘর');
    $pageBadge = getSetting('contact_badge', 'যোগাযোগ');
    $pageHeading = getSetting('contact_heading', 'আমাদের সাথে কথা বলুন');
    $pageSubheading = getSetting('contact_subheading', 'অর্ডার, ক্যাটারিং, ফিডব্যাক — যেকোনো প্রয়োজনে আমাদের জানান।');

    // ✅ Contact Info Cards - FIXED (json_decode আর needed নেই)
    $contactInfo = getSetting('contact_info_cards', null);

    // Check if already an array
    if (!is_array($contactInfo) || empty($contactInfo)) {
        // Default contact info
        $contactInfo = [
            ['📍', 'ঠিকানা', getSetting('contact_address', 'বনগ্রাম স্কুল ও কলেজের সামনে')],
            ['📞', 'ফোন', getSetting('contact_phone', '+৮৮০ ১৭১১-০০০০০০')],
            ['✉️', 'ইমেইল', getSetting('contact_email', 'hello@chillghor.com')],
            ['🕐', 'খোলার সময়', getSetting('opening_hours', 'প্রতিদিন সকাল ৭টা – রাত ১১টা')],
        ];
    }

    // Form Labels
    $formTitle = getSetting('contact_form_title', 'বার্তা পাঠান');
    $nameLabel = getSetting('contact_name_label', 'আপনার নাম');
    $emailLabel = getSetting('contact_email_label', 'ইমেইল');
    $messageLabel = getSetting('contact_message_label', 'আপনার বার্তা');
    $buttonText = getSetting('contact_button_text', 'পাঠান →');

    // Success Message
    $successMessage = getSetting('contact_success_message', '✅ আপনার বার্তা পাঠানো হয়েছে। আমরা শীঘ্রই যোগাযোগ করবো।');
@endphp
@section('title', 'যোগাযোগ — চিল ঘর | অর্ডার ও ফিডব্যাক')

@section('description',
    'চিল ঘর রেস্টুরেন্টে অর্ডার, ক্যাটারিং, ফিডব্যাক বা যেকোনো তথ্যের জন্য আমাদের সাথে যোগাযোগ করুন।
    দ্রুত সেবা ও সহজ যোগাযোগ ব্যবস্থা।')

@section('keywords', 'যোগাযোগ, চিল ঘর, রেস্টুরেন্ট ফোন, অর্ডার, ক্যাটারিং, ফিডব্যাক')

@section('og_title', 'যোগাযোগ করুন — চিল ঘর')
@section('og_description', 'অর্ডার বা যেকোনো প্রয়োজনে আমাদের সাথে সহজেই যোগাযোগ করুন')
@section('og_type', 'website')

@section('twitter_title', 'চিল ঘর যোগাযোগ')
@section('twitter_description', 'দ্রুত যোগাযোগ ও অর্ডার সাপোর্ট')
@section('title', $pageTitle)

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">

        @if (session('contact_success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                {{ session('contact_success') }}
            </div>
        @endif

        <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ $pageBadge }}</p>
        <h1 class="mt-1 font-display text-4xl font-bold sm:text-5xl">{{ $pageHeading }}</h1>
        <p class="mt-3 text-charcoal/60">{{ $pageSubheading }}</p>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            {{-- Info Cards --}}
            <div class="space-y-4">
                @foreach ($contactInfo as $info)
                    <div class="flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft">
                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-primary/10 text-xl">
                            {{ $info[0] ?? '📍' }}</div>
                        <div>
                            <div class="text-xs font-bold text-charcoal/50 uppercase tracking-wider mb-0.5">
                                {{ $info[1] ?? '' }}</div>
                            @if (($info[1] ?? '') == 'ফোন')
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $info[2] ?? '') }}"
                                    class="font-bold text-sm hover:text-primary transition">{{ $info[2] ?? '' }}</a>
                            @elseif(($info[1] ?? '') == 'ইমেইল')
                                <a href="mailto:{{ $info[2] ?? '' }}"
                                    class="font-bold text-sm hover:text-primary transition">{{ $info[2] ?? '' }}</a>
                            @else
                                <div class="font-bold text-sm">{{ $info[2] ?? '' }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Contact Form --}}
            <div class="rounded-2xl border border-charcoal/10 bg-white p-7 shadow-soft">
                <h2 class="font-display font-bold text-lg mb-5">{{ $formTitle }}</h2>

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">{{ $nameLabel }}
                            <span class="text-primary">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="{{ $nameLabel }}"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">{{ $emailLabel }}
                            <span class="text-primary">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="{{ $emailLabel }}"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">ফোন
                            (ঐচ্ছিক)</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="০১XXXXXXXXX"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">{{ $messageLabel }}
                            <span class="text-primary">*</span></label>
                        <textarea name="message" rows="4" required placeholder="{{ $messageLabel }}"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15 resize-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02] hover:shadow-[0_12px_30px_-8px_rgba(192,57,43,0.5)]">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
