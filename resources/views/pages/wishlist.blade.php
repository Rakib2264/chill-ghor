@extends('layouts.app')
@section('title', 'উইশলিস্ট — চিল ঘর')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

  <p class="text-xs font-bold uppercase tracking-widest text-primary">পছন্দের তালিকা</p>
  <h1 class="mt-1 font-display text-3xl font-bold sm:text-4xl">উইশলিস্ট ({{ $products->count() }})</h1>

  @if ($products->isEmpty())
    <div class="mt-12 rounded-3xl border border-dashed border-charcoal/20 bg-white p-16 text-center">
      <div class="text-6xl">❤️</div>
      <h2 class="mt-4 font-display text-2xl font-bold">উইশলিস্ট খালি</h2>
      <p class="mt-2 text-charcoal/60">পছন্দের খাবার সেভ করতে ❤️ আইকনে ক্লিক করুন।</p>
      <a href="{{ route('menu.index') }}" class="mt-6 inline-flex rounded-full bg-gradient-warm px-7 py-3 text-sm font-bold text-white shadow-warm">মেনু দেখুন →</a>
    </div>
  @else
    <div class="mt-8 grid gap-3">
      @foreach ($products as $p)
        <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-charcoal/10 bg-white p-3 shadow-soft">
          <a href="{{ route('menu.show', $p) }}"><img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="h-20 w-20 rounded-xl object-cover"></a>
          <div class="min-w-0 flex-1">
            <a href="{{ route('menu.show', $p) }}"><h3 class="font-display font-bold hover:text-primary">{{ $p->name }}</h3></a>
            <p class="line-clamp-1 text-xs text-charcoal/60">{{ $p->description }}</p>
            <p class="mt-1 font-bold text-primary">৳{{ number_format($p->price) }}</p>
          </div>
          <div class="flex items-center gap-2">
            <form action="{{ route('wishlist.move', $p) }}" method="POST">
              @csrf
              <button class="flex items-center gap-1.5 rounded-full bg-gradient-warm px-4 py-2 text-xs font-bold text-white shadow-warm">🛒 কার্টে নিন</button>
            </form>
            <form action="{{ route('wishlist.toggle', $p) }}" method="POST">
              @csrf
              <button class="flex h-9 w-9 items-center justify-center rounded-full text-charcoal/50 hover:bg-primary/10 hover:text-primary" aria-label="remove">✕</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>
@endsection
