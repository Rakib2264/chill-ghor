<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ইনভয়েস {{ $order->invoice_no }} — চিল ঘর</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Hind Siliguri', sans-serif; }
    .font-display { font-family: 'Tiro Bangla', serif; }
    @media print {
      .no-print { display: none; }
      body { background: white; padding: 20px; }
    }
  </style>
</head>
<body class="bg-gray-50 p-8">

<div class="mx-auto max-w-2xl bg-white rounded-2xl shadow-lg p-8 print:shadow-none print:p-0">

  {{-- Print Button --}}
  <div class="no-print flex justify-end mb-6">
    <button onclick="window.print()" class="rounded-full bg-gradient-warm px-6 py-2.5 text-sm font-bold text-white shadow-warm">
      🖨️ প্রিন্ট করুন
    </button>
  </div>

  {{-- Header --}}
  <div class="text-center border-b-2 border-dashed border-gray-200 pb-6 mb-6">
    <div class="flex items-center justify-center gap-2 mb-2">
      <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-warm text-xl shadow-warm text-white">☕</div>
      <span class="font-display text-2xl font-bold">চিল ঘর</span>
    </div>
    <p class="text-sm text-gray-600">বনগ্রাম স্কুল ও কলেজের সামনে</p>
    <p class="text-sm text-gray-600">ফোন: +৮৮০ ১৭১১-০০০০০০</p>
  </div>

  {{-- Invoice Info --}}
  <div class="flex justify-between mb-6">
    <div>
      <p class="text-xs text-gray-500 uppercase">ইনভয়েস নং</p>
      <p class="font-mono text-xl font-bold">{{ $order->invoice_no }}</p>
    </div>
    <div class="text-right">
      <p class="text-xs text-gray-500 uppercase">তারিখ</p>
      <p class="font-bold">{{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
  </div>

  {{-- Customer Info --}}
  <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
    <div>
      <p class="text-xs text-gray-500 uppercase">কাস্টমার</p>
      <p class="font-bold">{{ $order->customer_name }}</p>
      <p>{{ $order->phone }}</p>
      <p class="text-gray-600">{{ $order->address }}</p>
    </div>
    <div class="text-right">
      <p class="text-xs text-gray-500 uppercase">পেমেন্ট</p>
      <p class="font-bold uppercase">{{ $order->payment_method }}</p>
      @if($order->trx_id)
        <p class="font-mono text-sm">{{ $order->trx_id }}</p>
      @endif
    </div>
  </div>

  {{-- Items Table --}}
  <table class="w-full text-sm mb-6">
    <thead>
      <tr class="border-y border-gray-300">
        <th class="py-2 text-left">পণ্য</th>
        <th class="py-2 text-center">দাম</th>
        <th class="py-2 text-center">পরিমাণ</th>
        <th class="py-2 text-right">মোট</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
        <tr class="border-b border-gray-200">
          <td class="py-2">{{ $item->product_name }}</td>
          <td class="py-2 text-center">৳{{ number_format($item->price) }}</td>
          <td class="py-2 text-center">{{ $item->quantity }}</td>
          <td class="py-2 text-right">৳{{ number_format($item->line_total) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Totals --}}
  <div class="border-t border-gray-300 pt-4">
    <div class="flex justify-end">
      <dl class="w-64 space-y-2">
        <div class="flex justify-between">
          <dt>সাব-টোটাল</dt>
          <dd>৳{{ number_format($order->subtotal) }}</dd>
        </div>
        <div class="flex justify-between">
          <dt>ডেলিভারি চার্জ</dt>
          <dd>{{ $order->delivery_fee == 0 ? 'ফ্রি' : '৳'.number_format($order->delivery_fee) }}</dd>
        </div>
        <div class="flex justify-between border-t border-gray-300 pt-2 text-lg font-bold">
          <dt>সর্বমোট</dt>
          <dd>৳{{ number_format($order->total) }}</dd>
        </div>
      </dl>
    </div>
  </div>

  {{-- Footer --}}
  <div class="mt-8 text-center text-xs text-gray-500">
    <p>আপনার অর্ডারের জন্য ধন্যবাদ!</p>
    <p class="mt-1">{{ $order->invoice_no }}</p>
  </div>
</div>

<script>
  // Auto print when page loads
  window.onload = function() {
    // Uncomment for auto print: window.print();
  }
</script>

</body>
</html>