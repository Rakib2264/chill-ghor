@extends('admin.layouts.app')
@section('title', 'ক্যাটাগরি')
@section('header', 'ক্যাটাগরি ম্যানেজমেন্ট')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr,320px]">

  <div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft">
    <table class="w-full text-sm">
      <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
        <tr><th class="py-3 px-4 text-left">নাম</th><th class="text-center">পণ্য সংখ্যা</th><th class="text-right pr-4">অ্যাকশন</th></tr>
      </thead>
      <tbody>
        @forelse ($categories as $c)
          <tr class="border-t border-charcoal/5">
            <td class="py-3 px-4">
              <form action="{{ route('admin.categories.update', $c) }}" method="POST" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <input type="text" name="emoji" value="{{ $c->emoji }}" maxlength="4" class="w-14 rounded-lg border border-charcoal/15 bg-cream px-2 py-1.5 text-center">
                <input type="text" name="name" value="{{ $c->name }}" required class="flex-1 rounded-lg border border-charcoal/15 bg-cream px-3 py-1.5">
                <button class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">💾</button>
              </form>
            </td>
            <td class="text-center"><span class="rounded-full bg-charcoal/5 px-2.5 py-1 text-xs font-bold">{{ $c->products_count }}</span></td>
            <td class="py-3 pr-4 text-right">
              <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" onsubmit="return confirm('মুছে ফেলবেন?')" class="inline">
                @csrf @method('DELETE')
                <button class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50">🗑️</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="py-12 text-center text-charcoal/50">কোনো ক্যাটাগরি নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <aside class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft h-fit">
    <h2 class="font-display font-bold">নতুন ক্যাটাগরি</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="mt-4 space-y-3">
      @csrf
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">ইমোজি</span>
        <input name="emoji" maxlength="4" placeholder="🍛" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-center text-2xl">
      </label>
      <label class="block">
        <span class="text-xs font-bold text-charcoal/70">নাম *</span>
        <input name="name" required class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
      </label>
      <button class="w-full rounded-full bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm">➕ যোগ করুন</button>
    </form>
  </aside>

</div>
@endsection
