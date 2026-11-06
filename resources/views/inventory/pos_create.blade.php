@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('error'))
    <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-exclamation mr-2 text-rose-600"></i> {{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Item Finder & POS Cart -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Search / Scanner Bar -->
            <div class="inv-card p-4">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-barcode text-slate-700"></i>
                    <h4 class="text-sm font-bold text-slate-900">Find Items or Scan Barcode</h4>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" id="posSearch" placeholder="Type item name, SKU or scan barcode and press Enter..." class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 font-medium">
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Scanner input ending with Enter automatically adds the matching item to the cart.</p>

                <!-- Quick Click Chips -->
                <div class="mt-3 flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pt-1">
                    @foreach($items->take(12) as $qItem)
                    <button type="button" onclick="addToCart({{ $qItem->id }}, '{{ addslashes($qItem->name) }}', {{ $qItem->selling_price > 0 ? $qItem->selling_price : $qItem->unit_price }}, {{ $qItem->stock_quantity }})" class="px-2.5 py-1 text-xs bg-slate-50 border border-slate-200 hover:bg-slate-100 rounded-lg font-medium text-slate-700 flex items-center gap-1.5 transition">
                        <span>{{ $qItem->name }}</span>
                        <span class="text-emerald-600 font-bold">₹{{ number_format($qItem->selling_price > 0 ? $qItem->selling_price : $qItem->unit_price, 2) }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Cart Table -->
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-emerald-600"></i>
                        Cart (<span id="cartCount">0</span> items)
                    </span>
                    <button type="button" onclick="clearCart()" class="text-xs text-rose-600 font-semibold hover:underline">Clear Cart</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="w-28">Qty</th>
                                <th>Price</th>
                                <th>GST %</th>
                                <th>Total</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            <tr id="emptyCartRow">
                                <td colspan="6" class="text-center py-12 text-slate-400">
                                    <i class="fa-solid fa-cart-arrow-down text-3xl mb-2 block text-slate-300"></i>
                                    Cart is empty. Search above or scan barcode to add items.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Billing & Payment -->
        <div class="space-y-5">
            <div class="inv-card overflow-hidden">
                <div class="inv-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-credit-card text-slate-700"></i>
                        Billing & Payment
                    </span>
                </div>

                <form id="posForm" action="{{ route('school.inventory.sales.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="cart_data" id="cartDataInput" value="[]">

                    <!-- Billing Mode -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Billing Mode <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="p-2 border border-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="billing_mode" value="school" checked onchange="toggleVendorMode('school')">
                                <span>School (Own Bill)</span>
                            </label>
                            <label class="p-2 border border-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="billing_mode" value="vendor" onchange="toggleVendorMode('vendor')">
                                <span>On behalf of Vendor</span>
                            </label>
                        </div>
                    </div>

                    <!-- Vendor dropdown (hidden by default) -->
                    <div id="vendorSelectGroup" class="hidden">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Vendor <span class="text-rose-500">*</span></label>
                        <select name="vendor_id" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="">-- Select Vendor --</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Invoice will be issued under this vendor's identity.</p>
                    </div>

                    <!-- Buyer Type -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Buyer <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <label class="p-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-center cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="buyer_type" value="Walk-in" checked onchange="toggleBuyerType('Walk-in')"> Walk-in
                            </label>
                            <label class="p-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-center cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="buyer_type" value="Student" onchange="toggleBuyerType('Student')"> Student
                            </label>
                            <label class="p-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-center cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="buyer_type" value="Staff" onchange="toggleBuyerType('Staff')"> Staff
                            </label>
                        </div>

                        <!-- Walk-in Name Input -->
                        <div id="buyerNameGroup">
                            <input type="text" name="buyer_name" placeholder="Customer / Walk-in Name (optional)" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300">
                        </div>

                        <!-- Student Selector -->
                        <div id="studentBuyerGroup" class="hidden">
                            <select name="buyer_id" id="buyerStudentId" disabled onchange="setBuyerNameFromOption(this)" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $stud)
                                <option value="{{ $stud->id }}" data-name="{{ $stud->first_name }} {{ $stud->last_name }} ({{ $stud->admission_no ?? 'ID:'.$stud->id }})">
                                    {{ $stud->first_name }} {{ $stud->last_name }} ({{ $stud->admission_no ?? 'ID:'.$stud->id }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Staff Selector -->
                        <div id="staffBuyerGroup" class="hidden">
                            <select name="buyer_id" id="buyerStaffId" disabled onchange="setBuyerNameFromOption(this)" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300">
                                <option value="">-- Select Staff --</option>
                                @foreach($staffMembers as $st)
                                <option value="{{ $st->id }}" data-name="{{ $st->first_name }} {{ $st->last_name }}">
                                    {{ $st->first_name }} {{ $st->last_name }} ({{ $st->department ?? 'Staff' }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Mode <span class="text-rose-500">*</span></label>
                        <select name="payment_mode" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                            <option value="CASH">Cash</option>
                            <option value="UPI">UPI / QR Code</option>
                            <option value="CARD">Debit / Credit Card</option>
                            <option value="STUDENT_WALLET">Campus Digital Wallet</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Reference / Txn ID</label>
                        <input type="text" name="payment_reference" placeholder="e.g. UPI Ref, Card Auth Code" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Extra Discount (₹)</label>
                        <input type="number" step="0.01" min="0" id="extraDiscount" name="discount_amount" value="0.00" oninput="renderCartSummary()" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300">
                    </div>

                    <!-- Total Breakdown -->
                    <div class="p-3.5 bg-slate-50 rounded-xl space-y-1.5 border border-slate-100">
                        <div class="flex justify-between text-xs text-slate-600">
                            <span>Subtotal</span>
                            <span id="dispSubtotal" class="font-bold">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-600">
                            <span>Tax (GST)</span>
                            <span id="dispTax" class="font-bold">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-xs text-rose-600">
                            <span>Discount</span>
                            <span id="dispDiscount" class="font-bold">-₹0.00</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex justify-between text-base font-extrabold text-slate-900">
                            <span>Grand Total</span>
                            <span id="dispGrandTotal" class="text-emerald-600">₹0.00</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-receipt"></i> Complete Sale & Issue Invoice
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

const allItems = [
    @foreach($items as $it)
    {
        id: {{ $it->id }},
        name: "{{ addslashes($it->name) }}",
        sku: "{{ $it->sku }}",
        barcode: "{{ $it->barcode }}",
        price: {{ $it->selling_price > 0 ? $it->selling_price : $it->unit_price }},
        stock: {{ $it->stock_quantity }}
    },
    @endforeach
];

// Search & barcode input listener
document.getElementById('posSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const val = this.value.trim().toLowerCase();
        if (!val) return;

        const match = allItems.find(it => 
            (it.barcode && it.barcode.toLowerCase() === val) ||
            (it.sku && it.sku.toLowerCase() === val) ||
            it.name.toLowerCase().includes(val)
        );

        if (match) {
            addToCart(match.id, match.name, match.price, match.stock);
            this.value = '';
        } else {
            alert("No item matching '" + val + "' found in stock.");
        }
    }
});

function addToCart(itemId, name, price, stock) {
    const existing = cart.find(i => i.item_id === itemId);
    if (existing) {
        if (existing.qty + 1 > stock) {
            alert('Cannot add more! Only ' + stock + ' in stock.');
            return;
        }
        existing.qty += 1;
    } else {
        if (stock < 1) {
            alert('Item is currently out of stock!');
            return;
        }
        cart.push({
            item_id: itemId,
            name: name,
            price: parseFloat(price),
            qty: 1,
            tax: 0,
            stock: stock
        });
    }
    renderCart();
}

function updateQty(itemId, newQty) {
    const item = cart.find(i => i.item_id === itemId);
    if (!item) return;

    newQty = parseInt(newQty);
    if (newQty <= 0) {
        removeFromCart(itemId);
        return;
    }
    if (newQty > item.stock) {
        alert('Cannot exceed available stock of ' + item.stock);
        item.qty = item.stock;
    } else {
        item.qty = newQty;
    }
    renderCart();
}

function removeFromCart(itemId) {
    cart = cart.filter(i => i.item_id !== itemId);
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartTableBody');
    document.getElementById('cartCount').textContent = cart.length;

    if (cart.length === 0) {
        tbody.innerHTML = `
            <tr id="emptyCartRow">
                <td colspan="6" class="text-center py-12 text-slate-400">
                    <i class="fa-solid fa-cart-arrow-down text-3xl mb-2 block text-slate-300"></i>
                    Cart is empty. Search above or scan barcode to add items.
                </td>
            </tr>`;
        renderCartSummary();
        return;
    }

    let rowsHtml = '';
    cart.forEach(item => {
        const lineTotal = item.qty * item.price;
        rowsHtml += `
            <tr>
                <td>
                    <div class="font-bold text-slate-900 text-xs">${item.name}</div>
                    <div class="text-[10px] text-slate-400">Stock: ${item.stock}</div>
                </td>
                <td>
                    <div class="flex items-center space-x-1">
                        <button type="button" onclick="updateQty(${item.item_id}, ${item.qty - 1})" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-xs font-bold">-</button>
                        <input type="number" min="1" max="${item.stock}" value="${item.qty}" onchange="updateQty(${item.item_id}, this.value)" class="w-12 text-center text-xs py-1 border border-slate-200 rounded">
                        <button type="button" onclick="updateQty(${item.item_id}, ${item.qty + 1})" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-xs font-bold">+</button>
                    </div>
                </td>
                <td class="text-xs font-mono">₹${item.price.toFixed(2)}</td>
                <td class="text-xs text-slate-500">0%</td>
                <td class="text-xs font-bold font-mono text-slate-900">₹${lineTotal.toFixed(2)}</td>
                <td class="text-right">
                    <button type="button" onclick="removeFromCart(${item.item_id})" class="text-rose-500 hover:text-rose-700 text-xs">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>`;
    });

    tbody.innerHTML = rowsHtml;
    renderCartSummary();
}

function renderCartSummary() {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.qty * item.price;
    });

    const discount = parseFloat(document.getElementById('extraDiscount').value) || 0;
    const tax = 0;
    const grandTotal = Math.max(0, (subtotal + tax) - discount);

    document.getElementById('dispSubtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('dispTax').textContent = '₹' + tax.toFixed(2);
    document.getElementById('dispDiscount').textContent = '-₹' + discount.toFixed(2);
    document.getElementById('dispGrandTotal').textContent = '₹' + grandTotal.toFixed(2);

    document.getElementById('cartDataInput').value = JSON.stringify(cart);
}

function toggleVendorMode(mode) {
    const vGroup = document.getElementById('vendorSelectGroup');
    if (mode === 'vendor') {
        vGroup.classList.remove('hidden');
    } else {
        vGroup.classList.add('hidden');
    }
}

function toggleBuyerType(type) {
    const nameGroup = document.getElementById('buyerNameGroup');
    const studGroup = document.getElementById('studentBuyerGroup');
    const staffGroup = document.getElementById('staffBuyerGroup');
    const bStud = document.getElementById('buyerStudentId');
    const bStaff = document.getElementById('buyerStaffId');

    nameGroup.classList.add('hidden');
    studGroup.classList.add('hidden');
    staffGroup.classList.add('hidden');
    bStud.disabled = true;
    bStaff.disabled = true;

    if (type === 'Walk-in') {
        nameGroup.classList.remove('hidden');
    } else if (type === 'Student') {
        studGroup.classList.remove('hidden');
        bStud.disabled = false;
    } else if (type === 'Staff') {
        staffGroup.classList.remove('hidden');
        bStaff.disabled = false;
    }
}

function setBuyerNameFromOption(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.name) {
        document.querySelector('input[name="buyer_name"]').value = opt.dataset.name;
    }
}
</script>
@endsection

