@extends('layouts.app')

@section('title', 'Canteen & Cashless Campus')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-utensils text-primary mr-2"></i> Canteen &amp; Cashless Campus</h3>
            <p class="text-muted mb-0 small">Smart digital student wallets, touch POS sales counter, nutrition menu, shift reconciliation, and parent spending limits.</p>
        </div>
        <div>
            <a href="{{ route('modules.canteen', ['tab' => 'pos']) }}" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
                <i class="fas fa-cash-register mr-1"></i> Open POS Terminal
            </a>
        </div>
    </div>

    {{-- Sub Navigation Tabs matching live demo --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'pos' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'pos']) }}">
                <i class="fas fa-cash-register mr-1"></i> POS Counter
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'wallets' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'wallets']) }}">
                <i class="fas fa-wallet mr-1"></i> Campus Wallets
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'transactions' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'transactions']) }}">
                <i class="fas fa-receipt mr-1"></i> Transactions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'products' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'products']) }}">
                <i class="fas fa-hamburger mr-1"></i> Menu &amp; Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'shifts' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'shifts']) }}">
                <i class="fas fa-clock mr-1"></i> Shifts &amp; Day Sheet
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'guide' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.canteen', ['tab' => 'guide']) }}">
                <i class="fas fa-book-open mr-1"></i> Canteen Guide
            </a>
        </li>
    </ul>

    {{-- Stats Row matching live demo --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-rupee-sign fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">₹85.00</div>
                        <small class="text-muted">Sales Today &middot; 1 bill</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-arrow-circle-up fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">₹500.00</div>
                        <small class="text-muted">Topped up today</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-id-card-alt fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($wallets) }}</div>
                        <small class="text-muted">Active Campus Wallets</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-utensils fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($products) }}</div>
                        <small class="text-muted">Menu Items Available</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'pos')
        {{-- POS Fast Counter View --}}
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-th text-primary mr-2"></i> Touch POS Menu Selection</h5>
                        <div class="input-group input-group-sm" style="max-width: 200px;">
                            <input type="text" class="form-control" placeholder="Search menu...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($products as $p)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="p-3 border rounded text-center bg-light h-100 d-flex flex-column justify-content-between">
                                        <div class="mb-2">
                                            <i class="fas fa-hamburger fa-2x text-warning mb-2"></i>
                                            <h6 class="font-weight-bold text-dark mb-1">{{ $p->name }}</h6>
                                            <span class="badge badge-light border">{{ $p->category }}</span>
                                        </div>
                                        <div>
                                            <div class="h5 text-primary font-weight-bold mb-2">₹{{ number_format($p->price, 2) }}</div>
                                            <button class="btn btn-primary btn-sm btn-block font-weight-bold" onclick="alert('Added {{ $p->name }} to bill')">
                                                <i class="fas fa-plus mr-1"></i> Add to Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 font-weight-bold"><i class="fas fa-receipt mr-2"></i> Live POS Bill Summary</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="form-group mb-3">
                            <label class="small text-muted font-weight-bold">Scan Student RFID Card or QR</label>
                            <input type="text" class="form-control form-control-sm font-monospace" value="RFID-AARAV-10A (Balance: ₹650.00)" readonly>
                        </div>
                        <div class="border rounded p-2 mb-3 bg-light">
                            <div class="d-flex justify-content-between small py-1 border-bottom">
                                <span>Grilled Cheese Sandwich x 1</span>
                                <strong>₹45.00</strong>
                            </div>
                            <div class="d-flex justify-content-between small py-1 border-bottom">
                                <span>Fresh Orange Juice x 1</span>
                                <strong>₹40.00</strong>
                            </div>
                            <div class="d-flex justify-content-between font-weight-bold pt-2">
                                <span>Total Payable:</span>
                                <span class="text-primary font-weight-bold h5 mb-0">₹85.00</span>
                            </div>
                        </div>
                        <button class="btn btn-success btn-block py-2 font-weight-bold" onclick="alert('Payment debited from Student Wallet ₹85.00! Remaining balance: ₹565.00')">
                            <i class="fas fa-fingerprint mr-1"></i> Debit Smart Wallet &amp; Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tab === 'wallets')
        {{-- Wallets List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-wallet text-primary mr-2"></i> Campus Cashless Wallets</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Top-up Wallet</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Card Holder Name</th>
                                <th>Account Type</th>
                                <th>Current Balance</th>
                                <th>Daily Spend Cap</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wallets as $w)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-id-card text-muted mr-1"></i> {{ $w->user_name }}</td>
                                    <td><span class="badge badge-light border">{{ $w->user_type }}</span></td>
                                    <td class="font-weight-bold text-success h6 mb-0">₹{{ number_format($w->balance, 2) }}</td>
                                    <td>₹{{ number_format($w->daily_limit, 2) }} / day</td>
                                    <td><span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> Active</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Add Money</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No campus wallets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Products Menu View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-hamburger text-primary mr-2"></i> Canteen Nutrition Menu &amp; Rates</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Product</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Selling Price</th>
                                <th>Cost Price</th>
                                <th>Available Stock</th>
                                <th>Availability</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $prod)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $prod->name }}</td>
                                    <td><span class="badge badge-light border">{{ $prod->category }}</span></td>
                                    <td class="font-weight-bold text-primary">₹{{ number_format($prod->price, 2) }}</td>
                                    <td class="text-muted">₹{{ number_format($prod->cost_price, 2) }}</td>
                                    <td>{{ $prod->stock_qty }} servings</td>
                                    <td><span class="badge badge-success px-2 py-1">Available</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Edit</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No products on menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
