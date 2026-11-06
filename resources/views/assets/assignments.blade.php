@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <h4 class="ast-card-title mr-3"><i class="fas fa-hand-holding text-primary"></i> Asset Allocations</h4>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('school.assets.assignments', ['status' => 'all']) }}" class="btn {{ $status == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                    <a href="{{ route('school.assets.assignments', ['status' => 'active']) }}" class="btn {{ $status == 'active' ? 'btn-primary' : 'btn-outline-secondary' }}">Active</a>
                    <a href="{{ route('school.assets.assignments', ['status' => 'returned']) }}" class="btn {{ $status == 'returned' ? 'btn-primary' : 'btn-outline-secondary' }}">Returned</a>
                    <a href="{{ route('school.assets.assignments', ['status' => 'transferred']) }}" class="btn {{ $status == 'transferred' ? 'btn-primary' : 'btn-outline-secondary' }}">Transferred</a>
                    <a href="{{ route('school.assets.assignments', ['status' => 'lost']) }}" class="btn {{ $status == 'lost' ? 'btn-primary' : 'btn-outline-secondary' }}">Lost</a>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-ast-primary" data-toggle="modal" data-target="#assignModal">
                <i class="fas fa-plus mr-1"></i> New Allocation
            </button>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Holder</th>
                        <th>Assigned</th>
                        <th>Expected Return</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $asg)
                        <tr>
                            <td>
                                @if($asg->asset)
                                    <a href="{{ route('school.assets.assets.show', $asg->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $asg->asset->name }}
                                    </a>
                                    <br><span class="ast-code">{{ $asg->asset->asset_tag }}</span>
                                @else
                                    <span class="text-muted">Deleted Asset #{{ $asg->asset_id }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $asg->holder_name }}</strong>
                                <br><small class="text-muted">{{ $asg->assignable_type }}</small>
                            </td>
                            <td>{{ $asg->assigned_date ? $asg->assigned_date->format('d M Y') : '—' }}</td>
                            <td>
                                @if($asg->expected_return_date)
                                    @php $overdue = $asg->status === 'active' && $asg->expected_return_date < \Carbon\Carbon::today(); @endphp
                                    <span class="{{ $overdue ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $asg->expected_return_date->format('d M Y') }}
                                        @if($overdue) <span class="badge badge-danger">overdue</span> @endif
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $asg->status === 'active' ? 'badge-success' : ($asg->status === 'returned' ? 'badge-secondary' : 'badge-info') }}">
                                    {{ ucfirst($asg->status) }}
                                </span>
                            </td>
                            <td class="text-right text-nowrap">
                                @if($asg->status === 'active')
                                    <button type="button" class="btn btn-xs btn-outline-warning btn-return-asg"
                                        data-id="{{ $asg->id }}"
                                        data-asset="{{ $asg->asset->name ?? '' }}"
                                        data-holder="{{ $asg->holder_name }}">
                                        Return
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-info btn-transfer-asg"
                                        data-id="{{ $asg->id }}"
                                        data-asset="{{ $asg->asset->name ?? '' }}"
                                        data-holder="{{ $asg->holder_name }}">
                                        Transfer
                                    </button>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        @if($asg->returned_date) Returned on {{ $asg->returned_date->format('d M Y') }} @else Closed @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                No asset allocations found for the selected tab.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal 1: New Allocation --}}
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.assignments.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Allocate Asset</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-control" required>
                            <option value="">-- Choose Asset --</option>
                            @foreach($availableAssets as $av)
                                <option value="{{ $av->id }}">{{ $av->name }} ({{ $av->asset_tag }}) - [{{ $av->status_label }}]</option>
                            @endforeach
                        </select>
                    </div>
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
                        <label class="font-weight-600">Holder Name / Beneficiary <span class="text-danger">*</span></label>
                        <input type="text" name="holder_name" class="form-control" placeholder="e.g. Ramesh Kumar, Class XII-A" required>
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
                        <label class="font-weight-600">Condition at Handover <span class="text-danger">*</span></label>
                        <select name="condition_on_assign" class="form-control" required>
                            <option value="New">New</option>
                            <option value="Good" selected>Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Allocation notes or location..."></textarea>
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

{{-- Modal 2: Return Asset --}}
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="returnForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Return Asset: <span id="return_asset_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size: 0.9rem;">
                        Returning custody from: <strong id="return_holder_name"></strong>
                    </p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Returned Date <span class="text-danger">*</span></label>
                            <input type="date" name="returned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Condition on Return <span class="text-danger">*</span></label>
                            <select name="condition_on_return" class="form-control" required>
                                <option value="New">New</option>
                                <option value="Good" selected>Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Checklist or return condition notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Confirm Return</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal 3: Transfer Custody --}}
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="transferForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Custody: <span id="transfer_asset_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size: 0.9rem;">
                        Transferring from current holder: <strong id="transfer_holder_name"></strong>
                    </p>
                    <div class="form-group">
                        <label class="font-weight-600">New Holder Type <span class="text-danger">*</span></label>
                        <select name="assignable_type" class="form-control" required>
                            <option value="Staff">Staff</option>
                            <option value="Student">Student</option>
                            <option value="Class / Room">Class / Room</option>
                            <option value="Department">Department</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">New Holder Name <span class="text-danger">*</span></label>
                        <input type="text" name="holder_name" class="form-control" placeholder="e.g. Priya Sharma, Room 104" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Transfer Date <span class="text-danger">*</span></label>
                            <input type="date" name="assigned_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Expected Return</label>
                            <input type="date" name="expected_return_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Condition on Transfer <span class="text-danger">*</span></label>
                        <select name="condition_on_assign" class="form-control" required>
                            <option value="New">New</option>
                            <option value="Good" selected>Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Transfer justification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Transfer Custody</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-return-asg').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var asset = this.getAttribute('data-asset');
                var holder = this.getAttribute('data-holder');

                document.getElementById('return_asset_name').innerText = asset;
                document.getElementById('return_holder_name').innerText = holder;
                document.getElementById('returnForm').action = '/school/assets/assignments/' + id + '/return';
                $('#returnModal').modal('show');
            });
        });

        document.querySelectorAll('.btn-transfer-asg').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var asset = this.getAttribute('data-asset');
                var holder = this.getAttribute('data-holder');

                document.getElementById('transfer_asset_name').innerText = asset;
                document.getElementById('transfer_holder_name').innerText = holder;
                document.getElementById('transferForm').action = '/school/assets/assignments/' + id + '/transfer';
                $('#transferModal').modal('show');
            });
        });
    });
</script>
@endpush
@endsection

