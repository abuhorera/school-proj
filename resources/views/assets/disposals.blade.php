@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <h4 class="ast-card-title"><i class="fas fa-trash-alt text-danger"></i> Disposal Requests</h4>
            <button type="button" class="btn btn-sm btn-ast-primary" data-toggle="modal" data-target="#disposeModal">
                <i class="fas fa-plus mr-1"></i> Request Disposal
            </button>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th class="text-right">Realised</th>
                        <th class="text-right">Book Value</th>
                        <th class="text-right">Gain/Loss</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Recipient / Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposals as $dp)
                        <tr>
                            <td>
                                @if($dp->asset)
                                    <a href="{{ route('school.assets.assets.show', $dp->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $dp->asset->name }}
                                    </a>
                                    <br><span class="ast-code">{{ $dp->asset->asset_tag }}</span>
                                @else
                                    <span class="text-muted">Asset #{{ $dp->asset_id }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-light border">{{ $dp->disposal_method }}</span></td>
                            <td>{{ $dp->disposal_date ? $dp->disposal_date->format('d M Y') : '—' }}</td>
                            <td class="text-right">
                                @if($dp->realised_value > 0)
                                    ₹{{ number_format($dp->realised_value, 2) }}
                                @else — @endif
                            </td>
                            <td class="text-right">₹{{ number_format($dp->book_value_at_disposal, 2) }}</td>
                            <td class="text-right font-weight-bold {{ $dp->gain_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($dp->gain_loss, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $dp->status === 'Completed' ? 'badge-secondary' : 'badge-warning' }}">
                                    {{ $dp->status }}
                                </span>
                            </td>
                            <td>{{ $dp->requested_by }}</td>
                            <td>
                                @if($dp->recipient)
                                    <strong>{{ $dp->recipient }}</strong><br>
                                @endif
                                <small class="text-muted">{{ Str::limit($dp->reason, 40) }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No asset disposal records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($disposals->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $disposals->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal: Request Disposal --}}
<div class="modal fade" id="disposeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.disposals.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Asset Disposal</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-control" required>
                            <option value="">-- Choose Asset to Dispose --</option>
                            @foreach($assets as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->asset_tag }}) - Book Value: ₹{{ number_format($a->current_book_value, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Disposal Method <span class="text-danger">*</span></label>
                            <select name="disposal_method" class="form-control" required>
                                <option value="Sold">Sold</option>
                                <option value="Scrapped">Scrapped</option>
                                <option value="Donated">Donated</option>
                                <option value="Written Off">Written Off</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Disposal Date <span class="text-danger">*</span></label>
                            <input type="date" name="disposal_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Realised Value (₹)</label>
                            <input type="number" step="0.01" name="realised_value" class="form-control" placeholder="0.00" value="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Recipient / Buyer</label>
                            <input type="text" name="recipient" class="form-control" placeholder="e.g. Apex Recyclers">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Reason / Justification <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="2" required placeholder="Specify why the asset is being disposed..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks / Approval Note</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Approval reference, committee resolution..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

