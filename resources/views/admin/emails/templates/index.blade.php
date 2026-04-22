@extends('admin.layouts.app')
@section('title', 'ইমেইল টেমপ্লেট')
@section('header', '📧 ইমেইল টেমপ্লেট')
@section('content')
    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-charcoal/60">@{{ name }}, @{{ order_no }}, @{{ site_name }},
                @{{ total }} placeholder ব্যবহার করুন।</p>
            <div class="flex gap-2">
                <a href="{{ route('admin.emails.send') }}"
                    class="rounded-full bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm">✉️ ইমেইল
                    পাঠান</a>
                <a href="{{ route('admin.email-templates.create') }}"
                    class="rounded-full bg-charcoal px-5 py-2 text-sm font-bold text-white">+ নতুন</a>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft">
            <table class="w-full text-sm">
                <thead class="bg-cream text-left">
                    <tr>
                        <th class="p-3">নাম</th>
                        <th class="p-3">কী</th>
                        <th class="p-3">সাবজেক্ট</th>
                        <th class="p-3">স্ট্যাটাস</th>
                        <th class="p-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/8">
                    @forelse($templates as $t)
                        <tr>
                            <td class="p-3 font-bold">{{ $t->name }}</td>
                            <td class="p-3"><code class="rounded bg-cream px-2 py-1 text-xs">{{ $t->key }}</code>
                            </td>
                            <td class="p-3 text-charcoal/65">{{ $t->subject }}</td>
                            <td class="p-3">{{ $t->is_active ? '✅ সক্রিয়' : '⏸️ বন্ধ' }}</td>
                            <td class="p-3 text-right"><a href="{{ route('admin.email-templates.edit', $t) }}"
                                    class="font-bold text-primary">এডিট</a></td>
                    </tr>@empty<tr>
                            <td colspan="5" class="p-10 text-center text-charcoal/40">কোনো টেমপ্লেট নেই</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $templates->links() }}</div>
        </div>
    </div>
@endsection
