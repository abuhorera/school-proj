@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    {{-- Audit Header Banner --}}
    <div class="ast-card mb-4">
        <div class="ast-card-body d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
            <div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <h3 class="mb-0 font-weight-700" style="color: #0f172a; font-size: 1.4rem;">{{ $audit->title }}</h3>
                    <span class="badge {{ $audit->status === 'Completed' ? 'badge-secondary' : 'badge-success' }}">
                        {{ $audit->status }}
                    </span>
                </div>
                <div class="text-muted mt-1" style="font-size: 0.88rem;">
                    Scope: <strong>{{ $audit->scope }}</strong> · Conducted by: <strong>{{ $audit->conducted_by }}</strong> · Started: <strong>{{ $audit->started_at ? $audit->started_at->format('d M Y H:i') : '—' }}</strong>
                    @if($audit->completed_at) · Completed: <strong>{{ $audit->completed_at->format('d M Y H:i') }}</strong> @endif
                </div>
            </div>

            @if($audit->status === 'Open')
                <form method="POST" action="{{ route('school.assets.audits.complete', $audit->id) }}" onsubmit="return confirm('Complete this physical verification session?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Complete Session
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Summary Counters Row --}}
    <div class="row mb-4">
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #0284c7;">
                <div class="ast-kpi-val" style="font-size: 1.3rem;">{{ $audit->total_assets }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Total Assets</div>
            </div>
        </div>
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #6366f1;">
                <div class="ast-kpi-val text-primary" style="font-size: 1.3rem;" id="cnt_verified">{{ $audit->verified_count }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Verified</div>
            </div>
        </div>
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #10b981;">
                <div class="ast-kpi-val text-success" style="font-size: 1.3rem;" id="cnt_present">{{ $audit->present_count }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Present</div>
            </div>
        </div>
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #ef4444;">
                <div class="ast-kpi-val text-danger" style="font-size: 1.3rem;" id="cnt_missing">{{ $audit->missing_count }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Missing</div>
            </div>
        </div>
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #f59e0b;">
                <div class="ast-kpi-val text-warning" style="font-size: 1.3rem;" id="cnt_damaged">{{ $audit->damaged_count }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Damaged</div>
            </div>
        </div>
        <div class="col">
            <div class="ast-kpi-card text-center py-2" style="border-top: 3px solid #8b5cf6;">
                <div class="ast-kpi-val text-purple" style="font-size: 1.3rem;" id="cnt_moved">{{ $audit->moved_count }}</div>
                <div class="ast-kpi-lbl" style="font-size: 0.72rem;">Moved</div>
            </div>
        </div>
    </div>

    {{-- Verification Items Sweep Table --}}
    <div class="ast-card">
        <div class="ast-card-header">
            <h4 class="ast-card-title"><i class="fas fa-list-check text-primary"></i> Verification Items</h4>
        </div>
        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Tag</th>
                        <th>Asset</th>
                        <th>Expected Location</th>
                        <th>Expected Holder</th>
                        <th>Result</th>
                        <th>Found Location</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audit->items as $item)
                        <tr id="row_item_{{ $item->id }}">
                            <td>
                                <span class="ast-code">{{ $item->asset->asset_tag ?? '—' }}</span>
                            </td>
                            <td>
                                @if($item->asset)
                                    <a href="{{ route('school.assets.assets.show', $item->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $item->asset->name }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $item->asset->category->name ?? '' }}</div>
                                @else
                                    <span class="text-muted">Asset #{{ $item->asset_id }}</span>
                                @endif
                            </td>
                            <td>{{ $item->expected_location ?? '—' }}</td>
                            <td>{{ $item->expected_holder ?? '—' }}</td>
                            <td style="width: 170px;">
                                @if($audit->status === 'Open')
                                    <select class="form-control form-control-sm select-result" data-item-id="{{ $item->id }}">
                                        <option value="Pending" {{ $item->result === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Present" {{ $item->result === 'Present' ? 'selected' : '' }}>Present</option>
                                        <option value="Missing" {{ $item->result === 'Missing' ? 'selected' : '' }}>Missing</option>
                                        <option value="Damaged" {{ $item->result === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                                        <option value="Moved" {{ $item->result === 'Moved' ? 'selected' : '' }}>Moved</option>
                                    </select>
                                @else
                                    @php
                                        $badgeClass = match($item->result) {
                                            'Present' => 'badge-success',
                                            'Missing' => 'badge-danger',
                                            'Damaged' => 'badge-warning',
                                            'Moved' => 'badge-info',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $item->result }}</span>
                                @endif
                            </td>
                            <td>
                                @if($audit->status === 'Open')
                                    <input type="text" class="form-control form-control-sm input-found-loc" data-item-id="{{ $item->id }}" value="{{ $item->found_location }}" placeholder="Room / Desk">
                                @else
                                    {{ $item->found_location ?? '—' }}
                                @endif
                            </td>
                            <td>
                                @if($audit->status === 'Open')
                                    <input type="text" class="form-control form-control-sm input-remark" data-item-id="{{ $item->id }}" value="{{ $item->remarks }}" placeholder="Remark / observation">
                                @else
                                    {{ $item->remarks ?? '—' }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No assets matched the scope of this audit session.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var auditId = {{ $audit->id }};
        var csrfToken = '{{ csrf_token() }}';

        function updateItem(itemId) {
            var row = document.getElementById('row_item_' + itemId);
            if (!row) return;

            var result = row.querySelector('.select-result').value;
            var foundLocation = row.querySelector('.input-found-loc').value;
            var remarks = row.querySelector('.input-remark').value;

            fetch('/school/assets/audits/' + auditId + '/items/' + itemId + '/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    result: result,
                    found_location: foundLocation,
                    remarks: remarks
                })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.status === 'success' && data.audit) {
                    document.getElementById('cnt_verified').innerText = data.audit.verified_count;
                    document.getElementById('cnt_present').innerText = data.audit.present_count;
                    document.getElementById('cnt_missing').innerText = data.audit.missing_count;
                    document.getElementById('cnt_damaged').innerText = data.audit.damaged_count;
                    document.getElementById('cnt_moved').innerText = data.audit.moved_count;
                }
            })
            .catch(function(err) {
                console.error('Error saving verification:', err);
            });
        }

        document.querySelectorAll('.select-result').forEach(function(el) {
            el.addEventListener('change', function() {
                updateItem(this.getAttribute('data-item-id'));
            });
        });

        document.querySelectorAll('.input-found-loc, .input-remark').forEach(function(el) {
            el.addEventListener('blur', function() {
                updateItem(this.getAttribute('data-item-id'));
            });
        });
    });
</script>
@endpush
@endsection

