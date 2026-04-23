@extends('layouts.app')
@section('title', 'চেকআউট — চিল ঘর')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-14 lg:px-8" x-data="checkoutSpa({
        addresses: @js($addresses ?? []),
        defaultId: {{ $defaultAddress->id ?? 'null' }},
        items: @js($items->map(fn($i) => ['id' => $i['product']->id, 'name' => $i['product']->name, 'price' => (int) $i['product']->price, 'qty' => $i['qty'], 'image' => $i['product']->image, 'total' => (int) ($i['product']->price * $i['qty'])])),
        subtotal: {{ (int) $subtotal }},
        deliveryFee: {{ (int) $deliveryFee }},
        total: {{ (int) $total }},
        storeUrl: '{{ route('checkout.store') }}',
        addAddrUrl: '{{ url('/profile/addresses') }}',
        deliveryFeeUrl: '{{ route('checkout.delivery-fee') }}',
        loggedIn: {{ auth()->check() ? 'true' : 'false' }},
        deliveryZones: @js($deliveryZones ?? [])
    })">

        {{-- Stepper --}}
        <div class="mb-8 flex items-center justify-center gap-2 sm:gap-6 text-xs sm:text-sm">
            <template x-for="(s, i) in steps" :key="i">
                <div class="flex items-center gap-2">
                    <div :class="step >= i + 1 ? 'bg-gradient-warm text-white shadow-warm' :
                        'bg-white text-charcoal/50 border border-charcoal/15'"
                        class="flex h-9 w-9 items-center justify-center rounded-full font-bold transition">
                        <span x-show="step > i+1">✓</span>
                        <span x-show="step <= i+1" x-text="i+1"></span>
                    </div>
                    <span :class="step >= i + 1 ? 'text-charcoal font-bold' : 'text-charcoal/45'" x-text="s"
                        class="hidden sm:inline"></span>
                    <div x-show="i < steps.length - 1" class="h-px w-6 sm:w-12 bg-charcoal/15"></div>
                </div>
            </template>
        </div>

        {{-- STEP 1: REVIEW CART --}}
        <div x-show="step === 1" x-transition class="grid gap-6 lg:grid-cols-[1fr,360px]">
            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h2 class="font-display text-xl font-bold mb-5">🛒 আপনার অর্ডার</h2>
                <div class="space-y-3">
                    <template x-for="item in items" :key="item.id">
                        <div class="flex items-center gap-3 rounded-xl border border-charcoal/10 p-3">
                            <div
                                class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-lg bg-cream text-2xl flex-shrink-0">
                                <template x-if="item.image"><img :src="item.image"
                                        class="h-full w-full object-cover"></template>
                                <template x-if="!item.image"><span>🍽️</span></template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold truncate" x-text="item.name"></div>
                                <div class="text-xs text-charcoal/55">৳<span x-text="item.price.toLocaleString()"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="updateQty(item.id, 'dec')"
                                    class="h-7 w-7 rounded-full border border-charcoal/20 bg-cream font-bold hover:border-primary hover:bg-primary hover:text-white">−</button>
                                <span class="w-6 text-center font-bold text-sm" x-text="item.qty"></span>
                                <button @click="updateQty(item.id, 'inc')"
                                    class="h-7 w-7 rounded-full border border-charcoal/20 bg-cream font-bold hover:border-primary hover:bg-primary hover:text-white">+</button>
                            </div>
                            <div class="w-20 text-right font-bold text-primary">৳<span
                                    x-text="item.total.toLocaleString()"></span></div>
                            <button @click="removeItem(item.id)"
                                class="h-7 w-7 rounded-full text-charcoal/30 hover:bg-red-50 hover:text-red-500">✕</button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft h-fit">
                <h3 class="font-display text-lg font-bold mb-4">সারাংশ</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-charcoal/60">সাব-টোটাল</dt>
                        <dd class="font-bold">৳<span x-text="subtotal.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between" :class="deliveryFee === 0 ? 'text-green-600' : 'text-charcoal/60'">
                        <dt>ডেলিভারি</dt>
                        <dd class="font-bold" x-text="deliveryFee === 0 ? 'ফ্রি' : '৳' + deliveryFee.toLocaleString()"></dd>
                    </div>
                    <div class="flex justify-between text-green-600" x-show="discount > 0">
                        <dt>ছাড় (<span x-text="couponCode"></span>)</dt>
                        <dd class="font-bold">−৳<span x-text="discount.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between border-t border-charcoal/10 pt-3">
                        <dt class="font-display font-bold">মোট</dt>
                        <dd class="font-bold text-lg text-primary">৳<span
                                x-text="totalAfterDiscount.toLocaleString()"></span></dd>
                    </div>
                </dl>

                {{-- ✅ Free Delivery Info (DYNAMIC) --}}
                <div class="mt-4 rounded-xl bg-cream p-3 text-xs text-charcoal/65"
                    x-show="deliveryFee > 0 && freeMin > 0 && freeMin > subtotal">
                    💡 আরও ৳<span x-text="(freeMin - subtotal).toLocaleString()"></span>
                    অর্ডার করলে ডেলিভারি ফ্রি!
                    <span x-show="selectedZoneName" class="block mt-1 text-[10px]">
                        ( <span x-text="selectedZoneName"></span> জোনের জন্য <span x-text="freeMin.toLocaleString()"></span>
                        টাকার উপরে ফ্রি)
                    </span>
                </div>

                {{-- Coupon Form --}}
                <div class="mt-4 pt-4 border-t border-charcoal/10">
                    <div x-show="!couponApplied">
                        <div class="flex gap-2">
                            <input type="text" x-model="couponInput" placeholder="কুপন কোড"
                                class="flex-1 rounded-xl border border-charcoal/15 bg-cream/50 px-3 py-2 text-sm uppercase focus:border-primary focus:outline-none">
                            <button @click="applyCoupon()" :disabled="couponLoading"
                                class="rounded-xl bg-charcoal px-4 py-2 text-xs font-bold text-white transition hover:bg-primary">
                                <span x-show="!couponLoading">প্রয়োগ</span>
                                <span x-show="couponLoading" class="animate-spin inline-block w-4 h-4">⏳</span>
                            </button>
                        </div>
                        <p x-show="couponError" x-text="couponError" class="mt-2 text-xs text-red-500"></p>
                    </div>
                    <div x-show="couponApplied"
                        class="flex items-center justify-between rounded-xl bg-green-50 px-3 py-2 text-sm">
                        <div><span class="font-bold text-green-700">✅ <span x-text="couponCode"></span></span><span
                                class="text-xs text-green-600 ml-2">(ছাড়: ৳<span
                                    x-text="discount.toLocaleString()"></span>)</span></div>
                        <button @click="removeCoupon()" class="text-xs text-red-500 hover:underline">বাদ দিন</button>
                    </div>
                </div>

                <button @click="step = 2" :disabled="items.length === 0"
                    class="mt-5 w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02] disabled:opacity-50 transition">চালিয়ে
                    যান →</button>
            </div>
        </div>

        {{-- STEP 2: ADDRESS & DETAILS --}}
        <div x-show="step === 2" x-transition class="grid gap-6 lg:grid-cols-[1fr,360px]">
            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h2 class="font-display text-xl font-bold mb-5">📍 ডেলিভারি ঠিকানা</h2>

                <template x-if="loggedIn && addresses.length > 0">
                    <div class="space-y-2 mb-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-charcoal/55">সংরক্ষিত ঠিকানা থেকে বেছে নিন
                        </p>
                        <template x-for="addr in addresses" :key="addr.id">
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border-2 p-3 transition"
                                :class="selectedAddressId === addr.id ? 'border-primary bg-primary/5' :
                                    'border-charcoal/10 bg-cream/40 hover:border-primary/40'">
                                <input type="radio" name="addr" :value="addr.id" x-model="selectedAddressId"
                                    @change="selectAddress(addr)" class="mt-1 accent-primary">
                                <div class="flex-1 text-sm">
                                    <div class="flex items-center gap-2"><span class="font-bold"
                                            x-text="addr.label"></span><span x-show="addr.is_default"
                                            class="rounded bg-spice/20 px-2 py-0.5 text-[10px] font-bold text-spice">ডিফল্ট</span>
                                    </div>
                                    <div class="text-xs text-charcoal/70"
                                        x-text="addr.recipient_name + ' · ' + addr.phone"></div>
                                    <div class="text-xs text-charcoal/55 mt-0.5"
                                        x-text="(addr.area ? addr.area + ', ' : '') + addr.address_line"></div>
                                </div>
                            </label>
                        </template>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3 transition"
                            :class="selectedAddressId === 'new' ? 'border-primary bg-primary/5' :
                                'border-charcoal/10 bg-cream/40 hover:border-primary/40'">
                            <input type="radio" name="addr" value="new" x-model="selectedAddressId"
                                @change="clearAddressForm()" class="accent-primary">
                            <span class="font-bold text-sm">➕ নতুন ঠিকানা ব্যবহার করুন</span>
                        </label>
                    </div>
                </template>

                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="block sm:col-span-2"><span class="text-xs font-bold text-charcoal/70">নাম *</span><input
                            type="text" x-model="form.customer_name" required
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></label>
                    <label class="block"><span class="text-xs font-bold text-charcoal/70">ফোন *</span><input
                            type="tel" x-model="form.phone" required
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></label>
                    <label class="block"><span class="text-xs font-bold text-charcoal/70">ইমেইল *</span><input
                            type="email" x-model="form.email" required placeholder="example@gmail.com"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></label>
                    <label class="block"><span class="text-xs font-bold text-charcoal/70">এলাকা/জোন *</span>
                        <select x-model="form.delivery_zone" @change="updateDeliveryFee()"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none">
                            <option value="">এলাকা নির্বাচন করুন</option>
                            <template x-for="zone in deliveryZones" :key="zone.zone_name">
                                <option :value="zone.zone_name"
                                    x-text="zone.zone_name + ' (মিনিমাম ফ্রি: ৳' + zone.min_order_for_free + ')'"></option>
                            </template>
                        </select>
                    </label>
                    <label class="block"><span class="text-xs font-bold text-charcoal/70">এলাকার নাম
                            (বিস্তারিত)</span><input type="text" x-model="form.area" placeholder="যেমন: ধানমন্ডি ৩২"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></label>
                    <label class="block sm:col-span-2"><span class="text-xs font-bold text-charcoal/70">পূর্ণ ঠিকানা
                            *</span>
                        <textarea x-model="form.address" rows="2" required
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></textarea>
                    </label>
                    <label class="block sm:col-span-2"><span class="text-xs font-bold text-charcoal/70">নোট
                            (ঐচ্ছিক)</span>
                        <textarea x-model="form.notes" rows="2"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-3 text-sm focus:border-primary focus:outline-none"></textarea>
                    </label>
                </div>

                <div class="mt-6 flex justify-between">
                    <button @click="step = 1"
                        class="rounded-full border border-charcoal/15 px-5 py-2.5 text-sm font-bold hover:border-primary hover:text-primary">←
                        পেছনে</button>
                    <button @click="goPayment()"
                        class="rounded-full bg-gradient-warm px-7 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02]">পেমেন্টে
                        যান →</button>
                </div>
            </div>

            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft h-fit">
                <h3 class="font-display text-lg font-bold mb-4">সারাংশ</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-charcoal/60">সাব-টোটাল</dt>
                        <dd class="font-bold">৳<span x-text="subtotal.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between" :class="deliveryFee === 0 ? 'text-green-600' : 'text-charcoal/60'">
                        <dt>ডেলিভারি চার্জ</dt>
                        <dd class="font-bold" x-text="deliveryFee === 0 ? 'ফ্রি' : '৳' + deliveryFee.toLocaleString()">
                        </dd>
                    </div>
                    <div class="flex justify-between text-green-600" x-show="discount > 0">
                        <dt>ছাড় (<span x-text="couponCode"></span>)</dt>
                        <dd class="font-bold">−৳<span x-text="discount.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between border-t border-charcoal/10 pt-3">
                        <dt class="font-display font-bold">মোট</dt>
                        <dd class="font-bold text-lg text-primary">৳<span
                                x-text="totalAfterDiscount.toLocaleString()"></span></dd>
                    </div>
                </dl>
                <div class="mt-4 rounded-xl bg-cream p-3 text-xs text-charcoal/65"
                    x-show="deliveryFee > 0 && freeMin > 0">
                    💡 আরও ৳<span x-text="(freeMin - subtotal).toLocaleString()"></span> অর্ডার করলে ডেলিভারি ফ্রি!
                </div>
            </div>
        </div>

        {{-- STEP 3: PAYMENT --}}
        <div x-show="step === 3" x-transition class="grid gap-6 lg:grid-cols-[1fr,360px]">
            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
                <h2 class="font-display text-xl font-bold mb-5">💳 পেমেন্ট পদ্ধতি</h2>
                <div class="space-y-3">
                    @php $methods = [['cod','ক্যাশ অন ডেলিভারি','💵','খাবার পেয়ে নগদ পরিশোধ'],['bkash','bKash','📱','মার্চেন্ট: 018XXXXXXXX'],['nagad','Nagad','💳','মার্চেন্ট: 018XXXXXXXX']]; @endphp
                    @foreach ($methods as [$key, $label, $icon, $sub])
                        <label class="flex cursor-pointer items-center gap-4 rounded-xl border-2 p-4 transition"
                            :class="form.payment_method === '{{ $key }}' ? 'border-primary bg-primary/5' :
                                'border-charcoal/10 hover:border-primary/40'">
                            <input type="radio" x-model="form.payment_method" value="{{ $key }}"
                                class="accent-primary">
                            <div class="text-2xl">{{ $icon }}</div>
                            <div class="flex-1">
                                <div class="font-bold">{{ $label }}</div>
                                <div class="text-xs text-charcoal/55">{{ $sub }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <template x-if="form.payment_method !== 'cod'">
                    <div class="mt-5 rounded-xl bg-cream p-4 space-y-3">
                        <div class="text-xs text-charcoal/70"><strong
                                x-text="form.payment_method === 'bkash' ? 'bKash' : 'Nagad'"></strong> দিয়ে <strong>৳<span
                                    x-text="totalAfterDiscount.toLocaleString()"></span></strong> Send Money করুন →
                            মার্চেন্ট: <strong>018XXXXXXXX</strong></div>
                        <label class="block"><span class="text-xs font-bold text-charcoal/70">Transaction ID
                                *</span><input type="text" x-model="form.trx_id" placeholder="যেমন: 8N7M9P2Q3R"
                                class="mt-1 w-full rounded-xl border border-charcoal/15 bg-white px-4 py-3 text-sm font-mono focus:border-primary focus:outline-none"></label>
                    </div>
                </template>
                <template x-if="error">
                    <div class="mt-4 rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-600" x-text="error">
                    </div>
                </template>
                <div class="mt-6 flex justify-between">
                    <button @click="step = 2"
                        class="rounded-full border border-charcoal/15 px-5 py-2.5 text-sm font-bold hover:border-primary hover:text-primary">←
                        পেছনে</button>
                    <button @click="placeOrder()" :disabled="loading"
                        class="rounded-full bg-gradient-warm px-7 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02] disabled:opacity-60"><span
                            x-show="!loading">✅ অর্ডার নিশ্চিত করুন</span><span
                            x-show="loading">প্রসেসিং...</span></button>
                </div>
            </div>
            <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft h-fit">
                <h3 class="font-display text-lg font-bold mb-4">সারাংশ</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-charcoal/60">সাব-টোটাল</dt>
                        <dd class="font-bold">৳<span x-text="subtotal.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between" :class="deliveryFee === 0 ? 'text-green-600' : 'text-charcoal/60'">
                        <dt>ডেলিভারি</dt>
                        <dd class="font-bold" x-text="deliveryFee === 0 ? 'ফ্রি' : '৳' + deliveryFee.toLocaleString()">
                        </dd>
                    </div>
                    <div class="flex justify-between text-green-600" x-show="discount > 0">
                        <dt>ছাড় (<span x-text="couponCode"></span>)</dt>
                        <dd class="font-bold">−৳<span x-text="discount.toLocaleString()"></span></dd>
                    </div>
                    <div class="flex justify-between border-t border-charcoal/10 pt-3">
                        <dt class="font-display font-bold">মোট</dt>
                        <dd class="font-bold text-lg text-primary">৳<span
                                x-text="totalAfterDiscount.toLocaleString()"></span></dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- STEP 4: SUCCESS --}}
        <div x-show="step === 4" x-transition class="mx-auto max-w-xl text-center">
            <div class="rounded-3xl border border-green-200 bg-white p-10 shadow-warm">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-5xl">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="font-display text-2xl font-bold">🎉 অর্ডার সফল হয়েছে!</h2>
                <p class="mt-2 text-charcoal/65">আপনার অর্ডারটি গৃহীত হয়েছে। শীঘ্রই কনফার্মেশন কল পাবেন।</p>
                <div class="mt-6 inline-flex items-center gap-2 rounded-xl bg-cream px-5 py-3 text-sm font-mono"><span
                        class="text-charcoal/55">ইনভয়েস:</span><strong class="text-primary"
                        x-text="successInvoice"></strong></div>
                <div class="mt-4"><a :href="trackingUrl"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-500 px-5 py-2 text-sm font-bold text-white hover:bg-blue-600 transition"><span>📍</span>
                        লাইভ অর্ডার ট্র্যাক করুন</a></div>
                <div class="mt-6 pt-4 border-t border-charcoal/10">
                    <div class="flex flex-wrap justify-center gap-3"><a :href="successRedirect"
                            class="rounded-full bg-gradient-warm px-6 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-[1.02] transition">📋
                            অর্ডার বিস্তারিত দেখুন</a><a href="{{ route('menu.index') }}"
                            class="rounded-full border border-charcoal/15 px-6 py-2.5 text-sm font-bold hover:border-primary hover:text-primary transition">🍽️
                            আরও অর্ডার করুন</a></div>
                </div>
                <p class="mt-6 text-xs text-charcoal/50">🔍 আপনার অর্ডার ট্র্যাক করতে পারেন: <a
                        href="{{ route('order.track.form') }}" class="text-primary hover:underline">অর্ডার ট্র্যাক
                        পেজে</a></p>
            </div>
        </div>

    </div>

    <script>
        function checkoutSpa(init) {
            return {
                steps: ['কার্ট', 'ঠিকানা', 'পেমেন্ট', 'সম্পন্ন'],
                step: 1,
                loading: false,
                error: '',
                successInvoice: '',
                successRedirect: '#',
                trackingUrl: '#',
                addresses: init.addresses,
                selectedAddressId: init.defaultId || (init.addresses.length > 0 ? init.addresses[0].id : 'new'),
                loggedIn: init.loggedIn,
                items: init.items,
                subtotal: init.subtotal,
                deliveryFee: init.deliveryFee,
                total: init.total,
                freeMin: 500,
                selectedZoneName: '',
                storeUrl: init.storeUrl,
                addAddrUrl: init.addAddrUrl,
                deliveryFeeUrl: init.deliveryFeeUrl,
                deliveryZones: init.deliveryZones,
                discount: 0,
                couponCode: '',
                couponInput: '',
                couponLoading: false,
                couponError: '',
                form: {
                    address_id: init.defaultId,
                    customer_name: '',
                    phone: '',
                    email: '',
                    area: '',
                    address: '',
                    delivery_zone: '',
                    notes: '',
                    payment_method: 'cod',
                    trx_id: '',
                },

                get totalAfterDiscount() {
                    return Math.max(0, this.subtotal + this.deliveryFee - this.discount);
                },

                get couponApplied() {
                    return this.discount > 0 && this.couponCode;
                },

                init() {
                    const def = this.addresses.find(a => a.id === init.defaultId);
                    if (def) this.selectAddress(def);
                },

                selectAddress(addr) {
                    this.selectedAddressId = addr.id;
                    this.form.address_id = addr.id;
                    this.form.customer_name = addr.recipient_name;
                    this.form.phone = addr.phone;
                    this.form.email = addr.email || '';
                    this.form.area = addr.area || '';
                    this.form.address = addr.address_line;
                    this.form.delivery_zone = addr.zone_name || '';
                    if (this.form.delivery_zone) {
                        this.updateDeliveryFee();
                    }
                },

                clearAddressForm() {
                    this.selectedAddressId = 'new';
                    this.form.address_id = null;
                    this.form.customer_name = '';
                    this.form.phone = '';
                    this.form.email = '';
                    this.form.area = '';
                    this.form.address = '';
                    this.form.delivery_zone = '';
                    this.selectedZoneName = '';
                },

                async updateDeliveryFee() {
                    if (!this.form.delivery_zone) return;
                    try {
                        // Find selected zone details
                        const selectedZone = this.deliveryZones.find(z => z.zone_name === this.form.delivery_zone);
                        if (selectedZone) {
                            this.selectedZoneName = selectedZone.zone_name;
                        }

                        const r = await fetch(
                            `${this.deliveryFeeUrl}?area=${encodeURIComponent(this.form.delivery_zone)}&subtotal=${this.subtotal}`
                        );
                        const d = await r.json();
                        this.deliveryFee = d.delivery_fee;
                        this.freeMin = d.free_min;
                    } catch (e) {
                        console.error('Delivery fee update failed:', e);
                    }
                },

                async applyCoupon() {
                    if (!this.couponInput.trim()) {
                        this.couponError = 'কুপন কোড লিখুন';
                        return;
                    }
                    this.couponLoading = true;
                    this.couponError = '';
                    try {
                        const response = await fetch('/cart/coupon', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({
                                code: this.couponInput.toUpperCase()
                            })
                        });
                        const data = await response.json();
                        if (data.ok) {
                            this.discount = data.discount;
                            this.couponCode = this.couponInput.toUpperCase();
                            this.couponInput = '';
                            this.couponError = '';
                        } else {
                            this.couponError = data.message || 'কুপন কোড সঠিক নয়';
                        }
                    } catch (error) {
                        this.couponError = 'সার্ভার সমস্যা, আবার চেষ্টা করুন';
                    } finally {
                        this.couponLoading = false;
                    }
                },

                async removeCoupon() {
                    try {
                        const response = await fetch('/cart/coupon', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            }
                        });
                        if (response.ok) {
                            this.discount = 0;
                            this.couponCode = '';
                        }
                    } catch (error) {
                        console.error('Failed to remove coupon');
                    }
                },

                async updateQty(id, action) {
                    const r = await fetch(`/cart/update/${id}`, {
                        method: 'PATCH',
                        headers: this._headers(),
                        body: JSON.stringify({
                            action
                        })
                    });
                    const d = await r.json();
                    if (d.ok) this._syncCart(d);
                },

                async removeItem(id) {
                    const r = await fetch(`/cart/remove/${id}`, {
                        method: 'DELETE',
                        headers: this._headers()
                    });
                    const d = await r.json();
                    if (d.ok) this._syncCart(d);
                },

                _syncCart(d) {
                    this.items = d.items;
                    this.subtotal = d.subtotal;
                    this.deliveryFee = d.delivery_fee;
                    this.discount = d.discount ?? this.discount; // ✅ ADD THIS LINE

                    window.dispatchEvent(new CustomEvent('cart-updated', {
                        detail: {
                            count: d.count
                        }
                    }));
                },

                goPayment() {
                    if (!this.form.customer_name?.trim()) {
                        alert('দয়া করে আপনার নাম লিখুন');
                        return;
                    }
                    if (!this.form.phone?.trim()) {
                        alert('দয়া করে ফোন নম্বর লিখুন');
                        return;
                    }
                    if (!this.form.email?.trim()) {
                        alert('দয়া করে ইমেইল ঠিকানা লিখুন');
                        return;
                    }
                    if (!this.form.email.includes('@') || !this.form.email.includes('.')) {
                        alert('সঠিক ইমেইল ঠিকানা দিন');
                        return;
                    }
                    if (!this.form.address?.trim()) {
                        alert('দয়া করে ঠিকানা লিখুন');
                        return;
                    }
                    if (!this.form.delivery_zone) {
                        alert('দয়া করে ডেলিভারি এলাকা নির্বাচন করুন');
                        return;
                    }
                    this.step = 3;
                },

                async placeOrder() {
                    this.error = '';
                    if (!this.form.email || !this.form.email.includes('@')) {
                        this.error = 'সঠিক ইমেইল ঠিকানা দিন';
                        return;
                    }
                    if (this.form.payment_method !== 'cod' && (!this.form.trx_id || this.form.trx_id.length < 6)) {
                        this.error = 'Transaction ID আবশ্যক (কমপক্ষে ৬ অক্ষর)';
                        return;
                    }
                    this.loading = true;
                    try {
                        const fullAddr = (this.form.area ? this.form.area + ', ' : '') + this.form.address;
                        const payload = {
                            customer_name: this.form.customer_name,
                            phone: this.form.phone,
                            email: this.form.email,
                            address: fullAddr,
                            area: this.form.area,
                            delivery_zone: this.form.delivery_zone,
                            notes: this.form.notes,
                            payment_method: this.form.payment_method,
                            trx_id: this.form.trx_id || null,
                            address_id: this.form.address_id
                        };
                        const r = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: this._headers(),
                            body: JSON.stringify(payload)
                        });
                        const d = await r.json();
                        if (!r.ok || !d.ok) {
                            this.error = d.message || 'অর্ডার ব্যর্থ হয়েছে।';
                            return;
                        }
                        this.successInvoice = d.invoice_no;
                        this.successRedirect = d.redirect;
                        this.trackingUrl = `/order/track?invoice=${d.invoice_no}`;
                        this.step = 4;
                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: {
                                count: 0
                            }
                        }));
                    } catch (e) {
                        this.error = 'নেটওয়ার্ক সমস্যা — আবার চেষ্টা করুন';
                    } finally {
                        this.loading = false;
                    }
                },

                _headers() {
                    return {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    };
                },
            }
        }
    </script>
@endsection
