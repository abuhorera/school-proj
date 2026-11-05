@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Field Settings</h1>
                <p class="text-muted small mb-0">Show, hide, require or rename compliance and core fields for your school.</p>
            </div>
            <a href="{{ route('compliance.overview') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Overview
            </a>
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

        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom">
                <ul class="nav nav-tabs card-header-tabs" id="fieldTabs">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold text-dark" data-toggle="tab" href="#student_fields">
                            <i class="fas fa-user-graduate mr-1.5 text-primary"></i> Student Fields
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold text-dark" data-toggle="tab" href="#staff_fields">
                            <i class="fas fa-chalkboard-teacher mr-1.5 text-info"></i> Staff Fields
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold text-dark" data-toggle="tab" href="#school_fields">
                            <i class="fas fa-school mr-1.5 text-rose-500"></i> School Fields
                        </a>
                    </li>
                </ul>
            </div>

            <form action="{{ route('compliance.fields.update') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Student Fields -->
                        <div class="tab-pane fade show active" id="student_fields">
                            <h6 class="font-weight-bold text-dark mb-3">Statutory UDISE+ & Identity Fields</h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th>Field Name</th>
                                            <th>UDISE+ Standard Key</th>
                                            <th class="text-center">Visible in Admission</th>
                                            <th class="text-center">Mandatory</th>
                                            <th class="text-center">Include in Export</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $stFields = [
                                                ['name' => 'APAAR ID (12 Digits)', 'key' => 'apaar_id', 'visible' => true, 'req' => true],
                                                ['name' => 'Permanent Education Number (PEN)', 'key' => 'pen_no', 'visible' => true, 'req' => true],
                                                ['name' => 'Aadhaar Number (Checksum Verified)', 'key' => 'aadhaar_no', 'visible' => true, 'req' => true],
                                                ['name' => 'Social Category (General / SC / ST / OBC)', 'key' => 'social_category', 'visible' => true, 'req' => true],
                                                ['name' => 'CWSN Disability Details', 'key' => 'cwsn_type', 'visible' => true, 'req' => false],
                                                ['name' => 'Minority Community Group', 'key' => 'minority_group', 'visible' => true, 'req' => false],
                                                ['name' => 'BPL / EWS Status', 'key' => 'bpl_status', 'visible' => true, 'req' => false],
                                                ['name' => 'Blood Group & Rh Factor', 'key' => 'blood_group', 'visible' => true, 'req' => false],
                                            ];
                                        @endphp
                                        @foreach($stFields as $f)
                                            <tr>
                                                <td class="font-weight-bold text-dark py-2.5">{{ $f['name'] }}</td>
                                                <td><code>{{ $f['key'] }}</code></td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="visible[{{ $f['key'] }}]" value="1" {{ $f['visible'] ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="required[{{ $f['key'] }}]" value="1" {{ $f['req'] ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="export[{{ $f['key'] }}]" value="1" checked>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Staff Fields -->
                        <div class="tab-pane fade" id="staff_fields">
                            <h6 class="font-weight-bold text-dark mb-3">Statutory Teacher & Employee Fields</h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th>Field Name</th>
                                            <th>UDISE+ Key</th>
                                            <th class="text-center">Visible in HR</th>
                                            <th class="text-center">Mandatory</th>
                                            <th class="text-center">Include in Export</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $staffFields = [
                                                ['name' => 'TET Qualified Certificate No.', 'key' => 'tet_qualified', 'visible' => true, 'req' => true],
                                                ['name' => 'Professional Degree (B.Ed / D.El.Ed / M.Ed)', 'key' => 'prof_qualification', 'visible' => true, 'req' => true],
                                                ['name' => 'Academic Qualification (Post Graduate / Graduate)', 'key' => 'academic_qualification', 'visible' => true, 'req' => true],
                                                ['name' => 'EPF Universal Account Number (UAN)', 'key' => 'epf_uan', 'visible' => true, 'req' => true],
                                                ['name' => 'ESI Insurance Number', 'key' => 'esi_no', 'visible' => true, 'req' => false],
                                            ];
                                        @endphp
                                        @foreach($staffFields as $f)
                                            <tr>
                                                <td class="font-weight-bold text-dark py-2.5">{{ $f['name'] }}</td>
                                                <td><code>{{ $f['key'] }}</code></td>
                                                <td class="text-center"><input type="checkbox" checked></td>
                                                <td class="text-center"><input type="checkbox" {{ $f['req'] ? 'checked' : '' }}></td>
                                                <td class="text-center"><input type="checkbox" checked></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- School Fields -->
                        <div class="tab-pane fade" id="school_fields">
                            <h6 class="font-weight-bold text-dark mb-3">Institutional Infrastructure & Safety Fields</h6>
                            <p class="text-muted small">Fields rendered on the <strong>School Profile</strong> for SARAS/OASIS affiliation and UDISE+ compliance.</p>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="sc_1" checked>
                                <label class="custom-control-label small" for="sc_1">Enforce Fire Safety NOC expiry tracking</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="sc_2" checked>
                                <label class="custom-control-label small" for="sc_2">Enforce Building Safety PWD Certificate validity</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="sc_3" checked>
                                <label class="custom-control-label small" for="sc_3">Require Potable Water Testing Report renewal annually</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end pt-4 border-top mt-4">
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Save Field Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

