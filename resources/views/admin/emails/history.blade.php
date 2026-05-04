@extends('admin.layouts.app')
@section('title', 'ইমেইল হিস্ট্রি')
@section('header', '🕘 ইমেইল হিস্ট্রি')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 18px 22px;
            border: 1px solid rgba(0, 0, 0, 0.07);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <div x-data="historyPage()" class="space-y-5">

        {{-- Stats row --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="stat-card">
                <p class="text-xs font-bold uppercase tracking-wider text-charcoal/40">মোট পাঠানো</p>
                <p class="text-3xl font-black text-charcoal mt-1">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold uppercase tracking-wider text-green-400">সফল</p>
                <p class="text-3xl font-black text-green-600 mt-1">{{ $stats['sent'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold uppercase tracking-wider text-red-400">ব্যর্থ</p>
                <p class="text-3xl font-black text-red-600 mt-1">{{ $stats['failed'] ?? 0 }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold uppercase tracking-wider text-yellow-400">পেন্ডিং</p>
                <p class="text-3xl font-black text-yellow-600 mt-1">{{ $stats['pending'] ?? 0 }}</p>
            </div>
        </div>

        {{-- Filter bar --}}
        <form method="GET"
            class="flex flex-wrap gap-2 items-center bg-white rounded-2xl border border-charcoal/10 px-4 py-3 shadow-soft">
            <input name="q" value="{{ request('q') }}" placeholder="🔍 ইমেইল বা সাবজেক্ট খুঁজুন..."
                class="rounded-xl border border-charcoal/15 bg-cream px-4 py-2 text-sm flex-1 min-w-[200px]">
            <select name="status" class="rounded-xl border border-charcoal/15 bg-cream px-4 py-2 text-sm">
                <option value="">সব স্ট্যাটাস</option>
                <option value="sent" @selected(request('status') === 'sent')>✅ Sent</option>
                <option value="failed" @selected(request('status') === 'failed')>❌ Failed</option>
                <option value="pending" @selected(request('status') === 'pending')>⏳ Pending</option>
            </select>
            <select name="template" class="rounded-xl border border-charcoal/15 bg-cream px-4 py-2 text-sm">
                <option value="">সব টেমপ্লেট</option>
                @foreach ($templateList as $t)
                    <option value="{{ $t->id }}" @selected(request('template') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
            <select name="audience" class="rounded-xl border border-charcoal/15 bg-cream px-4 py-2 text-sm">
                <option value="">সব অডিয়েন্স</option>
                <option value="single" @selected(request('audience') === 'single')>👤 একজন</option>
                <option value="selected" @selected(request('audience') === 'selected')>✅ বাছাই</option>
                <option value="all_customers" @selected(request('audience') === 'all_customers')>👥 সব গ্রাহক</option>
                <option value="all_users" @selected(request('audience') === 'all_users')>🌐 সবাই</option>
                <option value="custom" @selected(request('audience') === 'custom')>✏️ কাস্টম</option>
            </select>
            <button class="rounded-xl bg-charcoal px-4 py-2 text-sm font-bold text-white">ফিল্টার</button>
            @if (request()->hasAny(['q', 'status', 'template', 'audience']))
                <a href="{{ route('admin.emails.history') }}"
                    class="rounded-xl bg-red-50 text-red-600 px-4 py-2 text-sm font-bold">✕ ক্লিয়ার</a>
            @endif
            <div class="ml-auto">
                <a href="{{ route('admin.emails.send') }}"
                    class="rounded-full bg-gradient-warm px-5 py-2 text-sm font-bold text-white shadow-warm">✉️ নতুন
                    ইমেইল</a>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-soft">
            <table class="w-full text-sm">
                <thead class="bg-cream text-left">
                    <tr>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">সময়</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">প্রাপক</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">টেমপ্লেট</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">সাবজেক্ট</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">অডিয়েন্স</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50">স্ট্যাটাস</th>
                        <th class="p-3 text-xs font-black uppercase tracking-wider text-charcoal/50 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/5">
                    @forelse($logs as $log)
                        <tr class="hover:bg-cream/50 transition-colors">
                            <td class="p-3">
                                <p class="text-xs font-semibold text-charcoal/70">
                                    {{ optional($log->sent_at ?? $log->created_at)->format('d M Y') }}</p>
                                <p class="text-xs text-charcoal/40">
                                    {{ optional($log->sent_at ?? $log->created_at)->format('h:i A') }}</p>
                            </td>
                            <td class="p-3">
                                <p class="font-bold text-charcoal">{{ $log->recipient_name ?: '—' }}</p>
                                <p class="text-xs text-charcoal/50">{{ $log->recipient_email }}</p>
                            </td>
                            <td class="p-3">
                                @if ($log->template)
                                    <span
                                        class="rounded-full bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-semibold">{{ $log->template->name }}</span>
                                @else
                                    <span class="text-charcoal/30 text-xs">—</span>
                                @endif
                            </td>
                            <td class="p-3 max-w-[200px]">
                                <p class="truncate text-charcoal/70" title="{{ $log->subject }}">{{ $log->subject }}</p>
                            </td>
                            <td class="p-3">
                                @php $audienceMap = ['single'=>'👤','selected'=>'✅','all_customers'=>'👥','all_users'=>'🌐','custom'=>'✏️']; @endphp
                                <span class="text-xs font-semibold">{{ $audienceMap[$log->audience] ?? '' }}
                                    {{ $log->audience }}</span>
                            </td>
                            <td class="p-3">
                                @if ($log->status === 'sent')
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-bold">✅
                                        Sent</span>
                                @elseif($log->status === 'failed')
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-bold"
                                        title="{{ $log->error_message }}">❌ Failed</span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-yellow-100 text-yellow-700 px-3 py-1 text-xs font-bold">⏳
                                        Pending</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Preview --}}
                                    <button type="button" @click="openPreview({{ $log->id }})"
                                        class="rounded-lg bg-blue-50 text-blue-700 px-3 py-1.5 text-xs font-bold hover:bg-blue-100 transition">
                                        👁️ দেখুন
                                    </button>
                                    {{-- Resend --}}
                                    @if ($log->status === 'failed')
                                        <form method="POST" action="{{ route('admin.emails.resend', $log->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-lg bg-orange-50 text-orange-700 px-3 py-1.5 text-xs font-bold hover:bg-orange-100 transition">
                                                🔁 আবার
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-16 text-center text-charcoal/30">
                                <p class="text-4xl mb-3">📭</p>
                                <p class="font-semibold">কোনো হিস্ট্রি নেই</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-charcoal/8 p-4">{{ $logs->links() }}</div>
        </div>

        {{-- Preview Modal --}}
        <div x-show="modalOpen" x-cloak class="modal-backdrop" @click.self="modalOpen=false">
            <div class="modal-box">
                <div class="flex items-center justify-between px-6 py-4 border-b border-charcoal/8">
                    <div>
                        <p class="font-black text-charcoal">📧 ইমেইল প্রিভিউ</p>
                        <p class="text-xs text-charcoal/40 mt-0.5" x-text="modalSubject"></p>
                    </div>
                    <button @click="modalOpen=false"
                        class="rounded-full w-8 h-8 bg-cream flex items-center justify-center font-bold text-charcoal/60 hover:bg-red-50 hover:text-red-600 transition">×</button>
                </div>
                <div class="p-4">
                    <div x-show="modalLoading" class="py-16 text-center text-charcoal/40">⏳ লোড হচ্ছে...</div>
                    <div x-show="!modalLoading && modalBody" x-cloak>
                        <iframe id="modalFrame" class="w-full rounded-xl border border-charcoal/10"
                            style="min-height:400px;border:none;"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            function historyPage() {
                return {
                    modalOpen: false,
                    modalLoading: false,
                    modalBody: '',
                    modalSubject: '',

                    async openPreview(logId) {
                        this.modalOpen = true;
                        this.modalLoading = true;
                        this.modalBody = '';
                        const r = await fetch(`{{ url('admin/emails/log') }}/${logId}/preview`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await r.json();
                        this.modalBody = data.body || '';
                        this.modalSubject = data.subject || '';
                        this.modalLoading = false;
                        this.$nextTick(() => {
                            const frame = document.getElementById('modalFrame');
                            if (frame && this.modalBody) {
                                frame.srcdoc = this.modalBody;
                                frame.onload = () => {
                                    frame.style.height = (frame.contentDocument?.body?.scrollHeight || 400) +
                                        'px';
                                };
                            }
                        });
                    }
                }
            }
        </script>
    @endpush
@endsection
