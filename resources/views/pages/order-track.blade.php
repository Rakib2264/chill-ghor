@extends('layouts.app')
@section('title', 'অর্ডার ট্র্যাক করুন')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12">
    <div class="rounded-3xl border border-charcoal/10 bg-white p-8 shadow-warm text-center">
        <div class="text-6xl mb-4">🔍</div>
        <h1 class="font-display text-3xl font-bold">অর্ডার ট্র্যাক করুন</h1>
        <p class="mt-2 text-charcoal/60">আপনার ইনভয়েস নম্বর দিন</p>
        
        @if(session('error'))
            <div class="mt-4 rounded-xl bg-red-50 border border-red-200 p-3 text-red-600">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('order.track') }}" method="POST" class="mt-6">
            @csrf
            <input type="text" name="invoice_no" placeholder="যেমন: CH-ABC12345" required
                   class="w-full rounded-xl border border-charcoal/15 bg-cream px-5 py-3 text-center">
            <button type="submit" class="mt-4 rounded-full bg-gradient-warm px-8 py-3 font-bold text-white">
                📍 ট্র্যাক করুন
            </button>
        </form>
    </div>
</div>
@endsection