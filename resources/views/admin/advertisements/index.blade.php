@extends('admin.layouts.app')
@section('title', 'বিজ্ঞাপন ম্যানেজমেন্ট')
@section('header', '📢 বিজ্ঞাপন ম্যানেজমেন্ট')

@section('content')
<div x-data="{
  showForm: false,
  editMode: false,
  form: {
    id: null, title: '', body: '', emoji: '🎉', badge: '',
    bg_color: '#c0392b', text_color: '#ffffff', cta_text: '', cta_url: '',
    cta_color: '#ffffff', style: 'popup', show_on_pages: ['all'],
    is_active: true, starts_at: '', ends_at: '', sort_order: 0
  },
  resetForm() {
    this.form = {
      id: null, title: '', body: '', emoji: '🎉', badge: '',
      bg_color: '#c0392b', text_color: '#ffffff', cta_text: '', cta_url: '',
      cta_color: '#ffffff', style: 'popup', show_on_pages: ['all'],
      is_active: true, starts_at: '', ends_at: '', sort_order: 0
    };
    this.editMode = false;
  },
  openEdit(ad) {
    this.form = {
      id: ad.id,
      title: ad.title,
      body: ad.body,
      emoji: ad.emoji ?? '🎉',
      badge: ad.badge ?? '',
      bg_color: ad.bg_color ?? '#c0392b',
      text_color: ad.text_color ?? '#ffffff',
      cta_text: ad.cta_text ?? '',
      cta_url: ad.cta_url ?? '',
      cta_color: ad.cta_color ?? '#ffffff',
      style: ad.style ?? 'popup',
      show_on_pages: ad.show_on_pages ?? ['all'],
      is_active: ad.is_active,
      starts_at: ad.starts_at ?? '',
      ends_at: ad.ends_at ?? '',
      sort_order: ad.sort_order ?? 0,
    };
    this.editMode = true;
    this.showForm = true;
    this.$nextTick(() => document.getElementById('ad-form-top')?.scrollIntoView({ behavior: 'smooth' }));
  }
}" class="space-y-5">

  {{-- Toast --}}
  @if(session('toast'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
      x-transition class="fixed right-4 top-20 z-50 rounded-xl bg-green-500 px-5 py-3 text-sm font-bold text-white shadow-lg">
      {{ session('toast') }}
    </div>
  @endif

  {{-- Header bar --}}
  <div class="flex items-center justify-between">
    <div>
      <p class="text-sm text-gray-500">মোট <strong>{{ $ads->count() }}</strong> টি বিজ্ঞাপন</p>
    </div>
    <button @click="showForm = !showForm; if(!showForm) resetForm()"
      class="inline-flex items-center gap-2 rounded-full bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm transition hover:scale-105">
      <span x-text="showForm ? '✕ বাতিল' : '＋ নতুন বিজ্ঞাপন'"></span>
    </button>
  </div>

  {{-- ===== CREATE / EDIT FORM ===== --}}
  <div x-show="showForm" x-transition id="ad-form-top"
    class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">

    <h2 class="mb-5 font-display text-lg font-bold" x-text="editMode ? '✏️ বিজ্ঞাপন সম্পাদনা করুন' : '➕ নতুন বিজ্ঞাপন তৈরি করুন'"></h2>

    {{-- Edit form --}}
    <template x-if="editMode">
      <form :action="`/admin/advertisements/${form.id}`" method="POST" class="space-y-5">
        @csrf @method('PATCH')
        @include('admin.advertisements._form')
      </form>
    </template>

    {{-- Create form --}}
    <template x-if="!editMode">
      <form action="{{ route('admin.advertisements.store') }}" method="POST" class="space-y-5">
        @csrf
        @include('admin.advertisements._form')
      </form>
    </template>
  </div>

  {{-- ===== ADS LIST ===== --}}
  @if($ads->isEmpty())
    <div class="rounded-2xl border border-dashed border-charcoal/20 bg-white p-12 text-center">
      <div class="text-5xl mb-3">📢</div>
      <h3 class="font-display text-xl font-bold">কোনো বিজ্ঞাপন নেই</h3>
      <p class="mt-1 text-sm text-gray-500">উপরে "নতুন বিজ্ঞাপন" বাটনে ক্লিক করুন</p>
    </div>
  @else
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
      @foreach($ads as $ad)
        <div class="group relative overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft transition hover:shadow-md">

          {{-- Preview strip --}}
          <div class="relative overflow-hidden px-5 py-4" style="background:{{ $ad->bg_color }};color:{{ $ad->text_color }}">
            <div class="pointer-events-none absolute -right-6 -top-6 h-20 w-20 rounded-full opacity-15" style="background:rgba(255,255,255,.3)"></div>
            <div class="flex items-start gap-3 relative">
              <span class="text-3xl leading-none flex-shrink-0">{{ $ad->emoji }}</span>
              <div class="min-w-0 flex-1">
                @if($ad->badge)
                  <span class="mb-1 inline-block rounded-full px-2 py-0.5 text-xs font-bold" style="background:rgba(255,255,255,.2)">{{ $ad->badge }}</span>
                @endif
                <h4 class="font-display text-sm font-black leading-tight">{{ $ad->title }}</h4>
                <p class="mt-0.5 text-xs opacity-75 line-clamp-2">{{ $ad->body }}</p>
              </div>
            </div>
          </div>

          {{-- Meta info --}}
          <div class="p-4 space-y-2">
            <div class="flex flex-wrap items-center gap-2">
              {{-- Status --}}
              <span class="rounded-full px-2.5 py-1 text-xs font-bold
                {{ $ad->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $ad->statusLabel() }}
              </span>
              {{-- Style --}}
              <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600">
                {{ match($ad->style) { 'popup'=>'🪟 পপআপ','banner'=>'📌 ব্যানার','slide'=>'📲 স্লাইড',default=>$ad->style } }}
              </span>
              {{-- Pages --}}
              @foreach($ad->show_on_pages ?? ['all'] as $page)
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600">
                  {{ match($page) { 'all'=>'সব পেজ','home'=>'হোম','menu'=>'মেনু',default=>$page } }}
                </span>
              @endforeach
            </div>

            @if($ad->ends_at)
              <p class="text-xs text-gray-400">
                ⏰ মেয়াদ: {{ $ad->ends_at->format('d M Y') }}
              </p>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-2 pt-1">
              {{-- Toggle --}}
              <form action="{{ route('admin.advertisements.toggle', $ad) }}" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <button class="w-full rounded-xl py-2 text-xs font-bold transition
                  {{ $ad->is_active ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                  {{ $ad->is_active ? '❌ নিষ্ক্রিয় করুন' : '✅ সক্রিয় করুন' }}
                </button>
              </form>

              {{-- Edit --}}
              <button @click="openEdit({{ $ad->toJson() }})"
                class="flex-1 rounded-xl bg-blue-50 py-2 text-xs font-bold text-blue-600 transition hover:bg-blue-100">
                ✏️ সম্পাদনা
              </button>

              {{-- Delete --}}
              <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST"
                onsubmit="return confirm('এই বিজ্ঞাপন মুছে ফেলবেন?')">
                @csrf @method('DELETE')
                <button class="rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-100">🗑️</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>
@endsection