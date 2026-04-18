<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইনভয়েস {{ $order->invoice_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Hind Siliguri', 'Segoe UI', sans-serif; 
            background: white;
            padding: 15px;
        }
        .invoice-container {
            max-width: 350px;
            margin: 0 auto;
            font-size: 13px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .shop-name {
            font-size: 22px;
            font-weight: bold;
            color: #c0392b;
        }
        .invoice-no {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        .divider {
            border-top: 1px dashed #999;
            margin: 10px 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .item-name {
            flex: 2;
        }
        .item-qty {
            flex: 0.5;
            text-align: center;
        }
        .item-price {
            flex: 1;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 8px 0;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 2px dashed #333;
            padding-top: 10px;
        }
        .thank-you {
            font-size: 16px;
            font-weight: bold;
            color: #c0392b;
        }
        .print-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #c0392b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .print-btn:hover {
            background: #a93226;
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 5px; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
    
    <div class="invoice-container">
        <div class="header">
            <div class="shop-name">🍽️ চিল ঘর</div>
            <div style="font-size: 11px;">বনগ্রাম স্কুল ও কলেজের সামনে</div>
            <div style="font-size: 11px;">ফোন: ০১৭১১-০০০০০০</div>
            <div class="invoice-no">{{ $order->invoice_no }}</div>
            <div style="font-size: 11px;">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        
        <div style="margin-bottom: 10px;">
            <strong>{{ $order->customer_name }}</strong><br>
            @if($order->order_type == 'dine_in' && $order->table_number)
                টেবিল: {{ $order->table_number }} (ডাইন ইন)<br>
            @elseif($order->order_type == 'takeaway')
                টেকঅ্যাওয়ে<br>
            @else
                ডেলিভারি<br>
            @endif
            @if($order->phone != '01XXXXXXXXX')
                ফোন: {{ $order->phone }}
            @endif
        </div>
        
        <div class="divider"></div>
        
        <div style="font-weight: bold; margin-bottom: 5px;">
            <span style="float: left;">পণ্য</span>
            <span style="float: right;">টাকা</span>
            <div style="clear: both;"></div>
        </div>
        
        @foreach($order->items as $item)
        <div class="item-row">
            <span class="item-name">{{ $item->product_name }}</span>
            <span class="item-qty">×{{ $item->quantity }}</span>
            <span class="item-price">৳{{ number_format($item->line_total) }}</span>
        </div>
        @endforeach
        
        <div class="divider"></div>
        
        <div style="margin: 10px 0;">
            <div style="display: flex; justify-content: space-between;">
                <span>সাব টোটাল</span>
                <span>৳{{ number_format($order->subtotal) }}</span>
            </div>
            @if($order->discount > 0)
            <div style="display: flex; justify-content: space-between; color: #27ae60;">
                <span>ডিসকাউন্ট</span>
                <span>- ৳{{ number_format($order->discount) }}</span>
            </div>
            @endif
            @if($order->tax > 0)
            <div style="display: flex; justify-content: space-between;">
                <span>ট্যাক্স</span>
                <span>৳{{ number_format($order->tax) }}</span>
            </div>
            @endif
        </div>
        
        <div class="total-row">
            <span>সর্বমোট</span>
            <span style="color: #c0392b;">৳{{ number_format($order->total) }}</span>
        </div>
        
        <div style="background: #f8f9fa; padding: 8px; border-radius: 5px; margin: 10px 0;">
            <div style="display: flex; justify-content: space-between;">
                <span>পেমেন্ট: {{ strtoupper($order->payment_method) }}</span>
                <span>পেইড: ৳{{ number_format($order->paid_amount) }}</span>
            </div>
            @if($order->due_amount > 0)
            <div style="display: flex; justify-content: space-between; color: #e74c3c; font-weight: bold;">
                <span>বাকি</span>
                <span>৳{{ number_format($order->due_amount) }}</span>
            </div>
            @endif
        </div>
        
        @if($order->notes)
        <div style="font-size: 11px; margin: 10px 0; font-style: italic;">
            নোট: {{ $order->notes }}
        </div>
        @endif
        
        <div class="footer">
            <div class="thank-you">ধন্যবাদ! ❤️</div>
            <div style="font-size: 11px;">পুনরায় আসার আমন্ত্রণ রইলো</div>
            <div style="font-size: 10px; margin-top: 10px;">প্রিন্ট: {{ now()->format('d/m/Y h:i A') }}</div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
        
        // Close window after print (optional)
        window.onafterprint = function() {
            // window.close();
        };
    </script>
</body>
</html>