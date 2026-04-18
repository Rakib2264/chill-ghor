@extends('admin.layouts.app')
@section('title', 'সেটিংস')
@section('header', 'সাইট সেটিংস')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PATCH')

  <div class="space-y-6">
    @foreach($groups as $group)
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <h2 class="font-display text-lg font-bold mb-5 capitalize">{{ $group }}</h2>
        
        <div class="grid gap-5 md:grid-cols-2">
          @foreach($settings[$group] ?? [] as $setting)
            <div>
              <label class="block">
                <span class="text-xs font-bold text-charcoal/70">{{ $setting->label ?? $setting->key }}</span>
                
                @if($setting->type === 'image')
                  @if($setting->value)
                    <img src="{{ asset($setting->value) }}" alt="{{ $setting->key }}" class="mt-2 h-16 w-16 object-contain border rounded">
                  @endif
                  <input type="file" name="settings[{{ $setting->key }}][file]" accept="image/*" 
                         class="mt-2 w-full rounded-xl border border-charcoal/15 bg-cream px-3 py-2 text-xs">
                  
                @elseif($setting->type === 'textarea')
                  <textarea name="settings[{{ $setting->key }}][value]" rows="3" 
                            class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">{{ $setting->value }}</textarea>
                  
                @elseif($setting->type === 'boolean')
                  <label class="mt-2 flex cursor-pointer items-center gap-2">
                    <input type="hidden" name="settings[{{ $setting->key }}][value]" value="0">
                    <input type="checkbox" name="settings[{{ $setting->key }}][value]" value="1" 
                           {{ $setting->value ? 'checked' : '' }} class="h-4 w-4 accent-primary">
                    <span class="text-sm">চালু করুন</span>
                  </label>
                  
                @elseif($setting->type === 'number')
                  <input type="number" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}" 
                         class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
                  
                @else
                  <input type="text" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}" 
                         class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm">
                @endif
                
                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
              </label>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach

    <div class="flex justify-end">
      <button type="submit" class="rounded-full bg-gradient-warm px-8 py-3 text-sm font-bold text-white shadow-warm">
        💾 সব সেটিংস সংরক্ষণ করুন
      </button>
    </div>
  </div>
</form>
@endsection