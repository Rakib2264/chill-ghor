@extends('admin.layouts.app')
@section('title', 'সেটিংস')
@section('header', '⚙️ সাইট সেটিংস')

@section('content')
<div x-data="{ tab: '{{ $groups->first() ?? 'general' }}' }" class="space-y-5">

  {{-- Tabs --}}
  <div class="flex gap-1.5 overflow-x-auto rounded-2xl bg-white p-1.5 shadow-soft border border-charcoal/8">
    @foreach ($groups as $group)
      <button type="button" @click="tab='{{ $group }}'"
        :class="tab==='{{ $group }}' ? 'bg-gradient-warm text-white shadow-warm' : 'text-charcoal/65 hover:bg-cream'"
        class="flex-1 min-w-fit whitespace-nowrap rounded-xl px-5 py-2.5 text-center text-sm font-bold transition capitalize">
        {{ $group }}
      </button>
    @endforeach
  </div>

  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
    @csrf
    @method('PATCH')

    @foreach ($groups as $group)
      <div x-show="tab==='{{ $group }}'" x-cloak class="rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft sm:p-6">
        <div class="mb-5 flex items-center gap-2 border-b border-charcoal/10 pb-4">
          <h2 class="font-display text-lg font-bold capitalize">{{ $group }}</h2>
          <span class="text-xs text-charcoal/50">— {{ count($settings[$group] ?? []) }} টি ফিল্ড</span>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
          @foreach($settings[$group] ?? [] as $setting)
            <div class="{{ in_array($setting->type, ['textarea','json']) ? 'md:col-span-2' : '' }}">
              <label class="block">
                <span class="text-xs font-bold text-charcoal/70">{{ $setting->label ?? $setting->key }}</span>

                @if($setting->type === 'image')
                  @if($setting->value)
                    <div class="mt-2 mb-2">
                      <img src="{{ asset($setting->value) }}" alt="{{ $setting->key }}"
                        class="h-24 w-24 rounded-xl object-cover border border-charcoal/10">
                      <input type="hidden" name="settings[{{ $setting->key }}][existing]" value="{{ $setting->value }}">
                    </div>
                  @endif
                  <input type="file" name="settings[{{ $setting->key }}][file]" accept="image/*"
                         class="block w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-1 file:text-xs file:font-bold file:text-white">
                  <small class="mt-1 block text-[10px] text-charcoal/50">নতুন ছবি আপলোড করতে চাইলে সিলেক্ট করুন</small>

                @elseif($setting->type === 'textarea')
                  <textarea name="settings[{{ $setting->key }}][value]" rows="3"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">{{ $setting->value }}</textarea>

                @elseif($setting->type === 'boolean')
                  <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                    <input type="hidden" name="settings[{{ $setting->key }}][value]" value="0">
                    <input type="checkbox" name="settings[{{ $setting->key }}][value]" value="1"
                           {{ $setting->value ? 'checked' : '' }} class="h-5 w-5 accent-primary">
                    <span class="text-sm">চালু করুন</span>
                  </label>

                @elseif($setting->type === 'number')
                  <input type="number" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}"
                         class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">

                @elseif($setting->type === 'json')
                  <textarea name="settings[{{ $setting->key }}][value]" rows="6"
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-xs font-mono focus:border-primary focus:outline-none">{{ $setting->value }}</textarea>
                  <small class="mt-1 block text-[10px] text-charcoal/50">JSON ফরম্যাটে ডাটা লিখুন</small>

                @else
                  <input type="text" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}"
                         class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none">
                @endif
              </label>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach

    {{-- Sticky Save Bar --}}
    <div class="sticky bottom-4 z-10 flex justify-end">
      <button type="submit"
        class="inline-flex items-center gap-2 rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm hover:scale-105 transition">
        💾 সেটিংস সংরক্ষণ করুন
      </button>
    </div>
  </form>
</div>
@endsection
