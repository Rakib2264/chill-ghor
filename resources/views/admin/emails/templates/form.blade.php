@extends('admin.layouts.app')
@section('title', $template->exists ? 'টেমপ্লেট এডিট' : 'নতুন টেমপ্লেট')
@section('header', $template->exists ? '✏️ টেমপ্লেট এডিট' : '+ নতুন টেমপ্লেট')
@section('content')
<form method="POST" action="{{ $template->exists ? route('admin.email-templates.update',$template) : route('admin.email-templates.store') }}" class="mx-auto max-w-5xl rounded-2xl border border-charcoal/10 bg-white p-6 shadow-soft space-y-5">
 @csrf @if($template->exists) @method('PATCH') @endif
 <div class="grid gap-4 md:grid-cols-2"><label class="text-sm font-bold">নাম<input name="name" value="{{ old('name',$template->name) }}" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2"></label><label class="text-sm font-bold">কী<input name="key" value="{{ old('key',$template->key) }}" placeholder="order.confirmation" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2 font-mono text-sm"></label></div>
 <label class="block text-sm font-bold">সাবজেক্ট<input name="subject" value="{{ old('subject',$template->subject) }}" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2"></label>
 <label class="block text-sm font-bold">HTML বডি<textarea name="body" rows="14" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2 font-mono text-xs">{{ old('body',$template->body) }}</textarea></label>
 <label class="block text-sm font-bold">বর্ণনা<textarea name="description" rows="2" class="mt-1 w-full rounded-xl border border-charcoal/15 bg-cream px-4 py-2">{{ old('description',$template->description) }}</textarea></label>
 <label class="inline-flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_active" value="1" {{ old('is_active',$template->is_active ?? true) ? 'checked' : '' }}> সক্রিয়</label>
 <div class="flex justify-end gap-2"><a href="{{ route('admin.email-templates.index') }}" class="rounded-full bg-cream px-5 py-2 font-bold">বাতিল</a><button class="rounded-full bg-gradient-warm px-6 py-2 font-bold text-white shadow-warm">সেভ</button></div>
</form>
@endsection
