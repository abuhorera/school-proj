@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <!-- Filters & Action Bar -->
    <div class="inv-card p-4 mb-5">
        <form action="{{ route('school.inventory.items') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Search Item / SKU</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, SKU, barcode..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Category</label>
                <select name="category_id" class="w-full py-1.5 px-3 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Item Type</label>
                <select name="item_type" class="w-full py-1.5 px-3 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Types</option>
                    <option value="Consumable" {{ request('item_type') == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                    <option value="Saleable" {{ request('item_type') == 'Saleable' ? 'selected' : '' }}>Saleable (POS)</option>
                    <option value="Asset" {{ request('item_type') == 'Asset' ? 'selected' : '' }}>Fixed Asset</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stock Status</label>
                <select name="stock_status" class="w-full py-1.5 px-3 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Stock Levels</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock (≤ threshold)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-1.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs rounded-lg transition shadow-2xs">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('school.inventory.items') }}" class="py-1.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Items Table -->
    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-600"></i>
                All Inventory Items ({{ $items->total() }})
            </span>
            <div class="flex items-center space-x-2">
                <a href="{{ route('school.inventory.items.create') }}" class="px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition">
                    <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add New Item
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Item Details</th>
                        <th>SKU / Barcode</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>In Stock</th>
                        <th>Unit Price</th>
                        <th>Expiry</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $items->firstItem() + $index }}</td>
                        <td>
                            <div class="font-bold text-slate-900">{{ $item->name }}</div>
                            @if($item->location)
                            <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-location-dot text-[10px]"></i> {{ $item->location }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="font-mono text-xs font-bold text-slate-700">{{ $item->sku ?: '—' }}</div>
                            @if($item->barcode)
                            <div class="text-[10px] font-mono text-slate-400">||| {{ $item->barcode }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                {{ $item->category->name ?? 'General' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-xs px-2 py-0.5 rounded font-medium {{ $item->item_type === 'Saleable' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-600' }}">
                                {{ $item->item_type }}
                            </span>
                        </td>
                        <td>
                            @if($item->stock_quantity <= 0)
                            <span class="inv-badge inv-badge-danger">Out of Stock</span>
                            @elseif($item->stock_quantity <= $item->minimum_stock_level)
                            <span class="inv-badge inv-badge-warn">
                                <i class="fa-solid fa-exclamation-triangle text-[10px]"></i>
                                {{ $item->stock_quantity }} {{ $item->unit }}
                            </span>
                            @else
                            <span class="inv-badge inv-badge-ok">
                                {{ $item->stock_quantity }} {{ $item->unit }}
                            </span>
                            @endif
                            <div class="text-[10px] text-slate-400 mt-0.5">Min: {{ $item->minimum_stock_level }}</div>
                        </td>
                        <td>
                            <div class="text-xs font-bold text-slate-900">₹{{ number_format($item->unit_price, 2) }}</div>
                            @if($item->selling_price > 0)
                            <div class="text-[10px] text-emerald-600 font-semibold">Sale: ₹{{ number_format($item->selling_price, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->expiry_date)
                                @if($item->isExpired())
                                <span class="text-xs text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded">
                                    Expired {{ $item->expiry_date->format('d M Y') }}
                                </span>
                                @else
                                <span class="text-xs text-slate-600">{{ $item->expiry_date->format('d M Y') }}</span>
                                @endif
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('school.inventory.add-stock') }}?item_id={{ $item->id }}" title="Add Stock" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition">
                                    <i class="fa-solid fa-plus-circle"></i>
                                </a>
                                <a href="{{ route('school.inventory.items.edit', $item->id) }}" title="Edit Item" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('school.inventory.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Item" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                            No inventory items found matching your filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

