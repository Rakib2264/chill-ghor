@extends('admin.layouts.app')
@section('title', 'POS সিস্টেম')
@section('header', 'পয়েন্ট অফ সেল')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar for better UX */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c0392b;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
    }
</style>
@endpush

@section('content')

<div class="flex flex-col lg:grid lg:grid-cols-3 gap-4 h-[calc(100vh-130px)]">
    
    {{-- Products Section (Left 2 columns) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
        
        {{-- Search Bar --}}
        <div class="p-4 border-b border-gray-200">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" id="searchInput" placeholder="পণ্য খুঁজুন..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full bg-gray-50 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>
        </div>

        {{-- Categories (Scrollable) --}}
        <div class="px-4 py-2 border-b border-gray-200 overflow-x-auto whitespace-nowrap custom-scrollbar">
            <div class="flex gap-2">
                <button onclick="filterCategory('')" 
                        class="cat-btn px-4 py-2 rounded-full text-sm font-bold bg-primary text-white shadow-sm transition">
                    🍽️ সব পণ্য
                </button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->id }}')" 
                            class="cat-btn px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                        {{ $cat->emoji }} {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Products Grid --}}
        <div id="productsGrid" class="flex-1 overflow-y-auto p-4 custom-scrollbar">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($products as $product)
                    <div class="product-card bg-cream border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-primary hover:shadow-md transition-all"
                         data-id="{{ $product['id'] }}"
                         data-name="{{ $product['name'] }}"
                         data-price="{{ $product['price'] }}"
                         data-category="{{ $product['category_id'] }}"
                         onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $product['price'] }})">
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" 
                             class="w-full aspect-square object-cover rounded-lg bg-gray-100 mb-2"
                             onerror="this.src='https://placehold.co/200/faf6ef/c0392b?text=🍽️'">
                        <div class="font-semibold text-sm line-clamp-2">{{ $product['name'] }}</div>
                        <div class="text-primary font-bold text-sm mt-1">৳{{ number_format($product['price']) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Add Button --}}
        <div class="p-4 border-t border-gray-200">
            <button onclick="toggleQuickAdd()" class="text-primary font-semibold text-sm hover:underline">
                ➕ দ্রুত পণ্য যোগ করুন
            </button>
            
            {{-- Quick Add Form --}}
            <div id="quickAddForm" class="hidden mt-3 p-4 bg-cream rounded-xl border border-gray-200">
                <div class="space-y-3">
                    <input type="text" id="quickName" placeholder="পণ্যের নাম" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <select id="quickCategory" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-primary focus:outline-none">
                        <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" id="quickPrice" placeholder="মূল্য" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <div class="flex gap-2">
                        <button onclick="quickAddProduct()" 
                                class="flex-1 bg-gradient-warm text-white py-2.5 rounded-full text-sm font-bold shadow-md hover:shadow-lg transition">
                            সংরক্ষণ
                        </button>
                        <button onclick="toggleQuickAdd()" 
                                class="px-6 py-2.5 border border-gray-300 rounded-full text-sm font-semibold hover:bg-gray-50 transition">
                            বাতিল
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cart Section (Right 1 column) --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
        
        {{-- Cart Header --}}
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span>🛒</span> অর্ডার
            </h3>
            <button onclick="clearCart()" class="text-red-500 text-sm font-semibold hover:text-red-600">
                খালি করুন
            </button>
        </div>

        {{-- Order Type --}}
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-3 gap-2">
                <button onclick="setOrderType('dine_in')" 
                        class="order-type-btn py-2.5 px-2 rounded-full text-sm font-bold bg-primary text-white transition" data-type="dine_in">
                    🍽️ ডাইন ইন
                </button>
                <button onclick="setOrderType('takeaway')" 
                        class="order-type-btn py-2.5 px-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition" data-type="takeaway">
                    🥡 টেকঅ্যাওয়ে
                </button>
                <button onclick="setOrderType('delivery')" 
                        class="order-type-btn py-2.5 px-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition" data-type="delivery">
                    🚚 ডেলিভারি
                </button>
            </div>
            <input type="hidden" id="orderType" value="dine_in">
        </div>

        {{-- Table Number & Customer Info --}}
        <div class="p-4 border-b border-gray-200 space-y-3">
            <div id="tableNumberDiv">
                <input type="text" id="tableNumber" placeholder="টেবিল নম্বর" 
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" id="customerName" placeholder="কাস্টমারের নাম" 
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <input type="text" id="customerPhone" placeholder="ফোন" 
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar min-h-[200px]">
            <div id="emptyCart" class="text-center py-12 text-gray-400">
                <div class="text-5xl mb-3">🛒</div>
                <p class="font-medium">কার্ট খালি</p>
                <p class="text-xs mt-1">পণ্য সিলেক্ট করুন</p>
            </div>
            <div id="cartList" class="space-y-2"></div>
        </div>

        {{-- Summary --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">সাব টোটাল</span>
                    <span id="subtotalDisplay" class="font-semibold">৳0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">ডিসকাউন্ট</span>
                    <input type="number" id="discount" value="0" min="0" 
                           class="w-24 px-2 py-1 border border-gray-200 rounded text-right text-sm focus:border-primary focus:outline-none"
                           onchange="calculateTotal()">
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">ট্যাক্স</span>
                    <input type="number" id="tax" value="0" min="0" 
                           class="w-24 px-2 py-1 border border-gray-200 rounded text-right text-sm focus:border-primary focus:outline-none"
                           onchange="calculateTotal()">
                </div>
                <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-300">
                    <span>মোট</span>
                    <span id="totalDisplay" class="text-primary text-lg">৳0</span>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="p-4 border-t border-gray-200">
            <div class="space-y-3">
                <select id="paymentMethod" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-primary focus:outline-none">
                    <option value="cash">💵 ক্যাশ</option>
                    <option value="card">💳 কার্ড</option>
                    <option value="bkash">📱 bKash</option>
                    <option value="nagad">📱 Nagad</option>
                </select>
                
                <input type="number" id="paidAmount" placeholder="পেমেন্টকৃত টাকা" value="0" min="0"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                       onchange="calculateDue()">
                
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">বাকি</span>
                    <span id="dueDisplay" class="font-bold text-lg">৳0</span>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <button onclick="setPaidAmount(50)" class="py-2 bg-gray-100 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">৳50</button>
                    <button onclick="setPaidAmount(100)" class="py-2 bg-gray-100 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">৳100</button>
                    <button onclick="setPaidAmount(500)" class="py-2 bg-gray-100 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">৳500</button>
                    <button onclick="setPaidAmount(1000)" class="py-2 bg-gray-100 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">৳1000</button>
                </div>

                <button onclick="setExactAmount()" 
                        class="w-full py-2.5 bg-green-50 border border-green-200 rounded-lg text-sm font-semibold text-green-700 hover:bg-green-100 transition">
                    💯 এক্সাক্ট পেমেন্ট
                </button>

                <button onclick="placeOrder()" id="placeOrderBtn"
                        class="w-full py-3.5 bg-gradient-warm text-white rounded-full text-base font-bold shadow-md hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    ✅ অর্ডার সম্পন্ন করুন
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Active Orders --}}
<div class="mt-4 bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-bold text-lg">📋 চলমান অর্ডার</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm font-semibold hover:underline">সব দেখুন →</a>
    </div>
    <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
        @forelse($activeOrders as $order)
            <div class="flex flex-wrap items-center justify-between gap-2 p-3 bg-gray-50 rounded-lg">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono font-bold text-primary">{{ $order->invoice_no }}</span>
                    <span class="text-sm text-gray-600">টেবিল: {{ $order->table_number ?? 'N/A' }}</span>
                    @include('admin.partials.status-badge', ['status' => $order->status])
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold">৳{{ number_format($order->total) }}</span>
                    <a href="{{ route('admin.pos.invoice.print', $order) }}" target="_blank" 
                       class="bg-white px-3 py-1.5 rounded text-sm font-semibold shadow-sm hover:shadow transition">
                        🖨️
                    </a>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 py-6">কোনো চলমান অর্ডার নেই</p>
        @endforelse
    </div>
</div>

{{-- Toast Container --}}
<div id="toastContainer" class="fixed top-20 right-4 z-50"></div>

<script>
let cart = [];
let subtotal = 0;
let currentCategory = '';

// Toast function
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'bg-charcoal text-white px-5 py-3 rounded-full shadow-lg text-sm font-medium mb-2 animate-slide-in';
    toast.innerHTML = message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

// Order Type
function setOrderType(type) {
    document.getElementById('orderType').value = type;
    document.getElementById('tableNumberDiv').style.display = type === 'dine_in' ? 'block' : 'none';
    
    document.querySelectorAll('.order-type-btn').forEach(btn => {
        if (btn.dataset.type === type) {
            btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            btn.classList.add('bg-primary', 'text-white');
        } else {
            btn.classList.remove('bg-primary', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        }
    });
}

// Quick Add Toggle
function toggleQuickAdd() {
    const form = document.getElementById('quickAddForm');
    form.classList.toggle('hidden');
}

// Quick Add Product
async function quickAddProduct() {
    const name = document.getElementById('quickName').value;
    const category_id = document.getElementById('quickCategory').value;
    const price = document.getElementById('quickPrice').value;

    if (!name || !category_id || !price) {
        showToast('❌ সব তথ্য পূরণ করুন', 'error');
        return;
    }

    try {
        const response = await fetch('/admin/pos/quick-product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name, category_id, price })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const grid = document.querySelector('#productsGrid > div');
            const productHtml = `
                <div class="product-card bg-cream border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-primary hover:shadow-md transition-all"
                     data-id="${data.product.id}"
                     data-name="${data.product.name}"
                     data-price="${data.product.price}"
                     data-category="${data.product.category_id}"
                     onclick="addToCart(${data.product.id}, '${data.product.name}', ${data.product.price})">
                    <img src="${data.product.image_url}" alt="${data.product.name}" 
                         class="w-full aspect-square object-cover rounded-lg bg-gray-100 mb-2"
                         onerror="this.src='https://placehold.co/200/faf6ef/c0392b?text=🍽️'">
                    <div class="font-semibold text-sm line-clamp-2">${data.product.name}</div>
                    <div class="text-primary font-bold text-sm mt-1">৳${data.product.price.toLocaleString()}</div>
                </div>
            `;
            grid.insertAdjacentHTML('beforeend', productHtml);
            
            document.getElementById('quickName').value = '';
            document.getElementById('quickCategory').value = '';
            document.getElementById('quickPrice').value = '';
            toggleQuickAdd();
            showToast('✅ পণ্য যোগ করা হয়েছে');
        }
    } catch (error) {
        showToast('❌ পণ্য যোগ করতে সমস্যা হয়েছে', 'error');
    }
}

// Filter Category
function filterCategory(catId) {
    currentCategory = catId;
    
    document.querySelectorAll('.cat-btn').forEach(btn => {
        if ((catId === '' && btn.textContent.includes('সব')) || btn.onclick.toString().includes(catId)) {
            btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            btn.classList.add('bg-primary', 'text-white');
        } else {
            btn.classList.remove('bg-primary', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        }
    });
    
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = (catId === '' || card.dataset.category === catId) ? '' : 'none';
    });
}

// Search
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.dataset.name.toLowerCase();
        const categoryMatch = currentCategory === '' || card.dataset.category === currentCategory;
        card.style.display = (categoryMatch && name.includes(query)) ? '' : 'none';
    });
});

// Add to Cart
function addToCart(id, name, price) {
    const existing = cart.find(item => item.product_id === id);
    existing ? existing.quantity++ : cart.push({ product_id: id, name, price, quantity: 1 });
    renderCart();
    saveCart();
    showToast(`🛒 ${name} যোগ করা হয়েছে`);
}

// Update Quantity
function updateQty(index, change) {
    if (change === -1 && cart[index].quantity > 1) cart[index].quantity--;
    else if (change === 1) cart[index].quantity++;
    if (cart[index].quantity < 1) cart.splice(index, 1);
    renderCart();
    saveCart();
}

// Remove Item
function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
    saveCart();
}

// Render Cart
function renderCart() {
    const cartList = document.getElementById('cartList');
    const emptyCart = document.getElementById('emptyCart');
    
    if (cart.length === 0) {
        cartList.innerHTML = '';
        emptyCart.style.display = 'block';
    } else {
        emptyCart.style.display = 'none';
        
        let html = '';
        cart.forEach((item, index) => {
            html += `
                <div class="bg-cream rounded-lg p-3 border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-semibold text-sm">${item.name}</span>
                        <button onclick="removeItem(${index})" class="text-gray-400 hover:text-red-500 text-lg leading-none">✕</button>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <button onclick="updateQty(${index}, -1)" class="w-7 h-7 rounded-full border border-gray-300 bg-white font-bold hover:bg-gray-50">−</button>
                            <span class="w-6 text-center font-semibold">${item.quantity}</span>
                            <button onclick="updateQty(${index}, 1)" class="w-7 h-7 rounded-full border border-gray-300 bg-white font-bold hover:bg-gray-50">+</button>
                        </div>
                        <span class="font-bold text-primary">৳${(item.price * item.quantity).toLocaleString()}</span>
                    </div>
                </div>
            `;
        });
        cartList.innerHTML = html;
    }
    
    calculateTotal();
}

// Calculate Total
function calculateTotal() {
    subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const total = subtotal - discount + tax;
    
    document.getElementById('subtotalDisplay').textContent = '৳' + subtotal.toLocaleString();
    document.getElementById('totalDisplay').textContent = '৳' + total.toLocaleString();
    calculateDue();
}

// Calculate Due
function calculateDue() {
    const total = subtotal - (parseFloat(document.getElementById('discount').value) || 0) + (parseFloat(document.getElementById('tax').value) || 0);
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const due = Math.max(0, total - paid);
    
    document.getElementById('dueDisplay').textContent = '৳' + due.toLocaleString();
    document.getElementById('dueDisplay').style.color = due > 0 ? '#ef4444' : '#22c55e';
}

function setPaidAmount(amount) {
    document.getElementById('paidAmount').value = amount;
    calculateDue();
}

function setExactAmount() {
    const total = subtotal - (parseFloat(document.getElementById('discount').value) || 0) + (parseFloat(document.getElementById('tax').value) || 0);
    document.getElementById('paidAmount').value = total;
    calculateDue();
}

function clearCart() {
    if (cart.length > 0) {
        cart = [];
        document.getElementById('discount').value = 0;
        document.getElementById('tax').value = 0;
        document.getElementById('paidAmount').value = 0;
        renderCart();
        saveCart();
        showToast('🗑️ কার্ট খালি করা হয়েছে');
    }
}

// Place Order
async function placeOrder() {
    if (cart.length === 0) {
        showToast('❌ কার্টে কোনো পণ্য নেই', 'error');
        return;
    }
    
    const total = subtotal - (parseFloat(document.getElementById('discount').value) || 0) + (parseFloat(document.getElementById('tax').value) || 0);
    
    const btn = document.getElementById('placeOrderBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ প্রসেসিং...';
    btn.disabled = true;
    
    const orderData = {
        customer_name: document.getElementById('customerName').value || 'Walking Customer',
        customer_phone: document.getElementById('customerPhone').value,
        order_type: document.getElementById('orderType').value,
        table_number: document.getElementById('tableNumber').value,
        items: cart,
        subtotal: subtotal,
        discount: parseFloat(document.getElementById('discount').value) || 0,
        tax: parseFloat(document.getElementById('tax').value) || 0,
        total: total,
        paid_amount: parseFloat(document.getElementById('paidAmount').value) || 0,
        payment_method: document.getElementById('paymentMethod').value,
    };
    
    try {
        const response = await fetch('/admin/pos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(orderData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(`✅ অর্ডার সম্পন্ন! ইনভয়েস: ${data.invoice_no}`);
            
            if (data.invoice_url) {
                const win = window.open(data.invoice_url, '_blank');
                if (win) win.onload = () => win.print();
            }
            
            cart = [];
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('tableNumber').value = '';
            document.getElementById('discount').value = 0;
            document.getElementById('tax').value = 0;
            document.getElementById('paidAmount').value = 0;
            renderCart();
            saveCart();
            
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || '❌ অর্ডার করতে সমস্যা হয়েছে', 'error');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        showToast('❌ অর্ডার করতে সমস্যা হয়েছে', 'error');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// Storage
function saveCart() {
    localStorage.setItem('pos_cart', JSON.stringify(cart));
}

function loadCart() {
    const saved = localStorage.getItem('pos_cart');
    if (saved) {
        try {
            cart = JSON.parse(saved);
            renderCart();
        } catch (e) {}
    }
}

// Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'F9') {
        e.preventDefault();
        placeOrder();
    }
    if (e.key === 'F2') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }
});

// Add animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slide-in {
        animation: slideIn 0.3s ease;
    }
`;
document.head.appendChild(style);

// Initialize
loadCart();
setOrderType('dine_in');
</script>
@endsection