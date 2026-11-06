@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <form method="POST" action="{{ route('school.assets.assets.store') }}">
        @csrf

        {{-- Section 1: Identity --}}
        <div class="ast-card mb-4">
            <div class="ast-card-header">
                <h4 class="ast-card-title"><i class="fas fa-fingerprint text-primary"></i> Identity</h4>
            </div>
            <div class="ast-card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Asset Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Dell OptiPlex Desktop" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Asset Tag</label>
                        <input type="text" name="asset_tag" class="form-control" placeholder="{{ $suggestedTag }}" value="{{ old('asset_tag', $suggestedTag) }}">
                        <small class="text-muted">Auto-generated if left blank</small>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Category</label>
                        <select name="asset_category_id" class="form-control">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('asset_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Brand</label>
                        <input type="text" name="brand" class="form-control" placeholder="e.g. Dell, HP, Godrej" value="{{ old('brand') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Model Number</label>
                        <input type="text" name="model_number" class="form-control" placeholder="e.g. OptiPlex 7090" value="{{ old('model_number') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" placeholder="e.g. DL-7090-4491" value="{{ old('serial_number') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
                    </div>
                    <div class="col-md-9 form-group">
                        <label class="font-weight-600">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Hardware configuration, purpose or notes...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Status & Location --}}
        <div class="ast-card mb-4">
            <div class="ast-card-header">
                <h4 class="ast-card-title"><i class="fas fa-map-marker-alt text-success"></i> Status & Location</h4>
            </div>
            <div class="ast-card-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="in_store" {{ old('status') == 'in_store' ? 'selected' : '' }}>In Store</option>
                            <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                            <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                            <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-control" required>
                            <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Good" {{ old('condition', 'Good') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                            <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Physical Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Computer Lab Room 204, Central Store" value="{{ old('location') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Supplier Name</label>
                        <input type="text" name="supplier_name" class="form-control" placeholder="e.g. Dell India Direct, CompuWorld" value="{{ old('supplier_name') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Funding Source</label>
                        <select name="funding_source" class="form-control">
                            <option value="School Budget" {{ old('funding_source') == 'School Budget' ? 'selected' : '' }}>School Budget</option>
                            <option value="Trust" {{ old('funding_source') == 'Trust' ? 'selected' : '' }}>Trust</option>
                            <option value="Grant" {{ old('funding_source') == 'Grant' ? 'selected' : '' }}>Government Grant</option>
                            <option value="Donation" {{ old('funding_source') == 'Donation' ? 'selected' : '' }}>Donation / PTA</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Financial & Depreciation --}}
        <div class="ast-card mb-4">
            <div class="ast-card-header">
                <h4 class="ast-card-title"><i class="fas fa-file-invoice-dollar text-warning"></i> Financial & Depreciation</h4>
            </div>
            <div class="ast-card-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Purchase Cost (₹)</label>
                        <input type="number" step="0.01" name="purchase_cost" class="form-control" placeholder="0.00" value="{{ old('purchase_cost') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" placeholder="e.g. INV-2026-0091" value="{{ old('invoice_number') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Depreciation Method</label>
                        <select name="depreciation_method" class="form-control">
                            <option value="Straight Line" {{ old('depreciation_method') == 'Straight Line' ? 'selected' : '' }}>Straight Line (SLM)</option>
                            <option value="Written Down Value (WDV)" {{ old('depreciation_method') == 'Written Down Value (WDV)' ? 'selected' : '' }}>Written Down Value (WDV)</option>
                            <option value="Declining Balance" {{ old('depreciation_method') == 'Declining Balance' ? 'selected' : '' }}>Declining Balance</option>
                            <option value="None" {{ old('depreciation_method') == 'None' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-600">Depreciation Start Date</label>
                        <input type="date" name="depreciation_start_date" class="form-control" value="{{ old('depreciation_start_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="font-weight-600">Useful Life (mo)</label>
                        <input type="number" name="useful_life_months" class="form-control" placeholder="60" value="{{ old('useful_life_months', 60) }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="font-weight-600">Salvage Value (₹)</label>
                        <input type="number" step="0.01" name="salvage_value" class="form-control" placeholder="0.00" value="{{ old('salvage_value', 0) }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="font-weight-600">Rate (%/yr)</label>
                        <input type="number" step="0.01" name="depreciation_rate" class="form-control" placeholder="20.0" value="{{ old('depreciation_rate', 20.0) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Opening Book Value (for mid-life assets)</label>
                        <input type="number" step="0.01" name="opening_book_value" class="form-control" placeholder="Defaults to purchase cost" value="{{ old('opening_book_value') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Warranty & Insurance --}}
        <div class="ast-card mb-4">
            <div class="ast-card-header">
                <h4 class="ast-card-title"><i class="fas fa-shield-alt text-info"></i> Warranty & Insurance</h4>
            </div>
            <div class="ast-card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Warranty Provider</label>
                        <input type="text" name="warranty_provider" class="form-control" placeholder="e.g. Dell ProSupport, HP Care Pack" value="{{ old('warranty_provider') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-600">Warranty Expiry Date</label>
                        <input type="date" name="warranty_expiry_date" class="form-control" value="{{ old('warranty_expiry_date') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Insurance Provider</label>
                        <input type="text" name="insurance_provider" class="form-control" placeholder="e.g. HDFC ERGO, ICICI Lombard" value="{{ old('insurance_provider') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Policy Number</label>
                        <input type="text" name="insurance_policy_number" class="form-control" placeholder="e.g. POL-99214" value="{{ old('insurance_policy_number') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-600">Insurance Expiry Date</label>
                        <input type="date" name="insurance_expiry_date" class="form-control" value="{{ old('insurance_expiry_date') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 5: Notes --}}
        <div class="ast-card mb-4">
            <div class="ast-card-header">
                <h4 class="ast-card-title"><i class="fas fa-sticky-note text-secondary"></i> Additional Notes</h4>
            </div>
            <div class="ast-card-body">
                <div class="form-group mb-0">
                    <textarea name="notes" class="form-control" rows="3" placeholder="Enter any special remarks, warranty conditions or audit instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="d-flex justify-content-end align-items-center mb-5" style="gap: 10px;">
            <a href="{{ route('school.assets.assets') }}" class="btn btn-secondary px-4">Cancel</a>
            <button type="submit" class="btn btn-ast-primary px-4 py-2">
                <i class="fas fa-check mr-1"></i> Register Asset
            </button>
        </div>
    </form>
</div>
@endsection

