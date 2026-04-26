{{-- resources/views/admin/products/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', $product->exists ? 'পণ্য এডিট' : 'নতুন পণ্য')
@section('header', $product->exists ? 'পণ্য এডিট: ' . $product->name : 'নতুন পণ্য যোগ')

@section('content')
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data"
      class="grid gap-6 lg:grid-cols-[1fr,360px]">
  @csrf
  @if($product->exists) @method('PUT') @endif

  {{-- ── LEFT COLUMN ── --}}
  <div class="space-y-6">

    {{-- মূল তথ্য --}}
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">মূল তথ্য</h2>
      <div class="mt-5 grid gap-4">

        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">পণ্যের নাম *</span>
          <input name="name" value="{{ old('name', $product->name) }}" required
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
        </label>

        <div class="grid gap-4 sm:grid-cols-2">
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ক্যাটাগরি *</span>
            <select name="category_id" required
                    class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
              <option value="">নির্বাচন করুন</option>
              @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>
                  {{ $c->emoji }} {{ $c->name }}
                </option>
              @endforeach
            </select>
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">মূল্য (৳) *</span>
            <input type="number" name="price" min="0"
                   value="{{ old('price', $product->price) }}" required
                   class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          </label>
        </div>

        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">পুরাতন মূল্য (ঐচ্ছিক — discount দেখানোর জন্য)</span>
          <input type="number" name="old_price" min="0"
                 value="{{ old('old_price', $product->old_price) }}"
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
        </label>

        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">সংক্ষিপ্ত বিবরণ *</span>
          <input name="description" value="{{ old('description', $product->description) }}"
                 required maxlength="300"
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
        </label>

        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">বিস্তারিত বিবরণ</span>
          <textarea name="long_description" rows="4"
                    class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">{{ old('long_description', $product->long_description) }}</textarea>
        </label>
      </div>
    </section>

    {{-- ফ্ল্যাগ ও স্ট্যাটাস --}}
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">ফ্ল্যাগ ও স্ট্যাটাস</h2>
      <div class="mt-4 grid gap-3 sm:grid-cols-2">
        @foreach([
          ['popular',      '⭐ জনপ্রিয়'],
          ['spicy',        '🌶️ ঝাল'],
          ['active',       '✅ সক্রিয়'],
          ['show_on_home', '🏠 হোম পেইজে দেখান'],
        ] as [$k, $label])
          <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-charcoal/15 bg-cream px-4 py-3">
            <input type="checkbox" name="{{ $k }}" value="1"
                   @checked(old($k, $product->{$k}))
                   class="h-4 w-4 accent-primary"
                   @if($k === 'show_on_home') id="show_on_home_cb" @endif>
            <span class="text-sm font-bold">{{ $label }}</span>
          </label>
        @endforeach
      </div>

      {{-- Home order --}}
      <div id="homeOrderField" class="mt-4 {{ old('show_on_home', $product->show_on_home) ? '' : 'hidden' }}">
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">হোম পেইজে ক্রম (ছোট সংখ্যা উপরে)</span>
          <input type="number" name="home_order" min="0"
                 value="{{ old('home_order', $product->home_order) }}"
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
        </label>
      </div>
    </section>

    {{-- ✅ স্টক ম্যানেজমেন্ট --}}
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">📦 স্টক ম্যানেজমেন্ট</h2>
      <div class="mt-4 grid gap-4 sm:grid-cols-2">

        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">স্টক পরিমাণ</span>
          <input type="number" name="stock" min="-1" id="stock_input"
                 value="{{ old('stock', $product->stock ?? -1) }}"
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
          <p class="mt-1.5 text-[10px] text-charcoal/45 leading-relaxed">
            <strong>-1</strong> = সীমাহীন স্টক &nbsp;·&nbsp;
            <strong>0</strong> = স্টক শেষ &nbsp;·&nbsp;
            <strong>1+</strong> = নির্দিষ্ট পরিমাণ
          </p>
        </label>

        {{-- Live status preview --}}
        <div class="flex items-center rounded-xl border border-charcoal/10 bg-cream px-4 py-3">
          <div>
            <div class="text-[10px] font-bold uppercase tracking-wider text-charcoal/50">বর্তমান অবস্থা</div>
            <div id="stock_status_label" class="mt-1.5 text-sm font-black">
              @php $s = old('stock', $product->stock ?? -1); @endphp
              @if($s == -1)
                <span class="text-green-600">✅ সীমাহীন স্টক</span>
              @elseif($s == 0)
                <span class="text-red-600">❌ স্টক শেষ</span>
              @elseif($s <= 5)
                <span class="text-orange-600">⚡ কম স্টক ({{ $s }}টি)</span>
              @else
                <span class="text-green-600">✅ স্টকে আছে ({{ $s }}টি)</span>
              @endif
            </div>
            @if($product->exists)
              <div class="mt-2 text-[10px] text-charcoal/40">
                👁 {{ number_format($product->views_count ?? 0) }} বার দেখা হয়েছে
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>

  </div>

  {{-- ── RIGHT COLUMN ── --}}
  <aside class="space-y-6">

    {{-- Image --}}
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">পণ্যের ছবি</h2>
      @if($product->image)
        <img src="{{ $product->image_url }}" alt=""
             class="mt-4 aspect-square w-full rounded-xl object-cover">
      @else
        <div class="mt-4 flex aspect-square items-center justify-center rounded-xl border-2 border-dashed border-charcoal/20 bg-cream text-4xl">
          🍽️
        </div>
      @endif
      <label class="mt-4 block">
        <span class="text-xs font-bold text-charcoal/70">নতুন ছবি (max 2MB)</span>
        <input type="file" name="image_file" accept="image/*"
               class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs
                      file:mr-3 file:rounded-lg file:border-0 file:bg-primary
                      file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
      </label>
    </section>

    <button type="submit"
            class="w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm">
      💾 {{ $product->exists ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
    </button>

    <a href="{{ route('admin.products.index') }}"
       class="block text-center text-xs font-bold text-charcoal/60 hover:text-primary">
      ← বাতিল
    </a>
  </aside>
</form>

@push('scripts')
<script>
  // Show/hide home order field
  const showOnHomeCb   = document.getElementById('show_on_home_cb');
  const homeOrderField = document.getElementById('homeOrderField');
  if (showOnHomeCb && homeOrderField) {
    showOnHomeCb.addEventListener('change', function () {
      homeOrderField.classList.toggle('hidden', !this.checked);
    });
  }

  // Live stock status label
  const stockInput  = document.getElementById('stock_input');
  const stockLabel  = document.getElementById('stock_status_label');
  if (stockInput && stockLabel) {
    stockInput.addEventListener('input', function () {
      const v = parseInt(this.value);
      if (v === -1)      stockLabel.innerHTML = '<span class="text-green-600">✅ সীমাহীন স্টক</span>';
      else if (v === 0)  stockLabel.innerHTML = '<span class="text-red-600">❌ স্টক শেষ</span>';
      else if (v <= 5)   stockLabel.innerHTML = `<span class="text-orange-600">⚡ কম স্টক (${v}টি)</span>`;
      else               stockLabel.innerHTML = `<span class="text-green-600">✅ স্টকে আছে (${v}টি)</span>`;
    });
  }
</script>
@endpush
@endsection