@extends('layouts.app')
@section('title', 'আমাদের গল্প — চিল ঘর')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
  <p class="text-xs font-bold uppercase tracking-widest text-primary">আমাদের গল্প</p>
  <h1 class="mt-1 font-display text-4xl font-bold sm:text-5xl">৩০+ বছরের ঐতিহ্য</h1>
  <p class="mt-6 text-lg leading-relaxed text-charcoal/70">
    চিল ঘর শুরু হয়েছিল ১৯৯২ সালে — মা-নানির হাতের রান্না সবার কাছে পৌঁছে দেওয়ার স্বপ্ন নিয়ে। আজ আমরা ঢাকার অন্যতম বিশ্বস্ত বাঙালি রেস্টুরেন্ট, যেখানে প্রতিটি পদ রান্না হয় খাঁটি দেশি মসলায়, ফ্রেশ ইনগ্রেডিয়েন্টে।
  </p>
  <div class="mt-10 grid gap-5 sm:grid-cols-3">
    @foreach ([['🌶️','খাঁটি মসলা','দেশের সেরা মসলা সরাসরি সংগ্রহ'],['👨‍🍳','অভিজ্ঞ শেফ','৩০+ বছরের অভিজ্ঞ বাবুর্চি'],['🥬','ফ্রেশ ইনগ্রেডিয়েন্ট','প্রতিদিন তাজা বাজার থেকে']] as $f)
      <div class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft">
        <div class="text-3xl">{{ $f[0] }}</div>
        <div class="mt-3 font-display font-bold">{{ $f[1] }}</div>
        <div class="mt-1 text-sm text-charcoal/60">{{ $f[2] }}</div>
      </div>
    @endforeach
  </div>
</div>
@endsection
