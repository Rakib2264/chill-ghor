@extends('admin.layouts.app')
@section('title', 'ব্যবহারকারী')
@section('header', 'ব্যবহারকারী')

@section('content')
<div class="rounded-2xl bg-white p-5 shadow-soft mb-4">
  <form method="GET" class="flex gap-2">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="🔍 নাম, ইমেইল, ফোন দিয়ে খুঁজুন"
           class="flex-1 rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
    <button class="rounded-xl bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm">খুঁজুন</button>
    @if($q)<a href="{{ route('admin.users.index') }}" class="rounded-xl border border-charcoal/15 px-5 py-2.5 text-sm font-bold">রিসেট</a>@endif
  </form>
</div>

<div class="rounded-2xl bg-white shadow-soft overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream text-xs font-bold uppercase tracking-wider text-charcoal/60">
        <tr>
          <th class="px-5 py-3 text-left">ব্যবহারকারী</th>
          <th class="text-left">যোগাযোগ</th>
          <th class="text-center">অর্ডার</th>
          <th class="text-center">ভূমিকা</th>
          <th class="text-center">যোগদান</th>
          <th class="px-5 text-right">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $u)
          <tr class="border-t border-charcoal/5 hover:bg-cream/40">
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <img src="{{ $u->avatar_url }}" class="h-9 w-9 rounded-full" alt="">
                <div>
                  <div class="font-bold">{{ $u->name }}</div>
                  <div class="text-xs text-charcoal/55">#{{ $u->id }}</div>
                </div>
              </div>
            </td>
            <td>
              <div class="text-xs">{{ $u->email }}</div>
              <div class="text-xs text-charcoal/55">{{ $u->phone ?: '—' }}</div>
            </td>
            <td class="text-center font-bold">{{ $u->orders_count }}</td>
            <td class="text-center">
              @if($u->is_admin)
                <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">👑 অ্যাডমিন</span>
              @else
                <span class="rounded-full bg-charcoal/8 px-3 py-1 text-xs font-bold text-charcoal/70">গ্রাহক</span>
              @endif
            </td>
            <td class="text-center text-xs text-charcoal/60">{{ $u->created_at->format('M d, Y') }}</td>
            <td class="px-5 py-3 text-right">
              <div class="inline-flex gap-1.5">
                <form action="{{ route('admin.users.admin', $u) }}" method="POST">@csrf @method('PATCH')
                  <button class="rounded-lg border border-charcoal/15 px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">
                    {{ $u->is_admin ? 'অ্যাডমিন সরান' : 'অ্যাডমিন করুন' }}
                  </button>
                </form>
                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('নিশ্চিত মুছবেন?')">@csrf @method('DELETE')
                  <button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100">মুছুন</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-12 text-charcoal/50">কোনো ব্যবহারকারী নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if ($users->hasPages())
    <div class="border-t border-charcoal/8 px-5 py-3">{{ $users->links() }}</div>
  @endif
</div>
@endsection
