@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-4xl mx-auto">
    @include('inventory.nav')

    <div class="inv-card p-6 mb-6">
        <h3 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-book-open text-sky-600"></i>
            Inventory & Stock Module User Guide
        </h3>
        <p class="text-xs text-slate-500 mb-6">
            Everything you need to know about setting up, replenishing, tracking, and distributing physical assets and consumables across the school campus.
        </p>

        <div class="space-y-6 text-xs text-slate-700">
            <!-- Section 1 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-slate-600"></i>
                    1. Item Classification: Consumable vs Saleable vs Asset
                </h4>
                <ul class="list-disc pl-5 space-y-1 text-slate-600">
                    <li><strong>Consumable:</strong> Internal supplies that are consumed through routine operations (e.g., A4 copier paper, whiteboard markers, science lab chemicals, footballs, first-aid bandages). These can be issued to teachers/departments and tracked.</li>
                    <li><strong>Saleable:</strong> Merchandise sold directly to students or parents via the Point of Sale (POS) counter (e.g., school uniforms, house t-shirts, textbooks, customized badges).</li>
                    <li><strong>Fixed Asset:</strong> Capital equipment that remains school property (e.g., microscopes, 3D printers, sports hurdles, classroom desks).</li>
                </ul>
            </div>

            <!-- Section 2 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                    <i class="fa-solid fa-arrows-spin text-emerald-600"></i>
                    2. Procurement & Replenishment Lifecycle
                </h4>
                <p class="text-slate-600 mb-2">The complete supply chain workflow consists of 4 integrated steps:</p>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 text-center">
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-slate-900 block text-xs">Step 1: Requisition / PO</span>
                        <span class="text-[10px] text-slate-500">Create PO with supplier rates</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-slate-900 block text-xs">Step 2: PO Approval</span>
                        <span class="text-[10px] text-slate-500">Authorized by administrator</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-slate-900 block text-xs">Step 3: Goods Receipt (GRN)</span>
                        <span class="text-[10px] text-slate-500">Goods checked & stock inwarded</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-bold text-slate-900 block text-xs">Step 4: Vendor Settlement</span>
                        <span class="text-[10px] text-slate-500">Payment recorded in ledger</span>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                    <i class="fa-solid fa-cash-register text-purple-600"></i>
                    3. Point of Sale (POS) Counter & Barcode Scanner
                </h4>
                <p class="text-slate-600">
                    The POS module supports rapid cashier transactions with USB or Bluetooth 1D/2D barcode scanners. Any input followed by the Enter key automatically looks up the item SKU/Barcode and adds it directly to the active cart. Supports multiple tender options including Cash, UPI, Debit Cards, and Campus Digital Wallets.
                </p>
            </div>

            <!-- Section 4 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                    <i class="fa-solid fa-bell text-amber-500"></i>
                    4. Safety Stock & Automatic Reorder Alerts
                </h4>
                <p class="text-slate-600">
                    Each item has a configurable <em>Minimum Stock Level</em> threshold. Whenever quantity on hand drops to or below this threshold, an amber warning chip appears on the dashboard and in the item register, providing a one-click <strong>Reorder</strong> shortcut.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

