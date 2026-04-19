@extends('admin.layouts.app')
@section('title', 'পিওএস')
@section('header', '💳 পয়েন্ট অফ সেল (POS)')

@section('content')
<div x-data="posSystem()" x-init="init()" class="grid gap-4 lg:grid-cols-[1fr,420px]">

  {{-- ===== LEFT: Products ===== --}}
  <div class="rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft sm:p-5">
    {{-- Search + categories --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
      <div class="relative flex-1">
        <input type="text" x-model="search" @input.debounce.250ms="filter()"
          placeholder="🔍 পণ্য খুঁজুন..."
          class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
      </div>
    </div>

    <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
      <button @click="activeCat=null; filter()" :class="activeCat===null ? 'bg-gradient-warm text-white shadow-warm' : 'bg-cream text-charcoal/70 hover:bg-charcoal/5'"
        class="whitespace-nowrap rounded-full px-4 py-1.5 text-xs font-bold">সব</button>
      @foreach ($categories as $cat)
        <button @click="activeCat='{{ $cat->slug }}'; filter()" :class="activeCat==='{{ $cat->slug }}' ? 'bg-gradient-warm text-white shadow-warm' : 'bg-cream text-charcoal/70 hover:bg-charcoal/5'"
          class="whitespace-nowrap rounded-full px-4 py-1.5 text-xs font-bold">
          {{ $cat->emoji }} {{ $cat->name }}
        </button>
      @endforeach
    </div>

    {{-- Product grid --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 max-h-[70vh] overflow-y-auto pr-1">
      <template x-for="p in filtered" :key="p.id">
        <button @click="addItem(p)" class="group flex flex-col items-stretch overflow-hidden rounded-xl border border-charcoal/10 bg-white text-left transition hover:-translate-y-0.5 hover:border-primary hover:shadow-warm">
          <div class="aspect-square overflow-hidden bg-cream">
            <template x-if="p.image">
              <img :src="p.image" :alt="p.name" class="h-full w-full object-cover transition group-hover:scale-105">
            </template>
            <template x-if="!p.image">
              <div class="flex h-full items-center justify-center text-3xl">🍽️</div>
            </template>
          </div>
          <div class="p-2.5">
            <div class="line-clamp-1 text-xs font-bold" x-text="p.name"></div>
            <div class="mt-1 text-sm font-bold text-primary" x-text="'৳' + p.price"></div>
          </div>
        </button>
      </template>
      <div x-show="filtered.length===0" class="col-span-full py-12 text-center text-charcoal/40">কোনো পণ্য নেই</div>
    </div>
  </div>

  {{-- ===== RIGHT: Cart ===== --}}
  <div class="rounded-2xl border border-charcoal/10 bg-white p-4 shadow-soft sm:p-5 flex flex-col">
    <h2 class="mb-3 font-display text-lg font-bold">🧾 চলমান অর্ডার</h2>

    <div class="grid grid-cols-2 gap-2 mb-3">
      <input type="text" x-model="customerName" placeholder="গ্রাহকের নাম (ঐচ্ছিক)" class="rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs">
      <input type="text" x-model="phone" placeholder="ফোন (ঐচ্ছিক)" class="rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs">
    </div>

    <div class="flex-1 space-y-2 overflow-y-auto max-h-[40vh] mb-3">
      <template x-for="(item, idx) in cart" :key="item.id">
        <div class="flex items-center gap-2 rounded-xl border border-charcoal/10 bg-cream/50 p-2">
          <div class="flex-1 min-w-0">
            <div class="line-clamp-1 text-xs font-bold" x-text="item.name"></div>
            <div class="text-[10px] text-charcoal/55" x-text="'৳' + item.price + ' × ' + item.qty"></div>
          </div>
          <div class="flex items-center gap-1">
            <button @click="dec(idx)" class="flex h-7 w-7 items-center justify-center rounded-lg bg-white border text-sm font-bold hover:border-primary">−</button>
            <span class="w-6 text-center text-xs font-bold" x-text="item.qty"></span>
            <button @click="inc(idx)" class="flex h-7 w-7 items-center justify-center rounded-lg bg-white border text-sm font-bold hover:border-primary">+</button>
          </div>
          <div class="w-16 text-right text-xs font-bold text-primary" x-text="'৳' + (item.price*item.qty)"></div>
          <button @click="cart.splice(idx,1)" class="text-charcoal/30 hover:text-red-500 text-xs">✕</button>
        </div>
      </template>
      <div x-show="cart.length===0" class="text-center text-xs text-charcoal/40 py-12">
        ⬅️ পণ্যে ক্লিক করে কার্টে যোগ করুন
      </div>
    </div>

    <div class="border-t border-charcoal/10 pt-3 space-y-2 text-sm">
      <div class="flex justify-between"><span class="text-charcoal/60">সাব-টোটাল</span><span class="font-bold" x-text="'৳' + subtotal"></span></div>
      <div class="flex items-center justify-between gap-2">
        <span class="text-charcoal/60">ডিসকাউন্ট (৳)</span>
        <input type="number" x-model.number="discount" min="0" class="w-24 rounded-lg border border-charcoal/15 bg-cream px-2 py-1 text-right text-xs">
      </div>
      <div class="flex justify-between border-t border-charcoal/10 pt-2 text-base">
        <span class="font-display font-bold">মোট</span>
        <span class="font-bold text-primary text-lg" x-text="'৳' + total"></span>
      </div>
    </div>

    <div class="mt-3">
      <label class="text-[10px] font-bold uppercase tracking-wider text-charcoal/50">পেমেন্ট মেথড</label>
      <div class="mt-1 grid grid-cols-3 gap-2">
        <template x-for="m in [{v:'cash',l:'💵 ক্যাশ'},{v:'bkash',l:'📱 বিকাশ'},{v:'nagad',l:'📱 নগদ'}]" :key="m.v">
          <button @click="payment=m.v" :class="payment===m.v ? 'bg-gradient-warm text-white shadow-warm' : 'bg-cream text-charcoal/70 hover:bg-charcoal/5'"
            class="rounded-xl px-2 py-2 text-xs font-bold" x-text="m.l"></button>
        </template>
      </div>
    </div>

    <button @click="checkout()" :disabled="cart.length===0 || loading"
      class="mt-4 rounded-full bg-gradient-warm py-3 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
      <span x-show="!loading">✅ অর্ডার সম্পন্ন করুন</span>
      <span x-show="loading">⏳ প্রসেস হচ্ছে...</span>
    </button>
    <button @click="cart=[]; discount=0" x-show="cart.length>0" class="mt-2 text-xs font-bold text-charcoal/40 hover:text-red-500">🗑️ কার্ট খালি করুন</button>
  </div>

  {{-- Success Modal --}}
  <div x-show="showSuccess" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="rounded-3xl bg-white p-8 text-center shadow-2xl max-w-sm w-full" @click.outside="showSuccess=false">
      <div class="text-6xl mb-3">🎉</div>
      <h3 class="font-display text-2xl font-bold mb-2">অর্ডার সফল!</h3>
      <p class="text-sm text-charcoal/60 mb-1">ইনভয়েস নং</p>
      <p class="font-display font-bold text-primary text-lg mb-4" x-text="lastInvoice"></p>
      <p class="text-2xl font-bold mb-6">৳<span x-text="lastTotal"></span></p>
      <div class="flex gap-2">
        <button @click="showSuccess=false" class="flex-1 rounded-xl border border-charcoal/15 py-3 text-sm font-bold hover:bg-cream">নতুন অর্ডার</button>
        <a :href="'/admin/orders/' + lastOrderId" class="flex-1 rounded-xl bg-gradient-warm py-3 text-sm font-bold text-white shadow-warm">দেখুন</a>
      </div>
    </div>
  </div>
</div>

<script>
function posSystem() {
  return {
products: @json($productsData),    filtered: [],
    search: '',
    activeCat: null,
    cart: [],
    customerName: '',
    phone: '',
    payment: 'cash',
    discount: 0,
    loading: false,
    showSuccess: false,
    lastInvoice: '',
    lastTotal: 0,
    lastOrderId: null,

    init() { this.filtered = this.products; },
    filter() {
      const q = this.search.toLowerCase();
      this.filtered = this.products.filter(p =>
        (!this.activeCat || p.category === this.activeCat) &&
        (!q || p.name.toLowerCase().includes(q))
      );
    },
    addItem(p) {
      const ex = this.cart.find(i => i.id === p.id);
      if (ex) ex.qty++;
      else this.cart.push({...p, qty: 1});
    },
    inc(i) { this.cart[i].qty++; },
    dec(i) { if (--this.cart[i].qty <= 0) this.cart.splice(i, 1); },
    get subtotal() { return this.cart.reduce((s,i) => s + i.price*i.qty, 0); },
    get total() { return Math.max(0, this.subtotal - (this.discount || 0)); },

    async checkout() {
      if (this.cart.length === 0) return;
      this.loading = true;
      try {
        const res = await fetch('{{ route("admin.pos.store") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            customer_name: this.customerName,
            phone: this.phone,
            payment_method: this.payment,
            discount: this.discount || 0,
            items: this.cart.map(i => ({ id: i.id, qty: i.qty })),
          }),
        });
        const j = await res.json();
        if (j.ok) {
          this.lastInvoice = j.invoice_no;
          this.lastTotal = j.total;
          this.lastOrderId = j.order_id;
          this.showSuccess = true;
          this.cart = [];
          this.customerName = '';
          this.phone = '';
          this.discount = 0;
        } else {
          alert('ত্রুটি: ' + (j.message || 'অজানা সমস্যা'));
        }
      } catch (e) {
        alert('নেটওয়ার্ক ত্রুটি');
      } finally {
        this.loading = false;
      }
    },
  };
}
</script>
@endsection
