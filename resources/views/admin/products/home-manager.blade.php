@extends('admin.layouts.app')
@section('title', 'হোম পেইজ প্রোডাক্ট ম্যানেজমেন্ট')
@section('header', 'হোম পেইজে কোন প্রোডাক্ট দেখাবে?')

@section('content')
<div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft overflow-hidden">
  <div class="border-b border-charcoal/8 bg-cream/50 p-5">
    <p class="text-sm text-charcoal/60">
      📌 এখানে সিলেক্ট করুন কোন প্রোডাক্টগুলো হোম পেইজের "আজকের বেস্ট" সেকশনে দেখাবে।
      <br>🚀 ড্র্যাগ করে অর্ডার পরিবর্তন করতে পারেন।
    </p>
  </div>

  <div class="p-6">
    <div class="mb-6 flex items-center justify-between">
      <h3 class="font-display text-lg font-bold">🏠 হোম পেইজের প্রোডাক্ট</h3>
      <button id="saveOrderBtn" class="rounded-full bg-primary px-5 py-2.5 text-sm font-bold text-white transition hover:opacity-90">
        💾 অর্ডার সেভ করুন
      </button>
    </div>

    <div id="sortable-list" class="space-y-3">
      @forelse ($homeProducts as $product)
        <div data-id="{{ $product->id }}" data-order="{{ $product->home_order }}"
             class="sortable-item flex cursor-move items-center gap-4 rounded-xl border border-charcoal/10 bg-white p-4 shadow-sm transition hover:shadow-md">
          <div class="flex-shrink-0 text-2xl text-charcoal/30">⋮⋮</div>
          <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-cream">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
          </div>
          <div class="flex-1">
            <div class="font-bold">{{ $product->name }}</div>
            <div class="text-xs text-charcoal/50">{{ $product->category->emoji ?? '' }} {{ $product->category->name ?? '' }}</div>
          </div>
          <div class="text-right">
            <div class="font-bold text-primary">৳{{ number_format($product->price) }}</div>
            <div class="text-xs text-charcoal/40">অর্ডার: <span class="order-badge font-bold">{{ $product->home_order }}</span></div>
          </div>
        </div>
      @empty
        <div class="rounded-xl border-2 border-dashed border-charcoal/15 bg-cream/30 p-12 text-center">
          <div class="mb-3 text-5xl">🏠</div>
          <p class="text-charcoal/50">কোন প্রোডাক্ট হোম পেইজের জন্য সিলেক্ট করা হয়নি</p>
          <p class="mt-2 text-sm text-charcoal/40">প্রোডাক্ট এডিট করে "হোম পেইজে দেখান" চেক করুন</p>
          <a href="{{ route('admin.products.index') }}" class="mt-4 inline-block rounded-full bg-primary px-5 py-2 text-sm font-bold text-white">
            📝 প্রোডাক্টে যান
          </a>
        </div>
      @endforelse
    </div>

    <div class="mt-6 rounded-lg bg-cream/50 p-4">
      <div class="flex items-start gap-3 text-sm">
        <span class="text-primary">💡</span>
        <div class="text-charcoal/60">
          <p class="font-bold">টিপস:</p>
          <ul class="mt-1 list-inside list-disc space-y-0.5">
            <li>শুধু "🏠 হোম পেইজে দেখান" চেক করা প্রোডাক্টগুলো এখানে দেখাবে</li>
            <li>অর্ডার নম্বর যত ছোট, প্রোডাক্ট তত উপরে দেখাবে</li>
            <li>ড্র্যাগ করে অর্ডার পরিবর্তন করে "সেভ" বাটনে ক্লিক করুন</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.12/lib/draggable.bundle.css">
<style>
  .sortable-item {
    transition: all 0.2s ease;
  }
  .sortable-item.sortable-drag {
    opacity: 0.5;
    background: #fef3e8;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
  }
  .sortable-item.sortable-ghost {
    opacity: 0.3;
    border: 2px dashed #c0392b;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.12/lib/draggable.bundle.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('#sortable-list');
    if (!container || container.children.length === 0) return;

    let items = [];

    // Initialize draggable
    const sortable = new Draggable.Sortable(container, {
      draggable: '.sortable-item',
      mirror: {
        constrainDimensions: true,
      },
      plugins: [Draggable.Plugins.SortAnimation],
    });

    // Store order after drag
    sortable.on('sortable:stop', () => {
      updateOrderNumbers();
    });

    function updateOrderNumbers() {
      const items = document.querySelectorAll('.sortable-item');
      items.forEach((item, index) => {
        const orderSpan = item.querySelector('.order-badge');
        if (orderSpan) {
          orderSpan.textContent = index + 1;
        }
      });
    }

    // Save order to server
    document.getElementById('saveOrderBtn')?.addEventListener('click', async function() {
      const items = document.querySelectorAll('.sortable-item');
      const orderData = [];

      items.forEach((item, index) => {
        orderData.push({
          id: item.dataset.id,
          order: index + 1
        });
      });

      const saveBtn = this;
      const originalText = saveBtn.innerHTML;
      saveBtn.innerHTML = '⏳ সেভ হচ্ছে...';
      saveBtn.disabled = true;

      try {
        const response = await fetch('{{ route("admin.products.update-home-order") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ products: orderData })
        });

        const result = await response.json();

        if (result.success) {
          saveBtn.innerHTML = '✅ সেভ হয়েছে!';
          setTimeout(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
          }, 2000);
          
          // Refresh the order badges
          items.forEach((item, index) => {
            const orderSpan = item.querySelector('.order-badge');
            if (orderSpan) {
              orderSpan.textContent = index + 1;
              item.dataset.order = index + 1;
            }
          });
        } else {
          throw new Error('Failed to save');
        }
      } catch (error) {
        saveBtn.innerHTML = '❌ ব্যর্থ! আবার চেষ্টা করুন';
        setTimeout(() => {
          saveBtn.innerHTML = originalText;
          saveBtn.disabled = false;
        }, 2000);
        console.error('Error saving order:', error);
      }
    });
  });
</script>
@endpush
@endsection