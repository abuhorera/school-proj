@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-4xl mx-auto">
    @include('inventory.nav')

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-box-open text-sky-600"></i>
                Create New Inventory Item
            </span>
            <a href="{{ route('school.inventory.items') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Item List</a>
        </div>

        <form action="{{ route('school.inventory.items.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Section 1: Item Identity -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">1. Basic Item Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. A4 Copier Paper, Football, Beaker 250ml" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                        <select name="category_id" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Classification / Type <span class="text-rose-500">*</span></label>
                        <select name="item_type" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="Consumable">Consumable (Office/Lab/Classroom supplies)</option>
                            <option value="Saleable">Saleable (For Point of Sale to Students/Parents)</option>
                            <option value="Asset">Fixed Asset (School Property/Furniture)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Measurement Unit <span class="text-rose-500">*</span></label>
                        <select name="unit" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                            <option value="pcs">pcs (Pieces)</option>
                            <option value="box">box (Box)</option>
                            <option value="ream">ream (Ream)</option>
                            <option value="set">set (Set)</option>
                            <option value="pair">pair (Pair)</option>
                            <option value="kg">kg (Kilogram)</option>
                            <option value="btl">btl (Bottle)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Codes & Storage -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">2. SKU, Barcode & Storage Location</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">SKU (Auto-generated if empty)</label>
                        <input type="text" name="sku" placeholder="e.g. SKU-PAP-101" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Barcode</label>
                        <input type="text" name="barcode" placeholder="Scan or enter barcode" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Storage Location / Rack</label>
                        <input type="text" name="location" placeholder="e.g. Shelf A-2, Biology Lab, Main Store" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Stock Levels & Pricing -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">3. Stock Quantities & Valuation</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Opening Stock</label>
                        <input type="number" name="stock_quantity" min="0" value="0" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Min. Re-order Level</label>
                        <input type="number" name="minimum_stock_level" min="0" value="5" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Purchase / Cost Price (₹)</label>
                        <input type="number" step="0.01" name="unit_price" min="0" value="0.00" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Selling Price (₹) [POS]</label>
                        <input type="number" step="0.01" name="selling_price" min="0" value="0.00" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Section 4: Expiry & Description -->
            <div class="pt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Expiry Date (Optional, for Medical/Lab)</label>
                        <input type="date" name="expiry_date" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Description / Specifications</label>
                        <textarea name="description" rows="2" placeholder="Material details, brand, dimensions..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.inventory.items') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Save Item to Inventory
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

