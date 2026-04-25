@extends('admin.layouts.app')
@section('title', 'পণ্য')
@section('header', 'পণ্য ম্যানেজমেন্ট')

@section('content')
    <div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center gap-3 border-b border-charcoal/8 p-4">
            <form method="GET" class="flex flex-1 flex-wrap gap-2 min-w-[260px]">
                <div class="relative flex-1 min-w-[160px]">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/35 text-sm">🔍</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="নাম দিয়ে খুঁজুন..."
                        class="w-full rounded-full border border-charcoal/15 bg-cream py-2 pl-9 pr-4 text-sm focus:border-primary focus:outline-none">
                </div>
                <select name="category_id"
                    class="rounded-full border border-charcoal/15 bg-cream px-4 py-2 text-sm focus:border-primary focus:outline-none cursor-pointer">
                    <option value="">সব ক্যাটাগরি</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>
                            {{ $c->emoji }} {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                <button
                    class="rounded-full bg-charcoal px-5 py-2 text-sm font-bold text-cream hover:bg-charcoal/85 transition">
                    খুঁজুন
                </button>
            </form>
            <a href="{{ route('admin.products.trash') }}" class="rounded-full bg-cream px-4 py-2 text-sm font-bold">🗑️
                ট্র্যাশ ({{ $trashCount ?? 0 }})</a>
            <a href="{{ route('admin.products.create') }}"
                class="rounded-full bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm transition hover:scale-105">
                ➕ নতুন পণ্য
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-cream/80 text-xs uppercase tracking-wider text-charcoal/50">
                        <th class="py-3 px-5 text-left">পণ্য</th>
                        <th class="py-3 px-4 text-left">ক্যাটাগরি</th>
                        <th class="py-3 px-4 text-right">মূল্য</th>
                        <th class="py-3 px-4 text-center">ফ্ল্যাগ</th>
                        <th class="py-3 px-4 text-center">স্ট্যাটাস</th>
                        <th class="py-3 px-5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                        <tr class="border-t border-charcoal/5 hover:bg-cream/40 transition-colors">
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-cream">
                                        @if ($p->image)
                                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-2xl">🍽️</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $p->name }}</div>
                                        <div class="text-xs text-charcoal/45 truncate max-w-xs">{{ $p->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-charcoal/60">
                                {{ $p->category->emoji ?? '' }} {{ $p->category->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="font-bold text-primary">৳{{ number_format($p->price) }}</div>
                                @if ($p->old_price)
                                    <div class="text-[11px] text-charcoal/35 line-through">
                                        ৳{{ number_format($p->old_price) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-base space-x-0.5">
                                @if ($p->popular)
                                    <span title="জনপ্রিয়">⭐</span>
                                @endif
                                @if ($p->spicy)
                                    <span title="ঝাল">🌶️</span>
                                @endif
                            </td>

                            {{-- Status Toggle Form - Fixed Version --}}
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.products.toggle-status', $p->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('{{ addslashes($p->name) }} পণ্যটি {{ $p->active ? 'নিষ্ক্রিয়' : 'সক্রিয়' }} করতে চান?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="active" value="{{ $p->active ? 0 : 1 }}">
                                    <button type="submit"
                                        class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase transition-all hover:scale-105 cursor-pointer
                               {{ $p->active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-charcoal/10 text-charcoal/50 hover:bg-charcoal/20' }}">
                                        {{ $p->active ? '✅ সক্রিয়' : '⛔ নিষ্ক্রিয়' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3 px-5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.products.edit', $p) }}"
                                        class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold transition hover:border-primary hover:text-primary">
                                        ✏️ এডিট
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST"
                                        onsubmit="return confirm('পণ্যটি মুছে ফেলবেন?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-bold text-red-500 transition hover:bg-red-50">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-charcoal/40">
                                <div class="text-4xl mb-3">🍽️</div>
                                কোনো পণ্য নেই
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-charcoal/8 p-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
