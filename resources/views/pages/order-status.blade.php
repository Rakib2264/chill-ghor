@extends('layouts.app')
@section('title', 'অর্ডার স্ট্যাটাস')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10">
        <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
            <h1 class="font-display text-2xl font-bold text-center mb-6">📦 অর্ডার স্ট্যাটাস</h1>

            <div class="text-center mb-6">
                <p class="text-sm text-charcoal/60">ইনভয়েস নম্বর</p>
                <p class="font-bold text-xl text-primary">{{ $order->invoice_no }}</p>
            </div>

            @php
                $statuses = [
                    'pending' => ['label' => 'অর্ডার গৃহীত', 'icon' => '📝', 'color' => 'orange'],
                    'confirmed' => ['label' => 'নিশ্চিতকৃত', 'icon' => '✅', 'color' => 'blue'],
                    'preparing' => ['label' => 'প্রস্তুতিরত', 'icon' => '🍳', 'color' => 'purple'],
                    'out_for_delivery' => ['label' => 'ডেলিভারি রাস্তায়', 'icon' => '🚚', 'color' => 'indigo'],
                    'delivered' => ['label' => 'ডেলিভারি সম্পন্ন', 'icon' => '🎉', 'color' => 'green'],
                ];
                $currentIndex = array_search($order->status, array_keys($statuses));
                if ($currentIndex === false) {
                    $currentIndex = 0;
                }
                $totalSteps = count($statuses) - 1;
                $progressPercent = ($currentIndex / $totalSteps) * 100;
            @endphp

            <div class="mb-8">
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-warm rounded-full" style="width: {{ $progressPercent }}%"></div>
                </div>
                <div class="flex justify-between mt-2">
                    @foreach ($statuses as $status)
                        <div class="text-center text-xs">{{ $status['label'] }}</div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-charcoal/10 pt-4 mt-4">
                <div class="flex justify-between">
                    <span class="text-charcoal/60">নাম:</span>
                    <span>{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-charcoal/60">মোট:</span>
                    <span class="font-bold text-primary">৳{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('menu.index') }}" class="rounded-full bg-gradient-warm px-6 py-2 text-white">
                    🍽️ নতুন অর্ডার করুন
                </a>
            </div>
        </div>
    </div>
@endsection
