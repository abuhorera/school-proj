@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Compliance Checklist</h1>
                <p class="text-muted small mb-0">Every statutory document your installed compliance pack expects, matched against vault records.</p>
            </div>
            <a href="{{ route('compliance.documents') }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-archive mr-1"></i> Document Vault
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        <!-- Readiness Progress Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #f8fafc;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-white rounded-circle shadow-sm text-primary text-center" style="width: 54px; height: 54px;">
                        <i class="fas fa-clipboard-check fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">School Document Readiness</h5>
                        <p class="text-muted small mb-0">
                            <strong>{{ $onFileCount }} on file</strong> · 
                            <span class="text-danger font-weight-bold">{{ count($expectedDocs) - $onFileCount }} missing</span> 
                            (of {{ count($expectedDocs) }} statutory requirements)
                        </p>
                    </div>
                </div>
                <div class="text-right" style="min-width: 180px;">
                    <div class="h2 font-weight-bold text-dark mb-1">{{ $readinessPct }}%</div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $readinessPct }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Statutory Documents Checklist Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title font-weight-bold m-0 text-dark">Required Statutory Documents</h5>
                <span class="badge badge-light border text-muted px-2 py-1">CBSE & UDISE+ Standard</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3">Statutory Requirement</th>
                            <th class="py-2.5">Issuing Authority</th>
                            <th class="py-2.5 text-center">Status</th>
                            <th class="py-2.5">Expiry Date</th>
                            <th class="py-2.5 text-right pr-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklistItems as $item)
                            <tr>
                                <td class="py-3 pl-3">
                                    <div class="font-weight-bold text-dark">{{ $item['document'] }}</div>
                                </td>
                                <td class="text-muted small">{{ $item['authority'] }}</td>
                                <td class="text-center">
                                    @if($item['status'] === 'On file')
                                        <span class="badge badge-success px-2 py-1 text-uppercase" style="font-size: 10px;">
                                            <i class="fas fa-check mr-1"></i> On File
                                        </span>
                                    @elseif($item['status'] === 'Expiring')
                                        <span class="badge badge-warning text-dark px-2 py-1 text-uppercase" style="font-size: 10px;">
                                            <i class="fas fa-clock mr-1"></i> Expiring Soon
                                        </span>
                                    @elseif($item['status'] === 'Expired')
                                        <span class="badge badge-danger px-2 py-1 text-uppercase" style="font-size: 10px;">
                                            <i class="fas fa-times mr-1"></i> Expired
                                        </span>
                                    @else
                                        <span class="badge badge-danger-light text-danger border border-danger px-2 py-1 text-uppercase" style="font-size: 10px;">
                                            Missing
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted font-weight-bold">{{ $item['expiry'] }}</td>
                                <td class="text-right pr-3">
                                    @if($item['status'] !== 'Missing')
                                        <a href="{{ route('compliance.documents') }}" class="btn btn-outline-primary btn-xs px-2.5 py-1">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    @else
                                        <a href="{{ route('compliance.documents') }}" class="btn btn-outline-danger btn-xs px-2.5 py-1">
                                            <i class="fas fa-upload mr-1"></i> Add Document
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

