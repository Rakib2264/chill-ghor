@extends('admin.layouts.app')
@section('title', 'ইমেইল পাঠান')
@section('header', '🚀 ইমেইল পাঠান')

@push('styles')
    <style>
        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 999px;
            padding: 4px 10px 4px 6px;
            font-size: 12px;
            font-weight: 600;
            color: #92400e;
        }

        .user-chip img {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-chip button {
            background: none;
            border: none;
            cursor: pointer;
            color: #b45309;
            font-size: 14px;
            line-height: 1;
            padding: 0;
            margin-left: 2px;
        }

        .search-dropdown {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
            width: 100%;
            max-height: 260px;
            overflow-y: auto;
            margin-top: 4px;
            left: 0;
        }

        .search-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .search-dropdown-item:hover {
            background: #fef9f0;
        }

        .search-dropdown-item img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            background: #f3f4f6;
        }

        .search-dropdown-item .meta {
            display: flex;
            flex-direction: column;
        }

        .search-dropdown-item .meta strong {
            font-size: 13px;
            color: #1f2937;
        }

        .search-dropdown-item .meta span {
            font-size: 11px;
            color: #6b7280;
        }

        .template-preview-frame {
            width: 100%;
            min-height: 340px;
            border: none;
            border-radius: 14px;
            background: #f8f9fb;
        }

        [x-cloak] {
            display: none !important;
        }

        .link-field-row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .link-field-row input {
            flex: 1;
        }

        .tab-btn {
            padding: 8px 20px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s;
            border: none;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #c0392b, #e8671a);
            color: white;
        }

        .tab-btn:not(.active) {
            background: #f3f4f6;
            color: #374151;
        }
    </style>
@endpush

@section('content')
    <div x-data="emailComposer()" class="mx-auto max-w-6xl space-y-5">
        {{-- Top bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex gap-2">
                <a href="{{ route('admin.emails.history') }}"
                    class="rounded-full bg-white border border-charcoal/10 px-4 py-2 text-xs font-bold shadow-soft">🕘
                    হিস্ট্রি</a>
                <a href="{{ route('admin.email-templates.index') }}"
                    class="rounded-full bg-white border border-charcoal/10 px-4 py-2 text-xs font-bold shadow-soft">📋
                    টেমপ্লেট</a>
            </div>
            <p class="text-xs text-charcoal/50">মোট গ্রাহক: <b>{{ $customerCount }}</b> | সব ইউজার:
                <b>{{ $userCount }}</b></p>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1fr_420px]">

            {{-- LEFT: Compose form --}}
            <form method="POST" action="{{ route('admin.emails.send.store') }}" @submit.prevent="submitForm($event)"
                class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft space-y-5">
                @csrf

                {{-- STEP 1: Template --}}
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-charcoal/40 mb-3">ধাপ ১ — টেমপ্লেট</p>
                    <select name="template_key" x-model="templateKey" @change="loadPreview()"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 font-semibold">
                        <option value="">— টেমপ্লেট সিলেক্ট করুন —</option>
                        @foreach ($templates as $t)
                            <option value="{{ $t->key }}" data-vars="{{ $t->available_vars ?? '[]' }}">
                                {{ $t->name }} — {{ $t->subject }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dynamic link fields --}}
                <div x-show="templateKey && linkFields.length > 0" x-cloak
                    class="rounded-xl bg-blue-50 border border-blue-100 p-4 space-y-3">
                    <p class="text-xs font-black uppercase tracking-widest text-blue-400 mb-2">🔗 টেমপ্লেট লিংক/ভেরিয়েবল
                    </p>
                    <template x-for="field in linkFields" :key="field">
                        <div class="link-field-row">
                            <label class="text-xs font-bold text-blue-700 w-28 shrink-0" x-text="fieldLabel(field)"></label>
                            <input type="text" :name="'vars[' + field + ']'" :placeholder="fieldPlaceholder(field)"
                                class="rounded-xl border border-blue-200 bg-white px-3 py-2 text-sm w-full"
                                x-model="vars[field]" @input="loadPreview()">
                        </div>
                    </template>
                </div>

                {{-- Subject override --}}
                <label class="block text-sm font-bold">
                    সাবজেক্ট <span class="font-normal text-charcoal/40">(ঐচ্ছিক — খালি রাখলে টেমপ্লেট থেকে নেবে)</span>
                    <input name="subject_override" x-model="subjectOverride" @input="loadPreview()"
                        placeholder="অথবা নিজে লিখুন..."
                        class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2">
                </label>

                {{-- STEP 2: Audience --}}
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-charcoal/40 mb-3">ধাপ ২ — প্রাপক</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach (['single' => '👤 একজন', 'selected' => '✅ বাছাই করা', 'all_customers' => '👥 সব গ্রাহক (' . $customerCount . ')', 'all_users' => '🌐 সবাই (' . $userCount . ')', 'custom' => '✏️ কাস্টম'] as $key => $label)
                            <button type="button" @click="audience='{{ $key }}'"
                                :class="audience === '{{ $key }}' ? 'active' : ''"
                                class="tab-btn">{{ $label }}</button>
                        @endforeach
                    </div>

                    {{-- Single email --}}
                    <div x-show="audience==='single'" x-cloak>
                        <input name="email" type="email" placeholder="customer@email.com"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2">
                    </div>

                    {{-- Selected users (search & chip) --}}
                    <div x-show="audience==='selected'" x-cloak class="space-y-3">
                        <div class="relative">
                            <input type="text" x-model="userSearch" @input.debounce.300ms="searchUsers()"
                                @keydown.escape="showDropdown=false" placeholder="নাম বা ইমেইল দিয়ে খুঁজুন..."
                                class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2 pl-10">
                            <span class="absolute left-3 top-2.5 text-charcoal/30">🔍</span>

                            <div x-show="showDropdown && searchResults.length > 0" x-cloak class="search-dropdown">
                                <template x-for="user in searchResults" :key="user.id">
                                    <div class="search-dropdown-item" @click="addUser(user)">
                                        <img :src="user.avatar || '/images/default-avatar.png'" :alt="user.name"
                                            onerror="this.src='/images/default-avatar.png'">
                                        <div class="meta">
                                            <strong x-text="user.name"></strong>
                                            <span x-text="user.email + (user.phone ? ' · ' + user.phone : '')"></span>
                                        </div>
                                        <span class="ml-auto text-xs font-bold text-green-600">+ যোগ করুন</span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="showDropdown && searchResults.length === 0 && userSearch.length > 1" x-cloak
                                class="search-dropdown p-4 text-center text-sm text-charcoal/40">কোনো ইউজার পাওয়া যায়নি
                            </div>
                        </div>

                        {{-- Selected user chips --}}
                        <div x-show="selectedUsers.length > 0" x-cloak
                            class="flex flex-wrap gap-2 p-3 rounded-xl bg-amber-50 border border-amber-100 min-h-[50px]">
                            <template x-for="user in selectedUsers" :key="user.id">
                                <div class="user-chip">
                                    <img :src="user.avatar || '/images/default-avatar.png'" :alt="user.name"
                                        onerror="this.src='/images/default-avatar.png'">
                                    <span x-text="user.name"></span>
                                    <button type="button" @click="removeUser(user.id)" title="সরান">×</button>
                                    <input type="hidden" name="selected_users[]" :value="user.id">
                                </div>
                            </template>
                            <span class="ml-auto text-xs text-amber-700 font-semibold self-center"
                                x-text="selectedUsers.length + ' জন নির্বাচিত'"></span>
                        </div>
                        <div x-show="selectedUsers.length === 0" x-cloak class="text-xs text-charcoal/40 italic">কোনো ইউজার
                            সিলেক্ট হয়নি</div>
                    </div>

                    {{-- Custom list --}}
                    <div x-show="audience==='custom'" x-cloak>
                        <textarea name="custom_list" rows="4"
                            placeholder="একাধিক ইমেইল comma, space বা newline দিয়ে আলাদা করুন&#10;example@email.com, another@email.com"
                            class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2 font-mono text-xs"></textarea>
                    </div>

                    {{-- Bulk warning --}}
                    <div x-show="audience==='all_customers'||audience==='all_users'" x-cloak
                        class="rounded-xl bg-yellow-50 border border-yellow-200 p-4 text-sm font-semibold text-yellow-800">
                        ⚠️ Bulk email পাঠানোর আগে SMTP settings ঠিক আছে কিনা নিশ্চিত করুন।
                    </div>

                    <input type="hidden" name="audience" :value="audience">
                </div>

                {{-- Send button --}}
                <div class="flex items-center justify-between pt-2 border-t border-charcoal/8">
                    <p class="text-xs text-charcoal/40">
                        <span x-text="recipientSummary()"></span>
                    </p>
                    <button type="submit" :disabled="!templateKey || sending"
                        class="rounded-full bg-gradient-warm px-7 py-3 font-bold text-white shadow-warm disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2">
                        <span x-show="!sending">🚀 পাঠান</span>
                        <span x-show="sending" x-cloak>⏳ পাঠানো হচ্ছে...</span>
                    </button>
                </div>
            </form>

            {{-- RIGHT: Live Preview --}}
            <div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft overflow-hidden flex flex-col">
                <div class="border-b border-charcoal/8 px-5 py-3 flex items-center justify-between">
                    <p class="font-bold text-sm">👁️ লাইভ প্রিভিউ</p>
                    <div class="flex gap-1">
                        <button type="button" @click="previewMode='desktop'"
                            :class="previewMode === 'desktop' ? 'bg-charcoal text-white' : 'bg-cream'"
                            class="rounded-lg px-3 py-1 text-xs font-bold">🖥️</button>
                        <button type="button" @click="previewMode='mobile'"
                            :class="previewMode === 'mobile' ? 'bg-charcoal text-white' : 'bg-cream'"
                            class="rounded-lg px-3 py-1 text-xs font-bold">📱</button>
                    </div>
                </div>

                {{-- Subject preview --}}
                <div x-show="previewSubject" x-cloak class="bg-gray-50 border-b border-charcoal/8 px-5 py-2">
                    <p class="text-xs text-charcoal/40">সাবজেক্ট:</p>
                    <p class="text-sm font-semibold text-charcoal" x-text="previewSubject"></p>
                </div>

                <div class="flex-1 p-4 flex items-start justify-center overflow-auto bg-gray-50">
                    <div :class="previewMode === 'mobile' ? 'w-[375px]' : 'w-full'" class="transition-all duration-300">
                        <div x-show="!templateKey" class="text-center py-20 text-charcoal/30">
                            <p class="text-4xl mb-3">📧</p>
                            <p class="text-sm font-semibold">টেমপ্লেট সিলেক্ট করুন</p>
                            <p class="text-xs">প্রিভিউ এখানে দেখাবে</p>
                        </div>
                        <div x-show="templateKey && previewHtml" x-cloak>
                            <iframe id="previewFrame" class="template-preview-frame"
                                :style="previewMode === 'mobile' ? 'width:375px' : 'width:100%'"></iframe>
                        </div>
                        <div x-show="templateKey && previewLoading" x-cloak class="text-center py-16 text-charcoal/40">
                            <p class="text-2xl mb-2 animate-spin inline-block">⏳</p>
                            <p class="text-sm">লোড হচ্ছে...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function emailComposer() {
                return {
                    templateKey: '',
                    subjectOverride: '',
                    audience: 'single',
                    userSearch: '',
                    searchResults: [],
                    selectedUsers: [],
                    showDropdown: false,
                    previewHtml: '',
                    previewSubject: '',
                    previewLoading: false,
                    previewMode: 'desktop',
                    sending: false,
                    vars: {},
                    linkFields: [],

                    init() {
                        document.addEventListener('click', (e) => {
                            if (!this.$el.contains(e.target)) this.showDropdown = false;
                        });
                    },

                    get allTemplateVars() {
                        const sel = document.querySelector(
                            `select[name="template_key"] option[value="${this.templateKey}"]`);
                        if (!sel) return [];
                        try {
                            return JSON.parse(sel.dataset.vars || '[]');
                        } catch {
                            return [];
                        }
                    },

                    fieldLabel(f) {
                        const map = {
                            order_link: '🛒 অর্ডার লিংক',
                            cart_link: '🛒 কার্ট লিংক',
                            review_link: '⭐ রিভিউ লিংক',
                            reset_link: '🔑 রিসেট লিংক',
                            offer_text: '💥 অফার টেক্সট',
                            favorite_item: '❤️ আইটেম',
                            last_order_item: '🔁 শেষ অর্ডার',
                            order_no: '📦 অর্ডার নং',
                            total: '💰 মোট',
                            delivery_time: '⏱️ ডেলিভারি'
                        };
                        return map[f] || f;
                    },

                    fieldPlaceholder(f) {
                        const map = {
                            order_link: 'https://yoursite.com/order',
                            cart_link: 'https://yoursite.com/cart',
                            review_link: 'https://yoursite.com/review',
                            reset_link: 'https://yoursite.com/reset',
                            offer_text: 'যেমন: ৩০% ছাড় সব আইটেমে',
                            favorite_item: 'যেমন: চিকেন বার্গার',
                            last_order_item: 'যেমন: চিকেন রোল',
                            order_no: '#ORD-12345',
                            total: '৳৩৫০',
                            delivery_time: '৩০-৪৫ মিনিট'
                        };
                        return map[f] || 'মান লিখুন...';
                    },

                    loadPreview() {
                        // Update link fields from template
                        const skip = ['name', 'site_name'];
                        this.linkFields = this.allTemplateVars.filter(v => !skip.includes(v));

                        if (!this.templateKey) {
                            this.previewHtml = '';
                            this.previewSubject = '';
                            return;
                        }
                        this.previewLoading = true;

                        const params = new URLSearchParams({
                            key: this.templateKey,
                            subject_override: this.subjectOverride,
                            ...Object.fromEntries(Object.entries(this.vars).map(([k, v]) => [`vars[${k}]`, v]))
                        });

                        fetch(`{{ route('admin.emails.preview') }}?${params}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                this.previewHtml = data.body || '';
                                this.previewSubject = data.subject || '';
                                this.$nextTick(() => {
                                    const frame = document.getElementById('previewFrame');
                                    if (frame) {
                                        frame.srcdoc = this.previewHtml;
                                        frame.onload = () => {
                                            frame.style.height = (frame.contentDocument?.body?.scrollHeight ||
                                                400) + 'px';
                                        };
                                    }
                                });
                            })
                            .finally(() => {
                                this.previewLoading = false;
                            });
                    },

                    async searchUsers() {
                        if (this.userSearch.length < 2) {
                            this.searchResults = [];
                            this.showDropdown = false;
                            return;
                        }
                        const r = await fetch(`{{ route('admin.emails.preview') }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        this.searchResults = await r.json();
                        this.showDropdown = true;
                    },

                    addUser(user) {
                        if (!this.selectedUsers.find(u => u.id === user.id)) this.selectedUsers.push(user);
                        this.userSearch = '';
                        this.searchResults = [];
                        this.showDropdown = false;
                    },

                    removeUser(id) {
                        this.selectedUsers = this.selectedUsers.filter(u => u.id !== id);
                    },

                    recipientSummary() {
                        if (this.audience === 'single') return '১ জনকে পাঠাবে';
                        if (this.audience === 'selected') return this.selectedUsers.length + ' জন নির্বাচিত';
                        if (this.audience === 'all_customers') return '{{ $customerCount }} জন গ্রাহককে';
                        if (this.audience === 'all_users') return '{{ $userCount }} জন সবাইকে';
                        return 'কাস্টম লিস্ট';
                    },

                    submitForm(e) {
                        this.sending = true;
                        e.target.submit();
                    }
                }
            }
        </script>
    @endpush
@endsection
