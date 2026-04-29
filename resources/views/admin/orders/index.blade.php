@extends('admin.layouts.app')
@section('title', 'অর্ডার')
@section('header', 'অর্ডার ম্যানেজমেন্ট')

@section('content')
    <div class="rounded-2xl border border-charcoal/10 bg-white shadow-soft">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-charcoal/10 p-4">
            <div class="flex items-center gap-3">
                <h3 class="font-display text-lg font-bold">অর্ডার লিস্ট</h3>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <form method="GET" class="flex flex-1 flex-wrap gap-2 min-w-[280px]">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="ইনভয়েস / ফোন / নাম..."
                        class="flex-1 rounded-full border border-charcoal/15 bg-cream px-4 py-2 text-sm focus:border-primary focus:outline-none">
                    <button class="rounded-full bg-charcoal px-5 text-sm font-bold text-cream">খুঁজুন</button>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap gap-1 p-4 border-b border-charcoal/10">
            @foreach ($statuses as $s)
                <a href="{{ route('admin.orders.index', ['status' => $s, 'q' => request('q')]) }}"
                    class="rounded-full px-3 py-1.5 text-xs font-bold uppercase tracking-wider transition
                {{ request('status', 'all') === $s ? 'bg-primary text-white shadow-warm' : 'bg-charcoal/5 hover:bg-charcoal/10' }}">
                    {{ $s == 'all' ? 'সব' : ucfirst($s) }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-cream text-xs uppercase tracking-wider text-charcoal/60">
                    <tr>
                        <th class="py-3 px-4 text-left">ইনভয়েস</th>
                        <th class="text-left">কাস্টমার</th>
                        <th class="text-left">সময়</th>
                        <th class="text-left">পেমেন্ট</th>
                        <th class="text-right">মোট</th>
                        <th class="text-center">স্ট্যাটাস</th>
                        <th class="text-right pr-4">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr class="border-t border-charcoal/5 hover:bg-cream/50">
                            <td class="py-3 px-4 font-mono font-bold text-primary">{{ $o->invoice_no }}</td>
                            <td>
                                <div class="font-bold">{{ $o->customer_name }}</div>
                                <div class="text-xs text-charcoal/50">{{ $o->phone }}</div>
                            </td>
                            <td class="text-charcoal/60">{{ $o->created_at->format('d M, h:i A') }}</td>
                            <td>
                                <span
                                    class="rounded-full bg-charcoal/5 px-2 py-0.5 text-[10px] font-bold uppercase">{{ $o->payment_method }}</span>
                                @if ($o->trx_id)
                                    <div class="text-[10px] text-charcoal/50 mt-1">{{ $o->trx_id }}</div>
                                @endif
                            </td>
                            <td class="text-right font-bold">৳{{ number_format($o->total) }}</td>
                            <td class="text-center">@include('admin.partials.status-badge', ['status' => $o->status])</td>
                            <td class="py-3 pr-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $o) }}"
                                    class="rounded-lg border border-charcoal/15 bg-white px-3 py-1.5 text-xs font-bold hover:border-primary hover:text-primary inline-block">দেখুন</a>
                                <button onclick="printInvoice({{ $o->id }})"
                                    class="rounded-lg border border-primary/30 bg-primary/5 px-3 py-1.5 text-xs font-bold hover:bg-primary hover:text-white transition ml-1">
                                    🖨️ প্রিন্ট
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-charcoal/50">কোনো অর্ডার নেই</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-charcoal/10 p-4">{{ $orders->links() }}</div>
    </div>

    {{-- Print Modal --}}
    <div id="printModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
        onclick="closePrintModal()">
        <div class="max-w-sm w-full rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b p-3">
                <h3 class="font-display text-sm font-bold">📄 রসিদ প্রিন্ট</h3>
                <button onclick="closePrintModal()" class="text-charcoal/50 hover:text-red-500 text-xl">×</button>
            </div>
            <div id="printContent" class="p-3 max-h-[60vh] overflow-y-auto bg-gray-50">
                <!-- Loading -->
                <div class="text-center py-8 text-gray-500">লোড হচ্ছে...</div>
            </div>
            <div class="border-t p-3 flex gap-2 justify-end">
                <button onclick="closePrintModal()" class="rounded-lg border px-3 py-1.5 text-xs">বন্ধ করুন</button>
                <button onclick="executePrint()"
                    class="rounded-full bg-gradient-warm px-4 py-1.5 text-xs font-bold text-white">🖨️ প্রিন্ট</button>
            </div>
        </div>
    </div>

    {{-- Print Styles for Thermal Printer (80mm) --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-receipt,
            .print-receipt * {
                visibility: visible;
            }

            .print-receipt {
                position: absolute;
                top: 0;
                left: 0;
                width: 80mm !important;
                min-width: 80mm !important;
                max-width: 80mm !important;
                margin: 0 !important;
                padding: 2mm !important;
                font-size: 10px !important;
                font-family: monospace !important;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 3px 2px !important;
                font-size: 9px !important;
            }

            .receipt-header {
                text-align: center;
                padding: 0 0 5px 0 !important;
            }

            .receipt-logo {
                max-height: 30px !important;
                width: auto !important;
                margin: 0 auto !important;
            }

            .divider {
                border-top: 1px dashed #999 !important;
                margin: 4px 0 !important;
            }

            .total-line {
                border-top: 1px solid #000 !important;
                padding-top: 4px !important;
            }
        }
    </style>

    <script>
        function printInvoice(orderId) {
            // Show loading
            document.getElementById('printContent').innerHTML =
                '<div class="text-center py-8 text-gray-500">⏳ লোড হচ্ছে...</div>';
            document.getElementById('printModal').classList.remove('hidden');
            document.getElementById('printModal').classList.add('flex');

            // Fetch order details
            fetch(`/admin/orders/${orderId}/print`)
                .then(response => response.json())
                .then(data => {
                    const printContent = document.getElementById('printContent');
                    printContent.innerHTML = generateReceiptHTML(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('printContent').innerHTML =
                        '<div class="text-center py-8 text-red-500">❌ লোড করতে সমস্যা হয়েছে</div>';
                });
        }

        function generateReceiptHTML(order) {
            return `
    <div class="print-receipt" style="width: 80mm; margin: 0 auto; font-family: monospace; font-size: 10px;">
      {{-- Header --}}
      <div class="receipt-header" style="text-align: center; padding: 5px 0; border-bottom: 1px dashed #999;">
        <img src="{{ asset('images/logo/logo-removebg-preview.png') }}" alt="logo" class="receipt-logo" style="max-height: 35px; width: auto; margin: 0 auto;">
        <h3 style="margin: 3px 0; font-size: 12px; font-weight: bold;">YOUR BUSINESS NAME</h3>
        <p style="margin: 2px 0; font-size: 8px;">📞 ০১XXXXXXXXX</p>
        <p style="margin: 2px 0; font-size: 8px;">📍 আপনার ঠিকানা</p>
      </div>
      
      {{-- Invoice Info --}}
      <div style="text-align: center; margin: 6px 0;">
        <h4 style="margin: 3px 0; font-size: 11px; font-weight: bold;">পেমেন্ট রসিদ</h4>
        <p style="margin: 2px 0; font-size: 9px; font-weight: bold;">ইনভয়েস: ${order.invoice_no}</p>
        <p style="margin: 2px 0; font-size: 8px;">তারিখ: ${new Date(order.date).toLocaleString('bn-BD')}</p>
      </div>
      
      <div class="divider" style="border-top: 1px dashed #999; margin: 4px 0;"></div>
      
      {{-- Customer Info --}}
      <div style="margin: 5px 0; padding: 4px; background: #f9f9f9; font-size: 9px;">
        <div><strong>👤 গ্রাহক:</strong> ${order.customer_name || 'ওয়াক-ইন কাস্টমার'}</div>
        <div><strong>📱 ফোন:</strong> ${order.phone || 'N/A'}</div>
        <div><strong>📍 ঠিকানা:</strong> ${order.address || 'ইন-স্টোর'}</div>
      </div>
      
      <div class="divider" style="border-top: 1px dashed #999; margin: 4px 0;"></div>
      
      {{-- Items Table --}}
      <table style="width: 100%; margin: 5px 0;">
        <thead>
          <tr style="border-bottom: 1px dotted #999;">
            <th style="text-align: left; font-size: 9px;">পণ্য</th>
            <th style="text-align: center; font-size: 9px;">qty</th>
            <th style="text-align: right; font-size: 9px;">মূল্য</th>
           </tr>
        </thead>
        <tbody>
          ${order.items.map(item => `
                <tr style="border-bottom: 1px dotted #eee;">
                  <td style="text-align: left; font-size: 9px; padding: 2px;">${item.product_name.substring(0, 20)}</td>
                  <td style="text-align: center; font-size: 9px; padding: 2px;">x${item.quantity}</td>
                  <td style="text-align: right; font-size: 9px; padding: 2px;">৳${(item.price * item.quantity).toLocaleString()}</td>
                 </tr>
              `).join('')}
        </tbody>
       </table>
      
      <div class="divider" style="border-top: 1px dashed #999; margin: 4px 0;"></div>
      
      {{-- Totals --}}
      <div style="margin: 5px 0;">
        <div style="display: flex; justify-content: space-between; font-size: 9px; padding: 2px 0;">
          <span>সাব-টোটাল:</span>
          <span>৳${order.subtotal.toLocaleString()}</span>
        </div>
        ${order.discount > 0 ? `
            <div style="display: flex; justify-content: space-between; font-size: 9px; padding: 2px 0; color: green;">
              <span>ডিসকাউন্ট:</span>
              <span>-৳${order.discount.toLocaleString()}</span>
            </div>
            ` : ''}
        ${order.delivery_fee > 0 ? `
            <div style="display: flex; justify-content: space-between; font-size: 9px; padding: 2px 0;">
              <span>ডেলিভারি ফি:</span>
              <span>+৳${order.delivery_fee.toLocaleString()}</span>
            </div>
            ` : ''}
        <div class="total-line" style="border-top: 1px solid #000; margin: 4px 0 2px 0;"></div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; padding: 4px 0;">
          <span>মোট টাকা:</span>
          <span>৳${order.total.toLocaleString()}</span>
        </div>
      </div>
      
      <div class="divider" style="border-top: 1px dashed #999; margin: 4px 0;"></div>
      
      {{-- Payment Info --}}
      <div style="margin: 5px 0; font-size: 9px;">
        <div style="display: flex; justify-content: space-between;">
          <span>💳 পেমেন্ট:</span>
          <span><strong>${order.payment_method.toUpperCase()}</strong></span>
        </div>
        ${order.trx_id ? `
            <div style="display: flex; justify-content: space-between; margin-top: 3px;">
              <span>📱 ট্রানজ্যাকশন:</span>
              <span style="font-size: 8px;">${order.trx_id}</span>
            </div>
            ` : ''}
      </div>
      
      <div class="divider" style="border-top: 1px dashed #999; margin: 4px 0;"></div>
      
      {{-- Footer --}}
      <div style="text-align: center; margin: 8px 0 4px 0;">
        <div style="font-size: 9px;">⭐⭐⭐⭐⭐</div>
        <div style="font-size: 9px; font-weight: bold;">ধন্যবাদ!</div>
        <div style="font-size: 8px; margin-top: 4px;">পুনরায় দেখার জন্য আমন্ত্রণ</div>
        <div style="font-size: 7px; margin-top: 4px; color: #888;">** কম্পিউটার দ্বারা জেনারেট করা রসিদ **</div>
      </div>
    </div>
  `;
        }

        function executePrint() {
            const printContent = document.getElementById('printContent').innerHTML;
            const printWindow = window.open('', '_blank', 'width=400,height=600');

            printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>ইনভয়েস প্রিন্ট</title>
      <meta charset="UTF-8">
      <style>
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        body {
          font-family: monospace;
          background: white;
          width: 80mm;
          margin: 0 auto;
          padding: 2mm;
        }
        .print-receipt {
          width: 100%;
          font-size: 10px;
        }
        .receipt-header {
          text-align: center;
          padding: 5px 0;
          border-bottom: 1px dashed #999;
        }
        .receipt-logo {
          max-height: 35px;
          width: auto;
          margin: 0 auto;
        }
        .divider {
          border-top: 1px dashed #999;
          margin: 4px 0;
        }
        table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 3px 2px;
          text-align: left;
          font-size: 9px;
        }
        th {
          border-bottom: 1px dotted #999;
        }
        .total-line {
          border-top: 1px solid #000;
          margin: 4px 0 2px 0;
        }
        @media print {
          body {
            margin: 0;
            padding: 0;
          }
          .no-print {
            display: none;
          }
        }
      </style>
    </head>
    <body>
      ${printContent}
      <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 5px 10px; margin: 5px;">🖨️ প্রিন্ট</button>
        <button onclick="window.close()" style="padding: 5px 10px; margin: 5px;">❌ বন্ধ করুন</button>
      </div>
      <script>
        window.onload = function() {
          setTimeout(function() {
            window.print();
          }, 500);
        }
      <\/script>
    </body>
    </html>
  `);

            printWindow.document.close();
            closePrintModal();
        }

        function closePrintModal() {
            document.getElementById('printModal').classList.add('hidden');
            document.getElementById('printModal').classList.remove('flex');
            document.getElementById('printContent').innerHTML =
                '<div class="text-center py-8 text-gray-500">⏳ লোড হচ্ছে...</div>';
        }
    </script>
@endsection
