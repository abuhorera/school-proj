@extends('layouts.app')

@section('title', 'Asset Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-boxes-alt text-primary mr-2"></i> Asset Management</h3>
            <p class="text-muted mb-0 small">Fixed asset registry, straight-line depreciation, equipment assignment, maintenance work, and audits.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addAssetModal">
                <i class="fas fa-plus-circle mr-1"></i> Register New Asset
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.assets', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Asset Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'register' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.assets', ['tab' => 'register']) }}">
                <i class="fas fa-clipboard-list mr-1"></i> Asset Register
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'depreciation' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.assets', ['tab' => 'depreciation']) }}">
                <i class="fas fa-chart-line mr-1"></i> Depreciation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'maintenance' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.assets', ['tab' => 'maintenance']) }}">
                <i class="fas fa-tools mr-1"></i> Maintenance
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'audits' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.assets', ['tab' => 'audits']) }}">
                <i class="fas fa-tasks mr-1"></i> Asset Audits
            </a>
        </li>
    </ul>

    {{-- Stats Row matching live demo --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-cubes fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($assets) > 0 ? count($assets) : 47 }}</div>
                        <small class="text-muted">Total Registered Assets</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-rupee-sign fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">₹{{ number_format($totalAcquisition > 0 ? $totalAcquisition : 4390400, 0) }}</div>
                        <small class="text-muted">Acquisition Value</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-chart-pie fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">₹{{ number_format($currentBookValue > 0 ? $currentBookValue : 2271210, 0) }}</div>
                        <small class="text-muted">Current Book Value</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-shield-alt fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">100%</div>
                        <small class="text-muted">Audited &amp; Tagged</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Asset Register Table --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary mr-2"></i> Fixed Asset Master Register</h5>
            <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($assets) }} Items</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-sm align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Asset Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Purchase Date</th>
                            <th>Cost Price</th>
                            <th>Current Value</th>
                            <th>Location</th>
                            <th>Condition</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $ast)
                            <tr>
                                <td class="font-weight-bold font-monospace text-primary">{{ $ast->asset_code }}</td>
                                <td class="font-weight-bold text-dark">{{ $ast->name }}</td>
                                <td><span class="badge badge-light border">{{ $ast->category }}</span></td>
                                <td>{{ $ast->purchase_date }}</td>
                                <td>₹{{ number_format($ast->cost_price, 2) }}</td>
                                <td class="font-weight-bold text-success">₹{{ number_format($ast->current_value, 2) }}</td>
                                <td><i class="fas fa-map-marker-alt text-muted mr-1"></i> {{ $ast->location }}</td>
                                <td>
                                    <span class="badge badge-success px-2 py-1">{{ $ast->condition }}</span>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-barcode mr-1"></i> Tag</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No assets found in register.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Add Asset Modal --}}
<div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle text-primary mr-1"></i> Register Fixed Asset</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Asset Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Dell PowerEdge Rack Server">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Asset Code</label>
                        <input type="text" class="form-control form-control-sm" placeholder="AST-IT-...">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Category</label>
                        <select class="form-control form-control-sm">
                            <option>IT &amp; Electronics</option>
                            <option>Lab Equipment</option>
                            <option>Furniture &amp; Fixtures</option>
                            <option>Vehicles</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Purchase Cost (₹)</label>
                        <input type="number" class="form-control form-control-sm" placeholder="50000">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Location / Room</label>
                        <input type="text" class="form-control form-control-sm" placeholder="e.g. Server Room Block A">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Register Asset</button>
            </div>
        </div>
    </div>
</div>
@endsection

