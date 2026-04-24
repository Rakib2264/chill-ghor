{{-- resources/views/admin/products/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', $product->exists ? 'পণ্য এডিট' : 'নতুন পণ্য')
@section('header', $product->exists ? 'পণ্য এডিট: ' . $product->name : 'নতুন পণ্য যোগ')

@section('content')
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data"
      class="grid gap-6 lg:grid-cols-[1fr,360px]">
  @csrf
  @if ($product->exists) @method('PUT') @endif

  <div class="space-y-6">
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">মূল তথ্য</h2>
      <div class="mt-5 grid gap-4">
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">পণ্যের নাম *</span>
          <input name="name" value="{{ old('name', $product->name) }}" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
        </label>

        <div class="grid gap-4 sm:grid-cols-2">
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ক্যাটাগরি *</span>
            <select name="category_id" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
              <option value="">নির্বাচন করুন</option>
              @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->emoji }} {{ $c->name }}</option>
              @endforeach
            </select>
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">মূল্য (৳) *</span>
            <input type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          </label>
        </div>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">পুরাতন মূল্য (ঐচ্ছিক, discount দেখানোর জন্য)</span>
          <input type="number" name="old_price" min="0" value="{{ old('old_price', $product->old_price) }}" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
        </label>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">সংক্ষিপ্ত বিবরণ *</span>
          <input name="description" value="{{ old('description', $product->description) }}" required maxlength="300" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
        </label>
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">বিস্তারিত বিবরণ</span>
          <textarea name="long_description" rows="4" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">{{ old('long_description', $product->long_description) }}</textarea>
        </label>
      </div>
    </section>

    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">ফ্ল্যাগ ও স্ট্যাটাস</h2>
      <div class="mt-4 grid gap-3 sm:grid-cols-4">
        @foreach ([
          ['popular','⭐ জনপ্রিয়'],
          ['spicy','🌶️ ঝাল'],
          ['active','✅ সক্রিয়'],
          ['show_on_home','🏠 হোম পেইজে দেখান']
        ] as [$k, $label])
          <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-charcoal/15 bg-cream px-4 py-3">
            <input type="checkbox" name="{{ $k }}" value="1" @checked(old($k, $product->{$k})) class="h-4 w-4 accent-primary">
            <span class="text-sm font-bold">{{ $label }}</span>
          </label>
        @endforeach
      </div>

      {{-- Home Order field - only show when product is set to show on home page --}}
      <div id="homeOrderField" class="mt-4 {{ old('show_on_home', $product->show_on_home) ? '' : 'hidden' }}">
        <label class="block">
          <span class="text-xs font-bold text-charcoal/70">হোম পেইজে অর্ডার (ছোট সংখ্যা উপরে দেখাবে)</span>
          <input type="number" name="home_order" min="0" value="{{ old('home_order', $product->home_order) }}" 
                 class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          <p class="mt-1 text-xs text-charcoal/50">যেমন: ১, ২, ৩ — ১ সবচেয়ে উপরে দেখাবে</p>
        </label>
      </div>
    </section>
  </div>

  <aside class="space-y-6">
    <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
      <h2 class="font-display text-lg font-bold">পণ্যের ছবি</h2>
      @if ($product->image)
        <img src="{{ $product->image_url }}" alt="" class="mt-4 aspect-square w-full rounded-xl object-cover">
      @else
        <div class="mt-4 flex aspect-square items-center justify-center rounded-xl border-2 border-dashed border-charcoal/20 bg-cream text-4xl">🍽️</div>
      @endif
      <label class="mt-4 block">
        <span class="text-xs font-bold text-charcoal/70">নতুন ছবি আপলোড (max 2 MB)</span>
        <input type="file" name="image_file" accept="image/*" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
      </label>
    </section>

    <button type="submit" class="w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm">
      💾 {{ $product->exists ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="block text-center text-xs font-bold text-charcoal/60 hover:text-primary">← বাতিল</a>
  </aside>
</form>

@push('scripts')
<script>
  // Show/hide home order field based on checkbox selection
  const showOnHomeCheckbox = document.querySelector('input[name="show_on_home"]');
  const homeOrderField = document.getElementById('homeOrderField');
  
  if (showOnHomeCheckbox && homeOrderField) {
    showOnHomeCheckbox.addEventListener('change', function() {
      if (this.checked) {
        homeOrderField.classList.remove('hidden');
      } else {
        homeOrderField.classList.add('hidden');
      }
    });
  }
</script>
@endpush
@endsection