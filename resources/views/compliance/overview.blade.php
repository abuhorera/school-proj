@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Compliance & Governance</h1>
                <p class="text-muted small mb-0">Inspection-readiness, statutory documents and government reporting — one place.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('compliance.packs') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-boxes mr-1"></i> Manage Compliance Packs
                </a>
                <a href="{{ route('compliance.documents') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-archive mr-1"></i> Document Vault
                </a>
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

        <!-- Top Active Standard Banner -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge badge-rose text-white font-weight-bold px-2.5 py-1 mb-2" style="background-color: #e11d48;">PRIMARY COMPLIANCE PACK</span>
                        <h3 class="font-weight-bold mb-2">{{ $primaryPack?->name ?: 'India — CBSE / UDISE+' }}</h3>
                        <p class="text-slate-300 small mb-0" style="color: #cbd5e1;">
                            {{ $primaryPack?->description ?: 'UDISE+, SARAS, OASIS, SQAAF, APAAR ID, Aadhaar, CWSN and CBSE statutory documents. The flagship pack for CBSE-affiliated schools.' }}
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <div class="d-inline-block text-left p-3 rounded" style="background: rgba(255, 255, 255, 0.1);">
                            <div class="small text-slate-300 font-weight-bold">Inspection Readiness</div>
                            <div class="h2 font-weight-bold mb-0 text-white">{{ $readinessPct }}%</div>
                            <div class="progress mt-1.5" style="height: 5px; width: 120px;">
                                <div class="progress-bar bg-success" style="width: {{ $readinessPct }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 KPI Metrics -->
        <div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Statutory Documents</div>
                    <div class="h3 font-weight-bold text-dark my-1">{{ $totalDocs }} on file</div>
                    <div class="small text-muted"><span class="text-success font-weight-bold">Verified</span> in vault</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Expiring / Expired</div>
                    <div class="h3 font-weight-bold my-1 {{ $expiredDocs > 0 ? 'text-danger' : ($expiringDocs > 0 ? 'text-warning' : 'text-success') }}">
                        {{ $expiringDocs + $expiredDocs }}
                    </div>
                    <div class="small text-muted">Requires renewal NOC</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Validation Errors</div>
                    <div class="h3 font-weight-bold text-danger my-1">{{ $openErrors }}</div>
                    <div class="small text-muted">Blocks clean UDISE+ export</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">UDISE+ Code</div>
                    <div class="h3 font-weight-bold text-primary my-1">{{ $profile->udise_code ?: 'Not Set' }}</div>
                    <div class="small text-muted">{{ $profile->board ?: 'CBSE' }} Affiliated</div>
                </div>
            </div>
        </div>

        <!-- "What to do next" Actionable Triage List -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">What to do next</h5>
                    <p class="text-muted small mb-0">{{ count($actionItems) }} open priority items require institutional attention</p>
                </div>
                <a href="{{ route('compliance.validator.run') }}" class="btn btn-outline-primary btn-xs px-2.5 py-1" onclick="event.preventDefault(); document.getElementById('run-val-form').submit();">
                    <i class="fas fa-sync-alt mr-1"></i> Re-check data
                </a>
                <form id="run-val-form" action="{{ route('compliance.validator.run') }}" method="POST" class="d-none">@csrf</form>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($actionItems as $item)
                        <div class="list-group-item p-3 d-flex align-items-start justify-content-between flex-wrap gap-2">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="font-weight-bold text-dark mb-0">{{ $item['title'] }}</h6>
                                    <span class="badge {{ $item['severity'] === 'Critical' ? 'badge-danger' : ($item['severity'] === 'High' ? 'badge-warning' : 'badge-info') }} px-2 py-0.5 ml-2" style="font-size: 10px;">
                                        {{ $item['severity'] }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">{{ $item['description'] }}</p>
                            </div>
                            <a href="{{ $item['link'] }}" class="btn btn-outline-secondary btn-sm px-3 mt-2 mt-sm-0 font-weight-bold">
                                {{ $item['link_text'] }} <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

