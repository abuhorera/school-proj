@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Government Reports</h1>
                <p class="text-muted small mb-0">Standardized government and UDISE+ data exports with pre-flight verification.</p>
            </div>
            <a href="{{ route('compliance.validator') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-exclamation-triangle mr-1"></i> Data Validator
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        <div class="alert alert-light border p-3 mb-4 text-muted small" style="border-radius: 8px;">
            <i class="fas fa-info-circle mr-1 text-primary"></i> <strong>Official Format Notice:</strong> These exports match the official column layouts of UDISE+ and SDMIS for data reconciliation. Official government portals may update templates annually.
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <!-- 1. Student Master -->
            <div class="col mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge badge-primary px-2 py-1 text-uppercase" style="font-size: 10px;">SDMIS Format</span>
                                @if($openErrors > 0)
                                    <span class="badge badge-danger text-uppercase px-2 py-0.5" style="font-size: 10px;">{{ $openErrors }} Errors</span>
                                @else
                                    <span class="badge badge-success text-uppercase px-2 py-0.5" style="font-size: 10px;">Verified Clean</span>
                                @endif
                            </div>
                            <h5 class="font-weight-bold text-dark mb-2">UDISE+ Student Data</h5>
                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                Student master in official UDISE+ column order — APAAR ID, Aadhaar hash, PEN, Social Category, CWSN disability, and Minority status.
                            </p>
                        </div>
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                            <a href="{{ route('compliance.validator') }}" class="btn btn-outline-secondary btn-xs px-2 py-1">Fix Issues</a>
                            <a href="{{ route('compliance.reports.export', 'student') }}" class="btn btn-primary btn-sm px-3 font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Download CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Teacher Master -->
            <div class="col mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge badge-info px-2 py-1 text-uppercase" style="font-size: 10px;">Staff Roster</span>
                                <span class="badge badge-success text-uppercase px-2 py-0.5" style="font-size: 10px;">Clean</span>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-2">UDISE+ Teacher Data</h5>
                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                Teaching faculty block with Academic Qualifications, Professional Degree (B.Ed/D.El.Ed), TET Qualification status, and Social Categories.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-right">
                            <a href="{{ route('compliance.reports.export', 'teacher') }}" class="btn btn-primary btn-sm px-3 font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Download CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. School Profile Master -->
            <div class="col mb-3">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge badge-dark px-2 py-1 text-uppercase" style="font-size: 10px;">Institution</span>
                                <span class="badge badge-success text-uppercase px-2 py-0.5" style="font-size: 10px;">Complete</span>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-2">UDISE+ School Profile</h5>
                            <p class="text-muted small mb-3" style="line-height: 1.6;">
                                Institutional profile: UDISE Code ({{ $profile->udise_code ?: '07010200345' }}), Recognition No, Affiliation Board, Management Type, and infrastructure meters.
                            </p>
                        </div>
                        <div class="pt-3 border-top text-right">
                            <a href="{{ route('compliance.reports.export', 'school') }}" class="btn btn-primary btn-sm px-3 font-weight-bold">
                                <i class="fas fa-download mr-1"></i> Download CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

