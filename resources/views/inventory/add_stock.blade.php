@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-3xl mx-auto">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-plus-square text-emerald-600"></i>
                Add Stock / Inward Inventory
            </span>
            <a href="{{ route('school.inventory.items') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Item List</a>
        </div>

        <form action="{{ route('school.inventory.add-stock.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select Item <span class="text-rose-500">*</span></label>
                <select name="inventory_item_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $it)
                    <option value="{{ $it->id }}" {{ request('item_id') == $it->id ? 'selected' : '' }}>
                        {{ $it->name }} (Available: {{ $it->stock_quantity }} {{ $it->unit }}) - [{{ $it->category->name ?? 'General' }}]
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier / Vendor (Optional)</label>
                <select name="inventory_supplier_id" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    <option value="">-- Direct Purchase / Local Vendor / No PO --</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->supplier_type }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Quantity to Inward <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantity" required placeholder="e.g. 50" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date Inwarded <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Total Cost (₹)</label>
                    <input type="number" step="0.01" min="0" name="cost" placeholder="e.g. 2500.00" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Bill / Delivery Challan Ref</label>
                <textarea name="description" rows="2" placeholder="e.g. Inward against Invoice #INV-8890 from city depot..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.inventory.items') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus-circle"></i> Add Inward Stock
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

