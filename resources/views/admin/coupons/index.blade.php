@extends('admin.layouts.app')
@section('title', 'কুপন')
@section('header', '🎟️ কুপন ম্যানেজমেন্ট')

@section('content')
<div x-data="{ open:false, edit:null }" class="space-y-5">

  <div class="flex justify-end">
    <button @click="open=true; edit=null"
      class="inline-flex items-center gap-2 rounded-xl bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105">
      ➕ নতুন কুপন
    </button>
  </div>

  <div class="overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
          <tr>
            <th class="py-3 px-4 text-left">কোড</th>
            <th class="text-left">টাইপ</th>
            <th class="text-left">মান</th>
            <th class="text-left">মিন অর্ডার</th>
            <th class="text-left">ব্যবহার</th>
            <th class="text-left">মেয়াদ</th>
            <th class="text-center">স্ট্যাটাস</th>
            <th class="text-right pr-4">অ্যাকশন</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($coupons as $c)
            <tr class="border-t border-charcoal/5 hover:bg-cream/50">
              <td class="py-3 px-4">
                <div class="font-display font-bold text-primary">{{ $c->code }}</div>
                @if ($c->label)<div class="text-[10px] text-charcoal/50">{{ $c->label }}</div>@endif
              </td>
              <td>
                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $c->type === 'percent' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                  {{ $c->type === 'percent' ? '%' : 'ফ্ল্যাট' }}
                </span>
              </td>
              <td class="font-bold">{{ $c->type === 'percent' ? $c->value.'%' : '৳'.$c->value }}</td>
              <td>৳{{ number_format($c->min_order) }}</td>
              <td>{{ $c->used_count }}/{{ $c->usage_limit ?? '∞' }}</td>
              <td>{{ $c->expires_at?->format('d M Y') ?? '∞' }}</td>
              <td class="text-center">
                @if ($c->is_active)
                  <span class="rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">সক্রিয়</span>
                @else
                  <span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] font-bold text-gray-500">নিষ্ক্রিয়</span>
                @endif
              </td>
              <td class="py-3 pr-4 text-right">
                <button @click='edit = @json($c); open=true' class="rounded-lg border border-charcoal/15 px-3 py-1 text-xs font-bold hover:border-primary hover:text-primary">এডিট</button>
                <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('মুছে ফেলবেন?')">
                  @csrf @method('DELETE')
                  <button class="rounded-lg border border-red-200 bg-red-50 px-3 py-1 text-xs font-bold text-red-600 hover:bg-red-100">✕</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="py-12 text-center text-charcoal/40">কোনো কুপন নেই — উপরের বাটনে ক্লিক করুন</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="border-t border-charcoal/10 p-4">{{ $coupons->links() }}</div>
  </div>

  {{-- Modal --}}
  <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="open=false">
    <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
      <h3 class="mb-4 font-display text-lg font-bold" x-text="edit ? 'কুপন এডিট' : 'নতুন কুপন'"></h3>
      <form :action="edit ? '/admin/coupons/' + edit.id : '{{ route('admin.coupons.store') }}'" method="POST" class="space-y-3">
        @csrf
        <template x-if="edit">@method('PATCH')</template>
        <div class="grid grid-cols-2 gap-3">
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">কোড</span>
            <input type="text" name="code" :value="edit?.code || ''" required maxlength="40"
              class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm uppercase">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">নাম (ঐচ্ছিক)</span>
            <input type="text" name="label" :value="edit?.label || ''" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">টাইপ</span>
            <select name="type" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
              <option value="percent" :selected="edit?.type === 'percent'">পার্সেন্ট (%)</option>
              <option value="flat" :selected="edit?.type === 'flat'">ফ্ল্যাট (৳)</option>
            </select>
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">মান</span>
            <input type="number" name="value" min="1" :value="edit?.value || ''" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">মিন অর্ডার (৳)</span>
            <input type="number" name="min_order" min="0" :value="edit?.min_order || 0" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">সর্বোচ্চ ছাড় (৳)</span>
            <input type="number" name="max_discount" min="0" :value="edit?.max_discount || ''" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ব্যবহারের সীমা</span>
            <input type="number" name="usage_limit" min="1" :value="edit?.usage_limit || ''" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">মেয়াদ শেষ</span>
            <input type="date" name="expires_at" :value="edit?.expires_at ? edit.expires_at.substring(0,10) : ''" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-sm">
          </label>
        </div>
        <label class="flex items-center gap-2">
          <input type="checkbox" name="is_active" value="1" :checked="edit ? edit.is_active : true" class="h-4 w-4 accent-primary">
          <span class="text-sm font-bold">সক্রিয়</span>
        </label>
        <div class="flex justify-end gap-2 pt-3">
          <button type="button" @click="open=false" class="rounded-xl border border-charcoal/15 px-5 py-2 text-sm font-bold hover:bg-cream">বাতিল</button>
          <button type="submit" class="rounded-xl bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm">সংরক্ষণ</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
