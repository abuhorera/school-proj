@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Document Vault</h1>
                <p class="text-muted small mb-0">Statutory certificates, NOCs and compliance documents with automated expiry tracking.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('compliance.checklist') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-clipboard-check mr-1"></i> Compliance Checklist
                </a>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#addDocumentModal">
                    <i class="fas fa-plus mr-1"></i> Add Document
                </button>
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
                    <div class="small text-muted font-weight-bold text-uppercase">Expired Documents</div>
                    <div class="h2 font-weight-bold text-danger my-1">{{ $expiredCount }}</div>
                    <div class="small text-muted">Statutory validity lapsed</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Expiring Soon (≤60 Days)</div>
                    <div class="h2 font-weight-bold text-warning my-1">{{ $expiringCount }}</div>
                    <div class="small text-muted">Renewal inspection required</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Valid & Verified</div>
                    <div class="h2 font-weight-bold text-success my-1">{{ $validCount }}</div>
                    <div class="small text-muted">Compliant and on record</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form action="{{ route('compliance.documents') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="owner_type" value="{{ $ownerType }}">
                    <div class="col-md-5 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Search Documents</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-left-0" placeholder="Title or document number…">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Category</label>
                        <select name="category" class="form-control form-control-sm">
                            <option value="All categories">All categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex align-items-end gap-1 mt-auto" style="padding-top: 24px;">
                        <button type="submit" class="btn btn-secondary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('compliance.documents') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">School Statutory Documents</h5>
                    <span class="text-muted small ml-2">{{ $documents->total() }} documents cataloged</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3">Title</th>
                            <th class="py-2.5">Category</th>
                            <th class="py-2.5">Doc No.</th>
                            <th class="py-2.5">Issue Date</th>
                            <th class="py-2.5">Expiry Date</th>
                            <th class="py-2.5 text-center">Status</th>
                            <th class="py-2.5 text-center">Verified</th>
                            <th class="py-2.5 text-right pr-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td class="py-3 pl-3">
                                    <div class="font-weight-bold text-dark">{{ $doc->title }}</div>
                                    @if($doc->notes)
                                        <div class="text-muted small text-truncate" style="max-width: 300px;">{{ $doc->notes }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-light border text-secondary px-2 py-1" style="font-size: 11px;">
                                        {{ $doc->category }}
                                    </span>
                                </td>
                                <td><code>{{ $doc->document_number ?: '—' }}</code></td>
                                <td class="text-muted small">{{ $doc->issue_date ? $doc->issue_date->format('d M Y') : '—' }}</td>
                                <td class="small font-weight-bold {{ $doc->computed_status === 'Expired' ? 'text-danger' : ($doc->computed_status === 'Expiring' ? 'text-warning' : 'text-dark') }}">
                                    {{ $doc->expiry_date ? $doc->expiry_date->format('d M Y') : 'Permanent' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $doc->status_badge }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                                        {{ $doc->computed_status }}
                                    </span>
                                </td>
                                <td class="text-center text-success">
                                    <i class="fas fa-check-circle" title="Verified"></i>
                                </td>
                                <td class="text-right pr-3">
                                    <div class="btn-group btn-group-xs">
                                        <button class="btn btn-outline-secondary btn-xs px-2 py-1" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('compliance.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Remove [{{ $doc->title }}] from vault?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No statutory documents found in vault.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($documents->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Modal: Add Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('compliance.documents.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header py-3 bg-light border-bottom">
                <h5 class="modal-title font-weight-bold text-dark" style="font-size: 16px;">
                    <i class="fas fa-file-upload mr-1 text-primary"></i> Upload Statutory Document
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="owner_type" value="school">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Document Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control form-control-sm" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Document Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Fire Safety Certificate 2026-29" required>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-dark">Certificate / Order Number</label>
                    <input type="text" name="document_number" class="form-control form-control-sm" placeholder="e.g. CFO/FS/2026/892">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="small font-weight-bold text-dark">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label class="small font-weight-bold text-dark">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="small font-weight-bold text-dark">Notes / Observations</label>
                    <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Details about issuing authority, inspection dates..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold">
                    <i class="fas fa-save mr-1"></i> Save to Vault
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

