@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    {{-- Asset Header Dossier Banner --}}
    <div class="ast-card mb-4" style="border-top: 4px solid #0284c7;">
        <div class="ast-card-body d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <h3 class="mb-0 font-weight-700" style="color: #0f172a; font-size: 1.5rem;">{{ $asset->name }}</h3>
                    <span class="ast-code" style="font-size: 0.95rem;">{{ $asset->asset_tag }}</span>
                    <span class="ast-badge ast-badge-{{ $asset->status }}" style="font-size: 0.85rem;">
                        {{ $asset->status_label }}
                    </span>
                    <span class="badge badge-light border">{{ $asset->condition }}</span>
                </div>
                <div class="text-muted mt-1" style="font-size: 0.88rem;">
                    {{ $asset->category->name ?? 'Uncategorized' }}
                    @if($asset->brand || $asset->model_number) · {{ $asset->brand }} {{ $asset->model_number }} @endif
                    @if($asset->location) · <i class="fas fa-map-marker-alt text-danger"></i> {{ $asset->location }} @endif
                </div>
            </div>

            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <a href="{{ route('school.assets.assets.tag', $asset->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-qrcode mr-1"></i> Tag
                </a>
                <a href="{{ route('school.assets.assets.edit', $asset->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <button type="button" class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#maintenanceModal">
                    <i class="fas fa-wrench mr-1"></i> Maintenance
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#disposeModal">
                    <i class="fas fa-trash-alt mr-1"></i> Dispose
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Specs & Financials --}}
        <div class="col-lg-6">
            {{-- Specifications --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-info-circle text-primary"></i> Specifications</h4>
                </div>
                <div class="ast-card-body p-0">
                    <table class="ast-table">
                        <tbody>
                            <tr>
                                <th style="width: 35%;">Asset Tag</th>
                                <td><span class="ast-code">{{ $asset->asset_tag }}</span></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $asset->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Brand / Model</th>
                                <td>{{ $asset->brand ?? '—' }} {{ $asset->model_number ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Serial Number</th>
                                <td>{{ $asset->serial_number ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td>{{ $asset->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Condition</th>
                                <td><span class="badge badge-light border">{{ $asset->condition }}</span></td>
                            </tr>
                            <tr>
                                <th>Physical Location</th>
                                <td>{{ $asset->location ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $asset->supplier_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Funding Source</th>
                                <td>{{ $asset->funding_source ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Financials --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-rupee-sign text-success"></i> Financials & Valuation</h4>
                </div>
                <div class="ast-card-body p-0">
                    <table class="ast-table">
                        <tbody>
                            <tr>
                                <th style="width: 35%;">Purchase Cost</th>
                                <td class="font-weight-bold">₹{{ number_format($asset->purchase_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Current Book Value</th>
                                <td class="font-weight-bold text-primary">₹{{ number_format($asset->current_book_value, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Depreciation Method</th>
                                <td>{{ $asset->depreciation_method }}</td>
                            </tr>
                            <tr>
                                <th>Useful Life</th>
                                <td>{{ $asset->useful_life_months ? $asset->useful_life_months . ' months' : '—' }}</td>
                            </tr>
                            <tr>
                                <th>Salvage Value</th>
                                <td>₹{{ number_format($asset->salvage_value, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Depreciation Rate</th>
                                <td>{{ $asset->depreciation_rate }}% / year</td>
                            </tr>
                            <tr>
                                <th>Purchase Date</th>
                                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d M Y') : '—' }}</td>
                            </tr>
                            <tr>
                                <th>Invoice Number</th>
                                <td>{{ $asset->invoice_number ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Warranty & Insurance --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-shield-alt text-warning"></i> Warranty & Insurance</h4>
                </div>
                <div class="ast-card-body p-0">
                    <table class="ast-table">
                        <tbody>
                            <tr>
                                <th style="width: 35%;">Warranty Provider</th>
                                <td>{{ $asset->warranty_provider ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Warranty Expiry</th>
                                <td>
                                    @if($asset->warranty_expiry_date)
                                        <span class="{{ $asset->warranty_expiry_date < \Carbon\Carbon::today() ? 'text-danger font-weight-bold' : '' }}">
                                            {{ $asset->warranty_expiry_date->format('d M Y') }}
                                        </span>
                                    @else — @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Insurance Provider</th>
                                <td>{{ $asset->insurance_provider ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Policy Number</th>
                                <td>{{ $asset->insurance_policy_number ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Insurance Expiry</th>
                                <td>{{ $asset->insurance_expiry_date ? $asset->insurance_expiry_date->format('d M Y') : '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column: Custody, Maintenance & Timeline --}}
        <div class="col-lg-6">
            {{-- Current Custody / Allocation --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-hand-holding text-indigo"></i> Custody / Allocation</h4>
                    <button type="button" class="btn btn-xs btn-outline-primary" data-toggle="modal" data-target="#allocateModal">
                        Allocate Asset
                    </button>
                </div>
                <div class="ast-card-body">
                    @if($asset->activeAssignment)
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-info">{{ $asset->activeAssignment->assignable_type }}</span>
                                <span class="badge badge-success">Active Holder</span>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-1">{{ $asset->activeAssignment->holder_name }}</h5>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                Assigned: <strong>{{ $asset->activeAssignment->assigned_date->format('d M Y') }}</strong>
                                @if($asset->activeAssignment->expected_return_date)
                                    · Expected: <strong>{{ $asset->activeAssignment->expected_return_date->format('d M Y') }}</strong>
                                @endif
                            </div>
                            @if($asset->activeAssignment->remarks)
                                <div class="mt-2 text-secondary" style="font-size: 0.82rem; font-style: italic;">
                                    "{{ $asset->activeAssignment->remarks }}"
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-light border text-center text-muted py-3">
                            <i class="fas fa-warehouse fa-2x mb-2 d-block text-secondary"></i>
                            Asset currently in store (unassigned).
                        </div>
                    @endif

                    @if($asset->assignments->count() > 1)
                        <h6 class="font-weight-600 text-muted mt-3 mb-2" style="font-size: 0.8rem; text-transform: uppercase;">Past Allocations</h6>
                        <ul class="list-unstyled mb-0" style="font-size: 0.82rem;">
                            @foreach($asset->assignments->skip(1)->take(3) as $past)
                                <li class="py-1 border-bottom d-flex justify-content-between">
                                    <span>{{ $past->holder_name }} ({{ $past->assignable_type }})</span>
                                    <span class="text-muted">{{ $past->assigned_date->format('M Y') }} · {{ ucfirst($past->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Recent Maintenance Logs --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-wrench text-warning"></i> Maintenance History</h4>
                </div>
                <div class="ast-card-body p-0">
                    <table class="ast-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Reported</th>
                                <th>Cost</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asset->maintenanceLogs->take(4) as $log)
                                <tr>
                                    <td>
                                        <strong>{{ $log->maintenance_type }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($log->problem_description, 35) }}</div>
                                    </td>
                                    <td>{{ $log->reported_date ? $log->reported_date->format('d M Y') : '—' }}</td>
                                    <td>₹{{ number_format($log->cost, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $log->status == 'completed' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No maintenance logs recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Lifecycle Movements / Timeline --}}
            <div class="ast-card mb-4">
                <div class="ast-card-header">
                    <h4 class="ast-card-title"><i class="fas fa-history text-secondary"></i> Lifecycle Movements</h4>
                </div>
                <div class="ast-card-body">
                    <div class="timeline" style="border-left: 2px solid #e2e8f0; padding-left: 16px; margin-left: 8px;">
                        @forelse($asset->movements->take(6) as $m)
                            <div class="mb-3 position-relative">
                                <div style="position: absolute; left: -22px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #0284c7;"></div>
                                <div class="font-weight-600 text-dark" style="font-size: 0.88rem;">{{ $m->event_type }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">{{ $m->event_date ? $m->event_date->format('d M Y') : '—' }} by {{ $m->actor }}</div>
                                <div class="text-secondary mt-1" style="font-size: 0.82rem;">{{ $m->details }}</div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-2">No movement logs recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 1: Allocate Asset --}}
<div class="modal fade" id="allocateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.assignments.store') }}">
            @csrf
            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Allocate Asset: {{ $asset->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Assign To Type <span class="text-danger">*</span></label>
                        <select name="assignable_type" class="form-control" required>
                            <option value="Staff">Staff</option>
                            <option value="Student">Student</option>
                            <option value="Class / Room">Class / Room</option>
                            <option value="Department">Department</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Holder Name / Target <span class="text-danger">*</span></label>
                        <input type="text" name="holder_name" class="form-control" placeholder="e.g. Ramesh Kumar, Class X-B, Physics Dept" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Assigned Date <span class="text-danger">*</span></label>
                            <input type="date" name="assigned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Expected Return Date</label>
                            <input type="date" name="expected_return_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Condition on Handover <span class="text-danger">*</span></label>
                        <select name="condition_on_assign" class="form-control" required>
                            <option value="New">New</option>
                            <option value="Good" selected>Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Purpose or custody notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ast-primary">Confirm Allocation</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal 2: Log Maintenance --}}
<div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.maintenance.logs.store') }}">
            @csrf
            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Maintenance: {{ $asset->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Maintenance Type <span class="text-danger">*</span></label>
                        <select name="maintenance_type" class="form-control" required>
                            <option value="Routine Service">Routine Service</option>
                            <option value="Repair">Repair / Breakdown</option>
                            <option value="Inspection">Inspection</option>
                            <option value="Calibration">Calibration</option>
                            <option value="Deep Clean">Deep Clean</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="reported">Reported</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Problem Description <span class="text-danger">*</span></label>
                        <textarea name="problem_description" class="form-control" rows="2" required placeholder="Describe issue or required maintenance..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Reported Date <span class="text-danger">*</span></label>
                            <input type="date" name="reported_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Cost (₹)</label>
                            <input type="number" step="0.01" name="cost" class="form-control" placeholder="0.00" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Performed By / Technician</label>
                        <input type="text" name="performed_by" class="form-control" placeholder="e.g. In-house Tech, External Vendor">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Maintenance Log</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal 3: Dispose Asset --}}
<div class="modal fade" id="disposeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.disposals.store') }}">
            @csrf
            <input type="hidden" name="asset_id" value="{{ $asset->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Disposal: {{ $asset->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 mb-3" style="font-size: 0.85rem;">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Current Book Value: <strong>₹{{ number_format($asset->current_book_value, 2) }}</strong>. Disposing this asset will release custody and write down book value to zero.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Disposal Method <span class="text-danger">*</span></label>
                        <select name="disposal_method" class="form-control" required>
                            <option value="Sold">Sold</option>
                            <option value="Scrapped">Scrapped</option>
                            <option value="Donated">Donated</option>
                            <option value="Written Off">Written Off</option>
                            <option value="Lost">Lost</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Disposal Date <span class="text-danger">*</span></label>
                            <input type="date" name="disposal_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Realised Value (₹)</label>
                            <input type="number" step="0.01" name="realised_value" class="form-control" placeholder="0.00" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Recipient / Buyer</label>
                        <input type="text" name="recipient" class="form-control" placeholder="e.g. Apex Scrap Mart, Trust Community Center">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="2" required placeholder="Specify justification for disposal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Disposal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

