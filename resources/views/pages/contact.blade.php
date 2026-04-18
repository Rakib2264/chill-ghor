@extends('layouts.app')
@section('title', 'যোগাযোগ — চিল ঘর')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
  <p class="text-xs font-bold uppercase tracking-widest text-primary">যোগাযোগ</p>
  <h1 class="mt-1 font-display text-4xl font-bold sm:text-5xl">আমাদের সাথে কথা বলুন</h1>
  <p class="mt-3 text-charcoal/60">অর্ডার, ক্যাটারিং, ফিডব্যাক — যেকোনো প্রয়োজনে আমাদের জানান।</p>

  <div class="mt-10 grid gap-6 lg:grid-cols-2">
    {{-- Info Cards --}}
    <div class="space-y-4">
      @foreach ([
        ['📍', 'ঠিকানা', 'বনগ্রাম স্কুল ও কলেজের সামনে'],
        ['📞', 'ফোন', '+৮৮০ ১৭১১-০০০০০০'],
        ['✉️', 'ইমেইল', 'hello@chillghar.com'],
        ['🕐', 'খোলার সময়', 'প্রতিদিন সকাল ৭টা – রাত ১১টা'],
      ] as [$icon, $label, $val])
        <div class="flex items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-5 shadow-soft">
          <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-primary/10 text-xl">{{ $icon }}</div>
          <div>
            <div class="text-xs font-bold text-charcoal/50 uppercase tracking-wider mb-0.5">{{ $label }}</div>
            <div class="font-bold text-sm">{{ $val }}</div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Contact Form --}}
    <div class="rounded-2xl border border-charcoal/10 bg-white p-7 shadow-soft">
      <h2 class="font-display font-bold text-lg mb-5">বার্তা পাঠান</h2>
      <form method="POST" action="#" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">আপনার নাম</label>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="আপনার নাম"
                 class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">ইমেইল</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="আপনার ইমেইল"
                 class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
        </div>
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-charcoal/50 mb-1.5">আপনার বার্তা</label>
          <textarea name="message" rows="4" required placeholder="আপনার বার্তা লিখুন..."
                    class="w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15 resize-none">{{ old('message') }}</textarea>
        </div>
        <button type="submit"
                class="w-full rounded-full bg-gradient-warm py-3.5 text-sm font-bold text-white shadow-warm transition hover:scale-[1.02] hover:shadow-[0_12px_30px_-8px_rgba(192,57,43,0.5)]">
          পাঠান →
        </button>
      </form>
    </div>
  </div>
</div>
@endsection