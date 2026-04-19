@extends('admin.layouts.app')
@section('title', 'যোগাযোগের বার্তা')
@section('header', 'যোগাযোগের বার্তা')

@section('content')
<div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
        <tr>
          <th class="py-3 px-4 text-left">নাম</th>
          <th class="text-left">ইমেইল</th>
          <th class="text-left">ফোন</th>
          <th class="text-left">তারিখ</th>
          <th class="text-center">স্ট্যাটাস</th>
          <th class="text-right pr-4">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($contacts as $c)
          <tr class="border-t border-charcoal/5 hover:bg-cream/50 {{ !$c->is_read ? 'bg-primary/5' : '' }}">
            <td class="py-3 px-4 font-bold">{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->phone ?? '-' }}</td>
            <td class="text-charcoal/60">{{ $c->created_at->format('d M Y, h:i A') }}</td>
            <td class="text-center">
              @if($c->is_read)
                <span class="rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">পঠিত</span>
              @else
                <span class="rounded-full bg-primary/20 px-2 py-1 text-[10px] font-bold text-primary">অপঠিত</span>
              @endif
            </td>
            <td class="py-3 pr-4 text-right">
              <a href="{{ route('admin.contacts.show', $c) }}" class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary">দেখুন</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="py-12 text-center text-charcoal/50">কোনো বার্তা নেই</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="border-t border-charcoal/10 p-4">{{ $contacts->links() }}</div>
</div>
@endsection