@if (isset($ads) && $ads->count() > 0)

    {{-- Global ad manager with enhanced localStorage --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('adManager', {
                // Dismissed ads with timestamps
                dismissedAds: {},

                init() {
                    // Load dismissed ads with timestamps
                    const stored = localStorage.getItem('ad_dismissals');
                    if (stored) {
                        try {
                            this.dismissedAds = JSON.parse(stored);
                            // Clean up old entries (older than 7 days)
                            const now = Date.now();
                            let changed = false;
                            Object.keys(this.dismissedAds).forEach(id => {
                                const expiry = this.dismissedAds[id];
                                if (expiry && expiry !== Infinity && now > expiry) {
                                    delete this.dismissedAds[id];
                                    changed = true;
                                }
                            });
                            if (changed) this.save();
                        } catch (e) {
                            console.error('Failed to parse ad dismissals', e);
                            this.dismissedAds = {};
                        }
                    }
                },

                save() {
                    localStorage.setItem('ad_dismissals', JSON.stringify(this.dismissedAds));
                },

                isDismissed(adId) {
                    const expiry = this.dismissedAds[adId];
                    if (!expiry) return false;
                    if (expiry === Infinity) return true;
                    return Date.now() < expiry;
                },

                dismiss(adId, duration = 86400000) { // Default 24 hours in ms
                    this.dismissedAds[adId] = Date.now() + duration;
                    this.save();
                },

                dismissPermanently(adId) {
                    this.dismissedAds[adId] = Infinity;
                    this.save();
                },

                resetAd(adId) {
                    delete this.dismissedAds[adId];
                    this.save();
                },

                resetAll() {
                    this.dismissedAds = {};
                    this.save();
                },

                // Get remaining time for an ad
                getRemainingTime(adId) {
                    const expiry = this.dismissedAds[adId];
                    if (!expiry || expiry === Infinity) return null;
                    const remaining = expiry - Date.now();
                    return remaining > 0 ? remaining : 0;
                },

                // Format remaining time for display
                formatRemainingTime(ms) {
                    if (ms <= 0) return '';
                    const hours = Math.floor(ms / (1000 * 60 * 60));
                    if (hours < 24) return `${hours} ঘন্টা`;
                    const days = Math.floor(hours / 24);
                    return `${days} দিন`;
                }
            });

            // Register console helper for debugging
            if (typeof window !== 'undefined') {
                window.adManager = Alpine.store('adManager');
            }
        });
    </script>

    {{-- ===== POPUP ADS (Enhanced) ===== --}}
    @php $popupAds = $ads->where('style', 'popup')->where('is_active', true); @endphp
    @if ($popupAds->count() > 0)
        <div x-data="{
            open: false,
            currentIndex: 0,
            ads: {{ $popupAds->values()->toJson() }},
            get availableAds() {
                return this.ads.filter(ad => !$store.adManager.isDismissed(ad.id));
            },
            get current() {
                const available = this.availableAds;
                return available[this.currentIndex] ?? null;
            },
            init() {
                this.$nextTick(() => {
                    if (this.availableAds.length > 0) {
                        // Check if user has seen ads today
                        const lastShowDate = localStorage.getItem('last_popup_show');
                        const today = new Date().toDateString();
        
                        if (lastShowDate !== today) {
                            setTimeout(() => {
                                this.open = true;
                                localStorage.setItem('last_popup_show', today);
                            }, 1500);
                        }
                    }
                });
            },
            dismiss(permanent = false) {
                if (this.current) {
                    if (permanent) {
                        $store.adManager.dismissPermanently(this.current.id);
                    } else {
                        $store.adManager.dismiss(this.current.id, 12 * 60 * 60 * 1000); // 12 hours
                    }
                }
        
                const nextAvailable = this.availableAds;
                if (nextAvailable.length > 0 && this.currentIndex < nextAvailable.length - 1) {
                    this.currentIndex++;
                } else {
                    this.open = false;
                }
            },
            close() {
                this.open = false;
            }
        }" x-show="open && current !== null" x-cloak
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 sm:p-6"
            style="background:rgba(0,0,0,0.85);backdrop-filter:blur(8px)" @click.self="close()">

            <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-[90%] sm:max-w-md overflow-hidden rounded-2xl sm:rounded-3xl shadow-2xl"
                :style="`background:${current?.bg_color ?? '#1e293b'};color:${current?.text_color ?? '#ffffff'}`"
                @click.stop>

                {{-- Close button --}}
                <button @click="close()"
                    class="absolute right-3 top-3 z-10 flex h-8 w-8 items-center justify-center rounded-full text-sm transition-all duration-200 hover:scale-110 hover:rotate-90 active:scale-95"
                    style="background:rgba(0,0,0,0.4);backdrop-filter:blur(4px)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                {{-- Hero section --}}
                <div class="relative flex items-center justify-center overflow-hidden"
                    style="height:140px;background:linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 100%)">
                    <div class="absolute inset-0 opacity-20 bg-gradient-to-br from-white to-transparent"></div>
                    <div class="relative z-10 transform transition-transform duration-500 hover:scale-110">
                        <span class="text-7xl sm:text-8xl leading-none drop-shadow-lg"
                            x-text="current?.emoji ?? '🎉'"></span>
                    </div>
                </div>

                <div class="relative px-5 pb-6 pt-4 sm:px-6 sm:pb-7 sm:pt-5">
                    {{-- Badge --}}
                    <div x-show="current?.badge"
                        class="mb-3 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold tracking-wide"
                        style="background:rgba(255,255,255,0.15);backdrop-filter:blur(4px)">
                        <span class="text-sm">✨</span>
                        <span x-text="current?.badge"></span>
                    </div>

                    {{-- Title --}}
                    <h3 class="mb-2 text-xl sm:text-2xl font-bold leading-tight tracking-tight" x-text="current?.title">
                    </h3>

                    {{-- Body --}}
                    <p class="mb-5 text-sm sm:text-base leading-relaxed opacity-85" x-text="current?.body"></p>

                    {{-- Buttons --}}
                    <div class="flex flex-col gap-2 sm:flex-row sm:gap-3">
                        <template x-if="current?.cta_text && current?.cta_url">
                            <a :href="current.cta_url"
                                class="group flex-1 rounded-full py-3 px-4 text-center text-sm font-semibold transition-all duration-200 hover:shadow-lg active:scale-95"
                                :style="`background:${current?.cta_color ?? '#ffffff'};color:${current?.bg_color ?? '#1e293b'}`"
                                @click="close()">
                                <span x-text="current?.cta_text"></span>
                                <span
                                    class="inline-block transition-transform duration-200 group-hover:translate-x-1">→</span>
                            </a>
                        </template>
                        <button @click="dismiss(false)"
                            class="flex-1 rounded-full py-3 px-4 text-sm font-medium transition-all duration-200 hover:bg-white/10 active:scale-95"
                            style="border:1px solid rgba(255,255,255,0.3)">
                            <span>পরে মনে করিয়ে দিন</span>
                        </button>
                    </div>

                    {{-- "Don't show again" option --}}
                    <button @click="dismiss(true)"
                        class="mt-3 text-center w-full text-xs opacity-50 hover:opacity-100 transition-opacity py-1">
                        আর দেখাবেন না
                    </button>

                    {{-- Progress indicator --}}
                    <div x-show="ads.length > 1" class="mt-4 flex justify-center gap-2">
                        <template x-for="(ad, i) in ads" :key="ad.id">
                            <button @click="currentIndex = i" class="transition-all duration-300 rounded-full"
                                :class="i === currentIndex ? 'w-6 h-1.5' : 'w-1.5 h-1.5'"
                                :style="`background:${$store.adManager.isDismissed(ad.id) ? 'rgba(255,255,255,0.2)' : (i === currentIndex ? 'white' : 'rgba(255,255,255,0.4)')}`"
                                :disabled="$store.adManager.isDismissed(ad.id)">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ===== BANNER ADS (Mobile-First with Swipe) ===== --}}
    @php $bannerAds = $ads->where('style', 'banner')->where('is_active', true); @endphp
    @foreach ($bannerAds as $ad)
        <div x-data="{
            show: true,
            expanded: false,
            adId: {{ $ad->id }},
            isDismissed: false,
            touchStart: null,
            touchEnd: null,
            checkDismissed() {
                this.isDismissed = $store.adManager.isDismissed(this.adId);
                if (this.isDismissed) this.show = false;
            },
            handleSwipe() {
                const swipeDistance = this.touchStart - this.touchEnd;
                if (Math.abs(swipeDistance) > 50) {
                    this.show = false;
                    $store.adManager.dismiss(this.adId, 4 * 60 * 60 * 1000); // 4 hours
                }
            },
            init() {
                this.checkDismissed();
                // Watch for storage changes from other tabs
                window.addEventListener('storage', (e) => {
                    if (e.key === 'ad_dismissals') {
                        this.checkDismissed();
                    }
                });
            }
        }" x-show="show && !isDismissed" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-full"
            class="sticky top-0 z-40 mx-auto max-w-7xl px-3 sm:px-4 pb-3 sm:pb-4"
            @touchstart="touchStart = $event.touches[0].clientX"
            @touchend="touchEnd = $event.changedTouches[0].clientX; handleSwipe()">

            <div class="group relative overflow-hidden rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl transition-all duration-300"
                :class="{ 'scale-[0.98]': expanded }"
                style="background:{{ $ad->bg_color }};color:{{ $ad->text_color }}">

                {{-- Swipe hint for mobile --}}
                <div class="absolute left-2 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none sm:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                <div class="relative p-3 sm:p-4" @click="expanded = !expanded">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        {{-- Left section --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-2xl sm:text-3xl"
                                style="background:rgba(255,255,255,0.12)">
                                <span>{{ $ad->emoji }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                @if ($ad->badge)
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-white/60"></span>
                                        <span
                                            class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider">{{ $ad->badge }}</span>
                                    </div>
                                @endif
                                <h4 class="text-sm sm:text-base font-semibold leading-tight">{{ $ad->title }}</h4>
                                <p class="text-xs sm:text-sm opacity-75" :class="expanded ? '' : 'line-clamp-1'">
                                    {{ $ad->body }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 justify-end sm:justify-start">
                            @if ($ad->cta_text && $ad->cta_url)
                                <a href="{{ $ad->cta_url }}"
                                    class="whitespace-nowrap rounded-full px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold transition-all duration-200 hover:scale-105 active:scale-95 shadow-sm"
                                    style="background:{{ $ad->cta_color }};color:{{ $ad->bg_color }}" @click.stop>
                                    {{ $ad->cta_text }}
                                </a>
                            @endif
                            <button @click.stop="show = false; $store.adManager.dismiss(adId, 4 * 60 * 60 * 1000)"
                                class="flex h-8 w-8 items-center justify-center rounded-full text-sm transition-all duration-200 hover:bg-white/20 hover:scale-110">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Expand indicator --}}
                    <div class="sm:hidden flex justify-center mt-2">
                        <div class="w-8 h-0.5 rounded-full bg-white/30 transition-transform duration-300"
                            :class="{ 'rotate-180': expanded }"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ===== SLIDE ADS (Enhanced with Dismiss Timer) ===== --}}
    @php $slideAds = $ads->where('style', 'slide')->where('is_active', true); @endphp
    @foreach ($slideAds as $slideAd)
        <div x-data="{
            show: false,
            isHovered: false,
            adId: {{ $slideAd->id }},
            isDismissed: false,
            autoHideTimeout: null,
            checkDismissed() {
                this.isDismissed = $store.adManager.isDismissed(this.adId);
                if (this.isDismissed) this.show = false;
            },
            startAutoHide() {
                this.autoHideTimeout = setTimeout(() => {
                    this.dismiss();
                }, 8000);
            },
            dismiss() {
                this.show = false;
                $store.adManager.dismiss(this.adId, 2 * 60 * 60 * 1000); // 2 hours
                if (this.autoHideTimeout) clearTimeout(this.autoHideTimeout);
            },
            pauseAutoHide() {
                if (this.autoHideTimeout) clearTimeout(this.autoHideTimeout);
            },
            resumeAutoHide() {
                this.autoHideTimeout = setTimeout(() => this.dismiss(), 3000);
            },
            init() {
                this.checkDismissed();
                if (!this.isDismissed) {
                    setTimeout(() => {
                        this.show = true;
                        this.startAutoHide();
                    }, 2000);
                }
            }
        }" x-show="show && !isDismissed" x-cloak
            x-transition:enter="transition-all ease-out duration-500 transform"
            x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition-all ease-in duration-300 transform"
            x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
            class="fixed bottom-4 left-4 right-4 sm:bottom-6 sm:left-auto sm:right-6 sm:max-w-xs z-50"
            @mouseenter="isHovered = true; pauseAutoHide()" @mouseleave="isHovered = false; resumeAutoHide()"
            @touchstart="pauseAutoHide()" @touchend="resumeAutoHide()">

            <div class="relative overflow-hidden rounded-xl sm:rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300"
                :class="{ 'scale-105': isHovered }"
                style="background:{{ $slideAd->bg_color }};color:{{ $slideAd->text_color }}">

                {{-- Auto-hide progress bar --}}
                <div class="absolute top-0 left-0 right-0 h-1 bg-white/20 overflow-hidden">
                    <div class="h-full bg-white/60 transition-all duration-[8000ms] linear" x-init="setTimeout(() => $el.style.width = '0%', 100)"
                        :class="{ 'transition-none': !show }"></div>
                </div>

                <button @click="dismiss()"
                    class="absolute right-2 top-2 z-10 flex h-7 w-7 items-center justify-center rounded-full text-xs transition-all duration-200 hover:scale-110 hover:rotate-90"
                    style="background:rgba(0,0,0,0.3);backdrop-filter:blur(4px)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="relative p-4 pr-8">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-2xl animate-bounce"
                            style="background:rgba(255,255,255,0.15)">
                            <span>{{ $slideAd->emoji }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            @if ($slideAd->badge)
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <span class="text-xs">⚡</span>
                                    <span
                                        class="text-[10px] font-semibold uppercase tracking-wider">{{ $slideAd->badge }}</span>
                                </div>
                            @endif
                            <h4 class="text-sm font-bold leading-tight mb-0.5">{{ $slideAd->title }}</h4>
                            <p class="text-xs leading-relaxed opacity-80 line-clamp-2">{{ $slideAd->body }}</p>

                            @if ($slideAd->cta_text && $slideAd->cta_url)
                                <a href="{{ $slideAd->cta_url }}"
                                    class="inline-flex items-center gap-2 mt-2.5 text-xs font-semibold transition-all duration-200 hover:gap-3"
                                    @click="dismiss()">
                                    <span>{{ $slideAd->cta_text }}</span>
                                    <svg class="w-3 h-3 transition-transform duration-200" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endif

<style>
    @keyframes bounce-slow {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }

    .animate-bounce {
        animation: bounce 1s ease-in-out infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mobile optimizations */
    @media (max-width: 640px) {
        .sticky {
            position: -webkit-sticky;
            position: sticky;
        }
    }

    /* Ensure Alpine.js hidden elements don't cause layout shift */
    [x-cloak] {
        display: none !important;
    }
</style>
