@extends('admin.layouts.app')
@section('title', 'বার্তা বিস্তারিত')
@section('header', '✉️ বার্তা বিস্তারিত')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">

  <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-charcoal/60 hover:text-primary">
    ← সকল বার্তায় ফিরে যান
  </a>

  <div class="overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft">
    {{-- Header --}}
    <div class="bg-gradient-warm px-6 py-5 text-white">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
          <h2 class="font-display text-xl font-bold">{{ $contact->name }}</h2>
          <p class="text-sm text-white/85 mt-0.5">{{ $contact->email }}</p>
          @if ($contact->phone)
            <p class="text-sm text-white/85">📞 {{ $contact->phone }}</p>
          @endif
        </div>
        <div class="text-right text-xs text-white/80">
          <div>📅 {{ $contact->created_at->format('d M Y') }}</div>
          <div>🕐 {{ $contact->created_at->format('h:i A') }}</div>
        </div>
      </div>
    </div>

    {{-- Subject --}}
    @if (!empty($contact->subject))
      <div class="border-b border-charcoal/8 px-6 py-4">
        <p class="text-[10px] font-bold uppercase tracking-widest text-charcoal/50">বিষয়</p>
        <p class="mt-1 font-bold">{{ $contact->subject }}</p>
      </div>
    @endif

    {{-- Message --}}
    <div class="px-6 py-5">
      <p class="text-[10px] font-bold uppercase tracking-widest text-charcoal/50 mb-2">বার্তা</p>
      <div class="rounded-xl bg-cream/60 px-4 py-4 text-sm leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-charcoal/8 bg-cream/40 px-6 py-4">
      <div class="flex flex-wrap gap-2">
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject ?? 'আপনার বার্তা' }}"
          class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-warm px-4 py-2 text-xs font-bold text-white shadow-warm hover:scale-105">
          ✉️ ইমেইলে রিপ্লাই
        </a>
        @if ($contact->phone)
          <a href="tel:{{ $contact->phone }}" class="inline-flex items-center gap-1.5 rounded-xl border border-charcoal/15 bg-white px-4 py-2 text-xs font-bold hover:border-primary hover:text-primary">
            📞 কল করুন
          </a>
        @endif
      </div>
      <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
        onsubmit="return confirm('এই বার্তা মুছে ফেলবেন?')">
        @csrf @method('DELETE')
        <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-100">
          🗑️ মুছে ফেলুন
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
