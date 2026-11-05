@extends('layouts.app')

@section('title', 'Inventory & Stock Management')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Modules</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Inventory</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Inventory &amp; Stock Management</h1>
                    <p class="text-xs text-slate-500">Warehouse ledger, store items, supplier procurement, purchase orders, and uniform/book POS sales.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="openModal('addItemModal')" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Add Inventory Item</span>
            </button>
            <a href="{{ route('modules.inventory', ['tab' => 'pos']) }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-cash-register"></i>
                <span>Store POS Counter</span>
            </a>
        </div>
    </div>

    <!-- Sub Navigation Pill Tabs -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('modules.inventory', ['tab' => 'dashboard']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'dashboard' || empty($tab) ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('modules.inventory', ['tab' => 'items']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'items' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-list"></i>
                <span>Item Master</span>
                <span class="ml-1 bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($items) }}</span>
            </a>
            <a href="{{ route('modules.inventory', ['tab' => 'categories']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'categories' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('modules.inventory', ['tab' => 'suppliers']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'suppliers' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-truck"></i>
                <span>Suppliers</span>
                <span class="ml-1 bg-slate-200 text-slate-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($suppliers) }}</span>
            </a>
            <a href="{{ route('modules.inventory', ['tab' => 'pos']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'pos' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-cash-register"></i>
                <span>Store POS Billing</span>
            </a>
            <a href="{{ route('modules.inventory', ['tab' => 'purchase-orders']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'purchase-orders' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Purchase Orders</span>
            </a>
        </div>

        <div class="relative w-56 hidden sm:block">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            <input type="text" id="inventorySearchInput" onkeyup="filterInventoryTable()" placeholder="Search item, category, SKU..." class="w-full bg-white border border-slate-300 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
    </div>

    <!-- 4 High-Density KPI Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Stock Units -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Total Stock Units</div>
                <div class="text-2xl font-bold text-slate-900">{{ number_format($items->sum('stock_quantity')) }}</div>
                <div class="text-[11px] text-blue-600 font-medium flex items-center">
                    <i class="fa-solid fa-boxes-stacked mr-1"></i> Warehouse Stores
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>

        <!-- Product SKUs -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Catalogued SKUs</div>
                <div class="text-2xl font-bold text-slate-900">{{ count($items) }}</div>
                <div class="text-[11px] text-emerald-600 font-medium flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span> Active products
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Registered Suppliers -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Verified Suppliers</div>
                <div class="text-2xl font-bold text-slate-900">{{ count($suppliers) }}</div>
                <div class="text-[11px] text-purple-600 font-medium flex items-center">
                    <i class="fa-solid fa-handshake mr-1"></i> Procurement Partners
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-truck"></i>
            </div>
        </div>

        <!-- Low Stock Warnings -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Low Stock Warnings</div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $items->filter(function($i) { return $i->stock_quantity <= $i->reorder_level; })->count() }}
                </div>
                <div class="text-[11px] text-amber-600 font-medium flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Reorder Threshold
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>
    </div>

    @if($tab === 'suppliers')
        <!-- ================================================================= -->
        <!-- TAB: SUPPLIERS & VENDORS                                          -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Authorized Suppliers &amp; Vendors</h2>
                        <p class="text-[11px] text-slate-500">Procurement contact directory, credit terms, and order history.</p>
                    </div>
                </div>
                <button type="button" onclick="alert('New supplier registration modal');" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-plus mr-1"></i> Add Supplier
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Supplier Firm Name</th>
                            <th class="py-3 px-4">Key Contact Person</th>
                            <th class="py-3 px-4">Phone Number</th>
                            <th class="py-3 px-4">Email Address</th>
                            <th class="py-3 px-4">Billing Address</th>
                            <th class="py-3 px-4 text-right">Orders</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($suppliers as $sup)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-building text-slate-400"></i>
                                        <span>{{ $sup->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-800">{{ $sup->contact_person }}</td>
                                <td class="py-3.5 px-4 font-mono text-slate-700">{{ $sup->phone }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $sup->email }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $sup->address }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="alert('Purchase orders for: {{ addslashes($sup->name) }}')" class="inline-flex items-center space-x-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-2.5 py-1.5 rounded-lg shadow-sm transition">
                                        <i class="fa-solid fa-file-invoice text-[11px]"></i>
                                        <span>PO History</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400">No registered suppliers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'pos')
        <!-- ================================================================= -->
        <!-- TAB: STORE POS BILLING COUNTER                                    -->
        <!-- ================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Item Selection Grid (8 cols) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-cash-register text-emerald-600 text-base"></i>
                            <h2 class="text-sm font-bold text-slate-900">School Store Items Catalog</h2>
                        </div>
                        <span class="text-xs text-slate-500">Click item to add to bill</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($items as $it)
                            <div class="bg-slate-50 hover:bg-slate-100/80 border border-slate-200 rounded-xl p-3.5 flex flex-col justify-between transition hover:shadow-sm cursor-pointer group" onclick="addToCart('{{ addslashes($it->name) }}', {{ $it->unit_price }})">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div class="font-bold text-slate-900 text-xs group-hover:text-blue-600 transition">{{ $it->name }}</div>
                                        <span class="text-[10px] font-semibold bg-white px-1.5 py-0.2 rounded border border-slate-200 text-slate-600">
                                            {{ $it->category_name ?? 'General' }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">Stock: <span class="font-bold text-slate-800">{{ $it->stock_quantity }} {{ $it->unit }}</span></div>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-200/60 mt-3">
                                    <div class="text-sm font-bold text-blue-700">₹{{ number_format($it->unit_price, 2) }}</div>
                                    <span class="w-6 h-6 rounded-lg bg-blue-600 group-hover:bg-blue-700 text-white flex items-center justify-center text-xs font-bold transition">
                                        +
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right POS Bill Summary (4 cols) -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4 sticky top-20">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Active Order Receipt</h3>
                        </div>
                        <span class="font-mono text-xs text-slate-500">POS-#9842</span>
                    </div>

                    <!-- Items in cart list -->
                    <div class="space-y-2 text-xs divide-y divide-slate-100" id="cartItemsList">
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <div class="font-semibold text-slate-800">School Uniform Blazer (Navy Blue)</div>
                                <div class="text-[10px] text-slate-500">Size: 34 &middot; 1 pcs &times; ₹1,250.00</div>
                            </div>
                            <span class="font-bold text-slate-900">₹1,250.00</span>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <div class="font-semibold text-slate-800">School Academic Diary 2026-27</div>
                                <div class="text-[10px] text-slate-500">Hardcover &middot; 1 pcs &times; ₹150.00</div>
                            </div>
                            <span class="font-bold text-slate-900">₹150.00</span>
                        </div>
                    </div>

                    <!-- Totals breakdown -->
                    <div class="bg-slate-50 rounded-xl p-3 space-y-1.5 text-xs border border-slate-200/80">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Subtotal Amount:</span>
                            <span class="font-mono" id="posSubtotal">₹1,400.00</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Applicable GST (5%):</span>
                            <span class="font-mono">₹70.00</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-900 font-bold text-sm pt-2 border-t border-slate-200">
                            <span>Total Payable:</span>
                            <span class="text-emerald-700 font-mono text-base" id="posGrandTotal">₹1,470.00</span>
                        </div>
                    </div>

                    <!-- Payment Mode -->
                    <div class="space-y-1.5 text-xs">
                        <label class="block font-semibold text-slate-700">Tender / Payment Mode</label>
                        <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none">
                            <option>Cash at Counter</option>
                            <option>UPI / QR Scan (PhonePe / GPay)</option>
                            <option>Student Smart Wallet Debit</option>
                            <option>Card Terminal (POS Machine)</option>
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <button type="button" onclick="alert('Sale processed successfully! Receipt printed.');" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs py-3 rounded-xl shadow-sm shadow-emerald-500/20 transition flex items-center justify-center space-x-2 cursor-pointer">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Complete Sale &amp; Print Bill</span>
                    </button>
                </div>
            </div>
        </div>

    @else
        <!-- ================================================================= -->
        <!-- TAB: ITEM MASTER / INVENTORY WAREHOUSE (DEFAULT)                  -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Warehouse Inventory Master Register</h2>
                        <p class="text-[11px] text-slate-500">Real-time stock ledger, unit pricing, reorder thresholds, and bin locations.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold border border-blue-200">
                        {{ count($items) }} SKUs Registered
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700" id="inventoryItemsTable">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Item Name &amp; Description</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Unit Price (₹)</th>
                            <th class="py-3 px-4">Current Warehouse Stock</th>
                            <th class="py-3 px-4">Reorder Level</th>
                            <th class="py-3 px-4">Stock Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $it)
                            <tr class="hover:bg-slate-50/80 transition inventory-item-row">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-box text-slate-400"></i>
                                        <span class="item-name">{{ $it->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $it->category_name ?? 'General Supplies' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">₹{{ number_format($it->unit_price, 2) }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $it->stock_quantity }} {{ $it->unit }}</div>
                                    <div class="text-[10px] text-slate-500">In Store Bin</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-700">{{ $it->reorder_level }} {{ $it->unit }}</td>
                                <td class="py-3.5 px-4">
                                    @if($it->stock_quantity <= $it->reorder_level)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                                            <i class="fa-solid fa-triangle-exclamation mr-1 text-red-600"></i> Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-check mr-1 text-emerald-600"></i> In Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="alert('Add stock batch for: {{ addslashes($it->name) }}')" class="inline-flex items-center space-x-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-2.5 py-1.5 rounded-lg shadow-sm transition">
                                        <i class="fa-solid fa-plus text-[11px]"></i>
                                        <span>Add Stock</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">No inventory items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- 🔥 TAILWIND PURE MODAL: ADD INVENTORY ITEM                                -->
<!-- ========================================================================= -->
<div id="addItemModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Add New Inventory Item</h3>
            </div>
            <button type="button" onclick="closeModal('addItemModal')" class="text-slate-400 hover:text-slate-600 text-lg p-1 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form onsubmit="event.preventDefault(); alert('Inventory item created successfully!'); closeModal('addItemModal');" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Item / SKU Name <span class="text-red-500">*</span></label>
                <input type="text" required placeholder="e.g. Winter Uniform Pullover (Size 32)" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Item Category <span class="text-red-500">*</span></label>
                    <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Unit of Measure</label>
                    <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="sets">Sets</option>
                        <option value="boxes">Boxes</option>
                        <option value="reams">Reams</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Unit Selling Price (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" required placeholder="850.00" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Opening Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" required value="20" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Reorder Level Alert</label>
                    <input type="number" value="5" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Warehouse Bin / Shelf Location</label>
                    <input type="text" placeholder="Shelf C-02" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('addItemModal')" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Item</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterInventoryTable() {
    const q = (document.getElementById('inventorySearchInput').value || '').toLowerCase();
    const rows = document.querySelectorAll('.inventory-item-row');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}

function addToCart(name, price) {
    alert('Added to POS Cart: ' + name + ' (₹' + price.toFixed(2) + ')');
}
</script>
@endsection
