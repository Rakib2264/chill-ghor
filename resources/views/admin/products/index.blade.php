@extends('admin.layouts.app')
@section('title', 'পণ্য')
@section('header', 'পণ্য ম্যানেজমেন্ট')

@section('content')
<div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft">
  <div class="flex flex-wrap items-center gap-3 border-b border-charcoal/10 p-4">
    <form method="GET" class="flex flex-1 flex-wrap gap-2 min-w-[280px]">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="নাম দিয়ে খুঁজুন..."
             class="flex-1 rounded-full border border-charcoal/15 bg-cream px-4 py-2 text-sm focus:border-primary focus:outline-none">
      <select name="category_id" class="rounded-full border border-charcoal/15 bg-cream px-4 py-2 text-sm">
        <option value="">সব ক্যাটাগরি</option>
        @foreach ($categories as $c)
          <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->emoji }} {{ $c->name }}</option>
        @endforeach
      </select>
      <button class="rounded-full bg-charcoal px-5 text-sm font-bold text-cream">খুঁজুন</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="rounded-full bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm">➕ নতুন পণ্য</a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
        <tr>
          <th class="py-3 px-4 text-left">পণ্য</th>
          <th class="text-left">ক্যাটাগরি</th>
          <th class="text-right">মূল্য</th>
          <th class="text-center">ফ্ল্যাগ</th>
          <th class="text-center">স্ট্যাটাস</th>
          <th class="text-right pr-4">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($products as $p)
          <tr class="border-t border-charcoal/5 hover:bg-cream/50">
            <td class="py-3 px-4">
              <div class="flex items-center gap-3">
                <img src="{{ $p->image_url }}" alt="" class="h-12 w-12 rounded-lg object-cover">
                <div>
                  <div class="font-bold">{{ $p->name }}</div>
                  <div class="text-xs text-charcoal/50 truncate max-w-xs">{{ $p->description }}</div>
                </div>
              </div>
            </td>
            <td>{{ $p->category->emoji ?? '' }} {{ $p->category->name ?? '—' }}</td>
            <td class="text-right">
              <div class="font-bold text-primary">৳{{ number_format($p->price) }}</div>
              @if ($p->old_price)<div class="text-xs text-charcoal/40 line-through">৳{{ number_format($p->old_price) }}</div>@endif
            </td>
            <td class="text-center text-base">
              @if ($p->popular) <span title="জনপ্রিয়">⭐</span> @endif
              @if ($p->spicy) <span title="ঝাল">🌶️</span> @endif
            </td>
            <td class="text-center">
              <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase {{ $p->active ? 'bg-emerald-100 text-emerald-700' : 'bg-charcoal/10 text-charcoal/60' }}">
                {{ $p->active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
              </span>
            </td>
            <td class="py-3 pr-4 text-right">
              <div class="inline-flex gap-1">
                <a href="{{ route('admin.products.edit', $p) }}" class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">✏️ এডিট</a>
                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('পণ্যটি মুছে ফেলবেন?')">
                  @csrf @method('DELETE')
                  <button class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="py-12 text-center text-charcoal/50">কোনো পণ্য নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="border-t border-charcoal/10 p-4">{{ $products->links() }}</div>
</div>
@endsection
