@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Data Validator</h1>
                <p class="text-muted small mb-0">Catches missing/duplicate APAAR, invalid Aadhaar checksum, implausible DOB, and category gaps before statutory filing.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('compliance.validator.export') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-csv mr-1 text-danger"></i> Export worklist
                </a>
                <form action="{{ route('compliance.validator.run') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="owner_type" value="{{ $ownerType }}">
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                        <i class="fas fa-play mr-1"></i> Run validation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- 3 KPI Status Counters -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Open Errors</div>
                    <div class="h2 font-weight-bold text-danger my-1">{{ $openErrors }}</div>
                    <div class="small text-muted">Mandatory statutory blockers</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Open Warnings</div>
                    <div class="h2 font-weight-bold text-warning my-1">{{ $openWarnings }}</div>
                    <div class="small text-muted">Recommended fields missing</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Accepted Exceptions</div>
                    <div class="h2 font-weight-bold text-secondary my-1">{{ $waivedCount }}</div>
                    <div class="small text-muted">Temporarily waived items</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form action="{{ route('compliance.validator') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="owner_type" value="{{ $ownerType }}">
                    <div class="col-md-5 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Search Finding</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-left-0" placeholder="Student name or issue keyword…">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Rule / Check</label>
                        <select name="check_key" class="form-control form-control-sm">
                            <option value="all">All checks</option>
                            <option value="aadhaar_checksum" {{ request('check_key') == 'aadhaar_checksum' ? 'selected' : '' }}>Invalid Aadhaar checksum</option>
                            <option value="apaar_missing" {{ request('check_key') == 'apaar_missing' ? 'selected' : '' }}>Missing / invalid APAAR ID</option>
                            <option value="social_category_missing" {{ request('check_key') == 'social_category_missing' ? 'selected' : '' }}>Missing social category</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex align-items-end gap-1 mt-auto" style="padding-top: 24px;">
                        <button type="submit" class="btn btn-secondary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('compliance.validator') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Findings Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Data Quality Findings</h5>
                    <span class="text-muted small ml-2">{{ $findings->total() }} findings recorded</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3">Owner</th>
                            <th class="py-2.5 text-center">Severity</th>
                            <th class="py-2.5">Check / Rule</th>
                            <th class="py-2.5">Issue Description</th>
                            <th class="py-2.5 text-right pr-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($findings as $f)
                            <tr>
                                <td class="py-3 pl-3">
                                    <div class="font-weight-bold text-dark">
                                        @if($f->owner_type === 'student')
                                            {{ $f->student?->first_name ?: 'Student' }} {{ $f->student?->last_name }}
                                        @else
                                            {{ $f->staff?->first_name ?: 'Staff' }} {{ $f->staff?->last_name }}
                                        @endif
                                    </div>
                                    <span class="badge badge-light border text-muted" style="font-size: 10px;">{{ ucfirst($f->owner_type) }} #{{ $f->owner_id }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $f->severity_badge }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                                        {{ $f->severity }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $f->check_name }}</div>
                                    <code>{{ $f->check_key }}</code>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $f->issue_description }}</div>
                                    @if($f->status === 'waived')
                                        <div class="text-muted small mt-1 italic"><i class="fas fa-info-circle mr-1 text-info"></i> Waived: {{ $f->waive_reason }}</div>
                                    @endif
                                </td>
                                <td class="text-right pr-3">
                                    @if($f->status !== 'waived')
                                        <button type="button" class="btn btn-outline-secondary btn-xs px-2.5 py-1" data-toggle="modal" data-target="#waiveModal{{ $f->id }}">
                                            Waive
                                        </button>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-1">Waived</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Waive Modal -->
                            <div class="modal fade" id="waiveModal{{ $f->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="{{ route('compliance.validator.waive') }}" method="POST" class="modal-content border-0 shadow">
                                        @csrf
                                        <input type="hidden" name="result_id" value="{{ $f->id }}">
                                        <div class="modal-header py-3 bg-light border-bottom">
                                            <h5 class="modal-title font-weight-bold text-dark" style="font-size: 15px;">Accept Administrative Exception</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold text-dark">Reason for Exemption</label>
                                                <input type="text" name="reason" class="form-control form-control-sm" placeholder="e.g. Pupil newly admitted, Aadhaar card under enrollment" required>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="small font-weight-bold text-dark">Exemption Valid Until</label>
                                                <input type="date" name="expires_on" class="form-control form-control-sm" value="{{ now()->addMonths(6)->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer py-2 bg-light border-top">
                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold">Accept Waive</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No validation findings. Data is clean and ready for statutory filing!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($findings->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $findings->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

