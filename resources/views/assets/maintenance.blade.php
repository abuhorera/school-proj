@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="d-flex justify-content-end mb-3" style="gap: 8px;">
        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#scheduleModal">
            <i class="fas fa-calendar-plus mr-1"></i> Add Schedule
        </button>
        <button type="button" class="btn btn-sm btn-ast-primary" data-toggle="modal" data-target="#logModal">
            <i class="fas fa-wrench mr-1"></i> Log Maintenance
        </button>
    </div>

    {{-- Section 1: Preventive Schedules --}}
    <div class="ast-card mb-4">
        <div class="ast-card-header">
            <h4 class="ast-card-title"><i class="fas fa-calendar-check text-primary"></i> Preventive Schedules</h4>
        </div>
        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Frequency</th>
                        <th>Next Due</th>
                        <th>Assigned</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $sch)
                        @php $isOverdue = $sch->next_due_date < \Carbon\Carbon::today(); @endphp
                        <tr>
                            <td>
                                @if($sch->asset)
                                    <a href="{{ route('school.assets.assets.show', $sch->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $sch->asset->name }}
                                    </a>
                                    <br><span class="ast-code">{{ $sch->asset->asset_tag }}</span>
                                @else
                                    <span class="text-muted">Asset #{{ $sch->asset_id }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $sch->title }}</strong>
                                @if($sch->notes)
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($sch->notes, 40) }}</div>
                                @endif
                            </td>
                            <td><span class="badge badge-light border">{{ $sch->maintenance_type }}</span></td>
                            <td>{{ $sch->frequency }}</td>
                            <td>
                                <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $sch->next_due_date->format('d M Y') }}
                                    @if($isOverdue) <span class="badge badge-danger">overdue</span> @endif
                                </span>
                            </td>
                            <td>{{ $sch->assigned_to ?? $sch->vendor_name ?? '—' }}</td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-xs btn-outline-warning btn-quick-log"
                                    data-asset-id="{{ $sch->asset_id }}"
                                    data-sched-id="{{ $sch->id }}"
                                    data-type="{{ $sch->maintenance_type }}"
                                    data-title="{{ $sch->title }}">
                                    Log
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-edit-sched"
                                    data-id="{{ $sch->id }}"
                                    data-title="{{ $sch->title }}"
                                    data-type="{{ $sch->maintenance_type }}"
                                    data-freq="{{ $sch->frequency }}"
                                    data-interval="{{ $sch->interval_days }}"
                                    data-due="{{ $sch->next_due_date->format('Y-m-d') }}"
                                    data-assigned="{{ $sch->assigned_to }}"
                                    data-vendor="{{ $sch->vendor_name }}"
                                    data-cost="{{ $sch->estimated_cost }}"
                                    data-notes="{{ $sch->notes }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('school.assets.maintenance.schedules.destroy', $sch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this preventive schedule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No preventive maintenance schedules set up.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Section 2: Work Logs --}}
    <div class="ast-card">
        <div class="ast-card-header">
            <h4 class="ast-card-title"><i class="fas fa-clipboard-list text-warning"></i> Work Logs</h4>
        </div>
        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Reported</th>
                        <th>Completed</th>
                        <th class="text-right">Cost</th>
                        <th>By / Vendor</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                @if($log->asset)
                                    <a href="{{ route('school.assets.assets.show', $log->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $log->asset->name }}
                                    </a>
                                    <br><span class="ast-code">{{ $log->asset->asset_tag }}</span>
                                @else
                                    <span class="text-muted">Asset #{{ $log->asset_id }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $log->maintenance_type }}</strong>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($log->problem_description, 40) }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $log->status === 'completed' ? 'badge-success' : ($log->status === 'in_progress' ? 'badge-primary' : 'badge-warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                </span>
                            </td>
                            <td>{{ $log->reported_date ? $log->reported_date->format('d M Y') : '—' }}</td>
                            <td>{{ $log->completed_date ? $log->completed_date->format('d M Y') : '—' }}</td>
                            <td class="text-right font-weight-bold">₹{{ number_format($log->cost, 2) }}</td>
                            <td>{{ $log->performed_by ?? $log->vendor_name ?? '—' }}</td>
                            <td class="text-right text-nowrap">
                                @if($log->status !== 'completed')
                                    <button type="button" class="btn btn-xs btn-outline-success btn-complete-log"
                                        data-id="{{ $log->id }}"
                                        data-asset="{{ $log->asset->name ?? '' }}"
                                        data-type="{{ $log->maintenance_type }}"
                                        data-cost="{{ $log->cost }}">
                                        Complete
                                    </button>
                                @else
                                    <span class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-check text-success"></i> Finished</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No maintenance work logs recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal 1: Add Schedule --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.maintenance.schedules.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Maintenance Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" class="form-control" required>
                            <option value="">-- Choose Asset --</option>
                            @foreach($assets as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->asset_tag }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Schedule Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Servicing — Epson Projector Servicing" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Maintenance Type <span class="text-danger">*</span></label>
                            <select name="maintenance_type" class="form-control" required>
                                <option value="Routine Service">Routine Service</option>
                                <option value="Inspection">Inspection</option>
                                <option value="Calibration">Calibration</option>
                                <option value="Deep Clean">Deep Clean</option>
                                <option value="Repair">Repair</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Frequency <span class="text-danger">*</span></label>
                            <select name="frequency" class="form-control" required>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Semi-Annually" selected>Semi-Annually</option>
                                <option value="Annually">Annually</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Interval (days)</label>
                            <input type="number" name="interval_days" class="form-control" value="90">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Next Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="next_due_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Assigned Staff</label>
                            <input type="text" name="assigned_to" class="form-control" placeholder="Technician name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">External Vendor</label>
                            <input type="text" name="vendor_name" class="form-control" placeholder="Vendor company name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Estimated Cost (₹)</label>
                        <input type="number" step="0.01" name="estimated_cost" class="form-control" placeholder="0.00" value="0">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Notes / Instructions</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Tasks to be performed..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ast-primary">Save Schedule</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal 2: Log Maintenance --}}
<div class="modal fade" id="logModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.maintenance.logs.store') }}">
            @csrf
            <input type="hidden" name="schedule_id" id="log_sched_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log Maintenance Work</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-600">Select Asset <span class="text-danger">*</span></label>
                        <select name="asset_id" id="log_asset_id" class="form-control" required>
                            <option value="">-- Choose Asset --</option>
                            @foreach($assets as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->asset_tag }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Maintenance Type <span class="text-danger">*</span></label>
                            <select name="maintenance_type" id="log_maintenance_type" class="form-control" required>
                                <option value="Routine Service">Routine Service</option>
                                <option value="Repair">Repair / Breakdown</option>
                                <option value="Inspection">Inspection</option>
                                <option value="Calibration">Calibration</option>
                                <option value="Deep Clean">Deep Clean</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="reported">Reported</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Problem Description / Scope <span class="text-danger">*</span></label>
                        <textarea name="problem_description" id="log_description" class="form-control" rows="2" required placeholder="Describe issue or required work..."></textarea>
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
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Performed By (Staff)</label>
                            <input type="text" name="performed_by" class="form-control" placeholder="Staff technician">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Vendor Provider</label>
                            <input type="text" name="vendor_name" class="form-control" placeholder="External company">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Work Log</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal 3: Complete Log --}}
<div class="modal fade" id="completeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="completeLogForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted" style="font-size: 0.9rem;">
                        Finishing work on: <strong id="complete_asset_name"></strong> (<span id="complete_type"></span>)
                    </p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Completed Date <span class="text-danger">*</span></label>
                            <input type="date" name="completed_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-600">Final Cost (₹)</label>
                            <input type="number" step="0.01" name="cost" id="complete_cost" class="form-control" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Action Taken / Resolution <span class="text-danger">*</span></label>
                        <textarea name="action_taken" class="form-control" rows="2" required placeholder="What was repaired, replaced or calibrated..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Next steps or recommendations..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark Completed</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-quick-log').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var assetId = this.getAttribute('data-asset-id');
                var schedId = this.getAttribute('data-sched-id');
                var type = this.getAttribute('data-type');
                var title = this.getAttribute('data-title');

                document.getElementById('log_asset_id').value = assetId;
                document.getElementById('log_sched_id').value = schedId;
                document.getElementById('log_maintenance_type').value = type;
                document.getElementById('log_description').value = title;
                $('#logModal').modal('show');
            });
        });

        document.querySelectorAll('.btn-complete-log').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var asset = this.getAttribute('data-asset');
                var type = this.getAttribute('data-type');
                var cost = this.getAttribute('data-cost');

                document.getElementById('complete_asset_name').innerText = asset;
                document.getElementById('complete_type').innerText = type;
                document.getElementById('complete_cost').value = cost || '0.00';
                document.getElementById('completeLogForm').action = '/school/assets/maintenance/logs/' + id + '/complete';
                $('#completeModal').modal('show');
            });
        });
    });
</script>
@endpush
@endsection

