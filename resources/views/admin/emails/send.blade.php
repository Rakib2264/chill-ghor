@extends('admin.layouts.app')
@section('title', 'ইমেইল পাঠান')
@section('header', '🚀 ইমেইল পাঠান')
@section('content')
<form method="POST" action="{{ route('admin.emails.send.store') }}" x-data="{audience:'{{ old('audience','single') }}'}" class="mx-auto max-w-4xl rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft space-y-5">
 @csrf
 <div class="flex justify-end"><a href="{{ route('admin.emails.history') }}" class="rounded-full bg-cream px-4 py-2 text-xs font-bold">🕘 হিস্ট্রি</a></div>
 <label class="block text-sm font-bold">টেমপ্লেট<select name="template_key" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2"><option value="">— সিলেক্ট —</option>@foreach($templates as $t)<option value="{{ $t->key }}">{{ $t->name }}</option>@endforeach</select></label>
 <label class="block text-sm font-bold">সাবজেক্ট ওভাররাইড (ঐচ্ছিক)<input name="subject_override" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2"></label>
 <div class="grid gap-2 sm:grid-cols-4">@foreach(['single'=>'👤 একজনকে','all_customers'=>'👥 সব গ্রাহক ('.$customerCount.')','all_users'=>'🌐 সবাই ('.$userCount.')','custom'=>'✏️ কাস্টম'] as $key=>$label)<label><input type="radio" name="audience" value="{{ $key }}" x-model="audience" class="sr-only peer"><div class="cursor-pointer rounded-xl border-2 border-charcoal/10 p-3 text-center text-sm font-bold peer-checked:border-primary peer-checked:bg-primary/10">{{ $label }}</div></label>@endforeach</div>
 <div x-show="audience==='single'" x-cloak><label class="block text-sm font-bold">ইমেইল<input name="email" type="email" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2"></label></div>
 <div x-show="audience==='custom'" x-cloak><label class="block text-sm font-bold">কাস্টম লিস্ট<textarea name="custom_list" rows="4" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2 font-mono text-xs"></textarea></label></div>
 <div x-show="audience==='all_customers'||audience==='all_users'" x-cloak class="rounded-xl bg-yellow-50 p-4 text-sm font-semibold text-yellow-800">⚠️ Bulk email পাঠানোর আগে SMTP settings ঠিক আছে কিনা নিশ্চিত করুন।</div>
 <div class="text-right"><button class="rounded-full bg-gradient-warm px-7 py-3 font-bold text-white shadow-warm">🚀 পাঠান</button></div>
</form>
@endsection
