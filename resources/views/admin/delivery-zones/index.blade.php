{{-- resources/views/admin/delivery-zones/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'ডেলিভারি জোন')
@section('header', 'ডেলিভারি জোন')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-sm text-charcoal/55">সকল ডেলিভারি এলাকা ও চার্জ পরিচালনা করুন</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')"
        class="rounded-full bg-gradient-warm px-5 py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105 transition">
        + নতুন জোন যোগ করুন
    </button>
</div>

{{-- Zones Table --}}
<div class="rounded-2xl bg-white shadow-soft overflow-hidden">
    @if ($zones->isEmpty())
        <div class="py-16 text-center">
            <div class="text-5xl mb-3">🚚</div>
            <p class="text-charcoal/55 text-sm">কোনো ডেলিভারি জোন নেই</p>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="mt-4 rounded-full bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm">
                প্রথম জোন যোগ করুন
            </button>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-charcoal/10 bg-cream/60">
                    <tr class="text-xs font-bold uppercase tracking-wider text-charcoal/55">
                        <th class="px-5 py-3.5 text-left">জোনের নাম</th>
                        <th class="px-5 py-3.5 text-right">ডেলিভারি চার্জ</th>
                        <th class="px-5 py-3.5 text-right">ফ্রি ডেলিভারি (ন্যূনতম)</th>
                        <th class="px-5 py-3.5 text-center">স্ট্যাটাস</th>
                        <th class="px-5 py-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/5">
                    @foreach ($zones as $zone)
                        <tr class="hover:bg-cream/40 transition" id="zone-row-{{ $zone->id }}">
                            <td class="px-5 py-4 font-semibold">{{ $zone->zone_name }}</td>
                            <td class="px-5 py-4 text-right font-bold text-primary">৳{{ number_format($zone->delivery_charge) }}</td>
                            <td class="px-5 py-4 text-right text-charcoal/70">
                                @if ($zone->min_order_for_free > 0)
                                    ৳{{ number_format($zone->min_order_for_free) }} এর উপরে
                                @else
                                    <span class="text-charcoal/40">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($zone->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> সক্রিয়
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-charcoal/8 px-2.5 py-1 text-xs font-bold text-charcoal/50">
                                        <span class="h-1.5 w-1.5 rounded-full bg-charcoal/30"></span> নিষ্ক্রিয়
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        onclick="openEdit({{ $zone->id }}, '{{ addslashes($zone->zone_name) }}', {{ $zone->delivery_charge }}, {{ $zone->min_order_for_free }}, {{ $zone->is_active ? 1 : 0 }})"
                                        class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary transition">
                                        সম্পাদনা
                                    </button>
                                    <form action="{{ route('admin.delivery-zones.destroy', $zone) }}" method="POST"
                                        onsubmit="return confirm('এই জোনটি মুছে ফেলবেন?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                            মুছুন
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ===== ADD MODAL ===== --}}
<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-charcoal/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-warm p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-lg font-bold">নতুন ডেলিভারি জোন</h2>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"
                class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-charcoal/8 text-charcoal/50 transition">✕</button>
        </div>
        <form action="{{ route('admin.delivery-zones.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">জোনের নাম</label>
                <input type="text" name="zone_name" required placeholder="যেমন: ঢাকা সিটি"
                    class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">ডেলিভারি চার্জ (৳)</label>
                    <input type="number" name="delivery_charge" required min="0" placeholder="60"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">ফ্রি ডেলিভারি থেকে (৳)</label>
                    <input type="number" name="min_order_for_free" required min="0" placeholder="500"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-charcoal/15 py-2.5 text-sm font-bold hover:bg-charcoal/5 transition">
                    বাতিল
                </button>
                <button type="submit"
                    class="flex-1 rounded-xl bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105 transition">
                    যোগ করুন
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== EDIT MODAL ===== --}}
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-charcoal/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-warm p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-lg font-bold">জোন সম্পাদনা</h2>
            <button onclick="document.getElementById('editModal').classList.add('hidden')"
                class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-charcoal/8 text-charcoal/50 transition">✕</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">জোনের নাম</label>
                <input type="text" id="editName" name="zone_name" required
                    class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">ডেলিভারি চার্জ (৳)</label>
                    <input type="number" id="editCharge" name="delivery_charge" required min="0"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-charcoal/55">ফ্রি ডেলিভারি থেকে (৳)</label>
                    <input type="number" id="editMinOrder" name="min_order_for_free" required min="0"
                        class="w-full rounded-xl border border-charcoal/15 bg-cream/50 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" id="editActive" name="is_active" value="1"
                    class="h-4 w-4 rounded border-charcoal/20 accent-primary">
                <label for="editActive" class="text-sm font-semibold">সক্রিয়</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="flex-1 rounded-xl border border-charcoal/15 py-2.5 text-sm font-bold hover:bg-charcoal/5 transition">
                    বাতিল
                </button>
                <button type="submit"
                    class="flex-1 rounded-xl bg-gradient-warm py-2.5 text-sm font-bold text-white shadow-warm hover:scale-105 transition">
                    আপডেট করুন
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEdit(id, name, charge, minOrder, isActive) {
    document.getElementById('editForm').action = `/admin/delivery-zones/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editCharge').value = charge;
    document.getElementById('editMinOrder').value = minOrder;
    document.getElementById('editActive').checked = isActive == 1;
    document.getElementById('editModal').classList.remove('hidden');
}
// Close modals on backdrop click
['addModal','editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endpush

@endsection