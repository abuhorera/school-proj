@extends('layouts.app')

@section('title', 'Inventory & Stock')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-boxes text-primary mr-2"></i> Inventory &amp; Stock Management</h3>
            <p class="text-muted mb-0 small">Warehouse ledger, store items, supplier procurement, purchase orders, and uniform/book POS sales.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addItemModal">
                <i class="fas fa-plus-circle mr-1"></i> Add Inventory Item
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'items' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'items']) }}">
                <i class="fas fa-list mr-1"></i> Item List
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'categories' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'categories']) }}">
                <i class="fas fa-tags mr-1"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'suppliers' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'suppliers']) }}">
                <i class="fas fa-truck mr-1"></i> Suppliers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'pos' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'pos']) }}">
                <i class="fas fa-cash-register mr-1"></i> Point of Sale (POS)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'purchase-orders' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.inventory', ['tab' => 'purchase-orders']) }}">
                <i class="fas fa-file-invoice mr-1"></i> Purchase Orders
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-boxes fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ $items->sum('stock_quantity') }}</div>
                        <small class="text-muted">Total Stock Units</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-layer-group fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($items) }}</div>
                        <small class="text-muted">Product SKUs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-truck fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($suppliers) }}</div>
                        <small class="text-muted">Registered Suppliers</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-exclamation-triangle fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">0</div>
                        <small class="text-muted">Low Stock Warnings</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'suppliers')
        {{-- Suppliers View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-truck text-primary mr-2"></i> Authorized Suppliers &amp; Vendors</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Supplier</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $sup)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $sup->name }}</td>
                                    <td>{{ $sup->contact_person }}</td>
                                    <td>{{ $sup->phone }}</td>
                                    <td>{{ $sup->email }}</td>
                                    <td class="text-muted small">{{ $sup->address }}</td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Orders</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No suppliers registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'pos')
        {{-- POS Counter View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-cash-register text-primary mr-2"></i> School Store POS Counter</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-muted mb-3">Select Items to Bill</h6>
                        <div class="row">
                            @foreach($items as $it)
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $it->name }}</div>
                                            <small class="text-primary font-weight-bold">₹{{ number_format($it->unit_price, 2) }}</small>
                                            <small class="text-muted d-block">Stock: {{ $it->stock_quantity }}</small>
                                        </div>
                                        <button class="btn btn-sm btn-primary" onclick="alert('Added {{ $it->name }} to cart')">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-3 border rounded bg-white shadow-sm">
                            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-receipt text-primary mr-1"></i> Current Order Receipt</h6>
                            <div class="d-flex justify-content-between small py-1 border-bottom">
                                <span>School Blazer (Navy Blue) x 1</span>
                                <strong>₹1,250.00</strong>
                            </div>
                            <div class="d-flex justify-content-between small py-1 border-bottom">
                                <span>School Diary 2026-27 x 1</span>
                                <strong>₹150.00</strong>
                            </div>
                            <div class="d-flex justify-content-between font-weight-bold py-2 mt-2">
                                <span>Total Amount:</span>
                                <span class="text-primary h5 mb-0">₹1,400.00</span>
                            </div>
                            <button class="btn btn-success btn-block font-weight-bold mt-3" onclick="alert('POS Sale completed! Printing receipt...')">
                                <i class="fas fa-check-circle mr-1"></i> Complete Sale &amp; Print Bill
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Item List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary mr-2"></i> Warehouse Inventory Items</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($items) }} SKUs</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th>Current Stock</th>
                                <th>Reorder Level</th>
                                <th>Stock Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $it)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-box text-muted mr-1"></i> {{ $it->name }}</td>
                                    <td><span class="badge badge-light border">{{ $it->category_name ?? 'General' }}</span></td>
                                    <td>₹{{ number_format($it->unit_price, 2) }}</td>
                                    <td class="font-weight-bold">{{ $it->stock_quantity }} {{ $it->unit }}</td>
                                    <td>{{ $it->reorder_level }} {{ $it->unit }}</td>
                                    <td>
                                        @if($it->stock_quantity <= $it->reorder_level)
                                            <span class="badge badge-danger px-2 py-1">Low Stock</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">In Stock</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Stock</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No inventory items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Item Modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-box text-primary mr-1"></i> Add Inventory Item</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Item Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Laboratory Safety Goggles">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Unit Price (₹)</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" placeholder="250.00">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Initial Quantity</label>
                        <input type="number" class="form-control form-control-sm" value="20">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Item</button>
            </div>
        </div>
    </div>
</div>
@endsection

