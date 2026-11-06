@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-4xl mx-auto">
    @include('inventory.nav')

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-sky-600"></i>
                Edit Inventory Item: {{ $item->name }}
            </span>
            <a href="{{ route('school.inventory.items') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Item List</a>
        </div>

        <form action="{{ route('school.inventory.items.update', $item->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Item Identity -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">1. Basic Item Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                        <select name="category_id" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Classification / Type <span class="text-rose-500">*</span></label>
                        <select name="item_type" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="Consumable" {{ old('item_type', $item->item_type) == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                            <option value="Saleable" {{ old('item_type', $item->item_type) == 'Saleable' ? 'selected' : '' }}>Saleable (POS)</option>
                            <option value="Asset" {{ old('item_type', $item->item_type) == 'Asset' ? 'selected' : '' }}>Fixed Asset</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Measurement Unit <span class="text-rose-500">*</span></label>
                        <select name="unit" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="pcs" {{ $item->unit == 'pcs' ? 'selected' : '' }}>pcs (Pieces)</option>
                            <option value="box" {{ $item->unit == 'box' ? 'selected' : '' }}>box (Box)</option>
                            <option value="ream" {{ $item->unit == 'ream' ? 'selected' : '' }}>ream (Ream)</option>
                            <option value="set" {{ $item->unit == 'set' ? 'selected' : '' }}>set (Set)</option>
                            <option value="pair" {{ $item->unit == 'pair' ? 'selected' : '' }}>pair (Pair)</option>
                            <option value="kg" {{ $item->unit == 'kg' ? 'selected' : '' }}>kg (Kilogram)</option>
                            <option value="btl" {{ $item->unit == 'btl' ? 'selected' : '' }}>btl (Bottle)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Codes & Storage -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">2. SKU, Barcode & Storage Location</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $item->sku) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode', $item->barcode) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Storage Location / Rack</label>
                        <input type="text" name="location" value="{{ old('location', $item->location) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Stock Levels & Pricing -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">3. Stock Quantities & Valuation</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Min. Re-order Level</label>
                        <input type="number" name="minimum_stock_level" min="0" value="{{ old('minimum_stock_level', $item->minimum_stock_level) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Purchase / Cost Price (₹)</label>
                        <input type="number" step="0.01" name="unit_price" min="0" value="{{ old('unit_price', $item->unit_price) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Selling Price (₹) [POS]</label>
                        <input type="number" step="0.01" name="selling_price" min="0" value="{{ old('selling_price', $item->selling_price) }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Section 4: Expiry & Description -->
            <div class="pt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Expiry Date</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Description / Specifications</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.inventory.items') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-sky-600 text-white hover:bg-sky-700 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Update Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

