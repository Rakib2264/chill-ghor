@extends('layouts.app')
@section('title', 'আমার প্রোফাইল — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  <div class="flex items-center justify-between mb-8">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-primary">প্রোফাইল</p>
      <h1 class="mt-1 font-display text-3xl font-bold">আমার অ্যাকাউন্ট</h1>
    </div>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="text-sm font-bold text-charcoal/60 hover:text-primary">লগআউট →</button>
    </form>
  </div>

  <div class="grid gap-8 lg:grid-cols-[320px,1fr]">

    {{-- Sidebar --}}
    <div class="space-y-6">
      {{-- Profile Card --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft text-center">
        <div class="relative mx-auto w-28">
          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
               class="h-28 w-28 rounded-full object-cover border-4 border-white shadow-soft">
          <label for="avatar-input" class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-gradient-warm text-white shadow-warm hover:scale-110 transition">
            📷
          </label>
        </div>
        
        <h2 class="mt-4 font-display text-xl font-bold">{{ $user->name }}</h2>
        <p class="text-sm text-charcoal/60">{{ $user->email }}</p>
        @if($user->phone)
          <p class="text-sm text-charcoal/60">{{ $user->phone }}</p>
        @endif

        {{-- Stats --}}
        <div class="mt-6 grid grid-cols-3 gap-2 border-t border-charcoal/10 pt-4">
          <div>
            <div class="text-xl font-bold text-primary">{{ $totalOrders }}</div>
            <div class="text-[10px] text-charcoal/50">অর্ডার</div>
          </div>
          <div>
            <div class="text-xl font-bold text-primary">৳{{ number_format($totalSpent) }}</div>
            <div class="text-[10px] text-charcoal/50">খরচ</div>
          </div>
          <div>
            <div class="text-xl font-bold text-primary">{{ $user->created_at->format('Y') }}</div>
            <div class="text-[10px] text-charcoal/50">যোগদান</div>
          </div>
        </div>
      </div>

      {{-- Navigation --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-3 shadow-soft">
        <nav class="space-y-1">
          <a href="{{ route('profile.index') }}" 
             class="flex items-center gap-3 rounded-xl bg-primary/10 px-4 py-3 text-sm font-bold text-primary">
            👤 প্রোফাইল
          </a>
          <a href="{{ route('profile.orders') }}" 
             class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-charcoal/70 hover:bg-charcoal/5 transition">
            📋 অর্ডার হিস্টরি
          </a>
        </nav>
      </div>
    </div>

    {{-- Main Content --}}
    <div class="space-y-6">
      {{-- Edit Profile Form --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h3 class="font-display text-lg font-bold mb-5">প্রোফাইল এডিট</h3>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf
          @method('PATCH')

          {{-- Hidden file input for avatar --}}
          <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">

          <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
              <span class="text-xs font-bold text-charcoal/70">নাম</span>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                     class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
            </label>
            <label class="block">
              <span class="text-xs font-bold text-charcoal/70">ইমেইল</span>
              <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                     class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
            </label>
          </div>

          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ফোন</span>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                   class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          </label>

          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">ঠিকানা</span>
            <textarea name="address" rows="3" 
                      class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">{{ old('address', $user->address) }}</textarea>
          </label>

          <button type="submit" 
                  class="rounded-full bg-gradient-warm px-6 py-2.5 text-sm font-bold text-white shadow-warm">
            💾 আপডেট করুন
          </button>
        </form>
      </div>

      {{-- Change Password --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h3 class="font-display text-lg font-bold mb-5">পাসওয়ার্ড পরিবর্তন</h3>
        
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
          @csrf
          @method('PATCH')

          <label class="block">
            <span class="text-xs font-bold text-charcoal/70">বর্তমান পাসওয়ার্ড</span>
            <input type="password" name="current_password" required 
                   class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
          </label>

          <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
              <span class="text-xs font-bold text-charcoal/70">নতুন পাসওয়ার্ড</span>
              <input type="password" name="password" required 
                     class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
            </label>
            <label class="block">
              <span class="text-xs font-bold text-charcoal/70">পাসওয়ার্ড নিশ্চিত করুন</span>
              <input type="password" name="password_confirmation" required 
                     class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
            </label>
          </div>

          <button type="submit" 
                  class="rounded-full border-2 border-primary bg-white px-6 py-2.5 text-sm font-bold text-primary hover:bg-primary hover:text-white transition">
            🔐 পাসওয়ার্ড পরিবর্তন
          </button>
        </form>
      </div>

      {{-- Recent Orders --}}
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-display text-lg font-bold">সাম্প্রতিক অর্ডার</h3>
          <a href="{{ route('profile.orders') }}" class="text-xs font-bold text-primary hover:underline">সব দেখুন →</a>
        </div>

        @if($recentOrders->isEmpty())
          <p class="text-center text-charcoal/50 py-8">এখনো কোনো অর্ডার নেই</p>
        @else
          <div class="space-y-3">
            @foreach($recentOrders as $order)
              <a href="{{ route('profile.orders.show', $order) }}" 
                 class="flex items-center justify-between rounded-xl border border-charcoal/10 p-4 hover:border-primary transition">
                <div>
                  <div class="font-mono font-bold text-primary">{{ $order->invoice_no }}</div>
                  <div class="text-xs text-charcoal/50">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="text-right">
                  <div class="font-bold">৳{{ number_format($order->total) }}</div>
                  @include('admin.partials.status-badge', ['status' => $order->status])
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection