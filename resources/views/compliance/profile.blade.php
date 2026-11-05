@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">School Compliance Profile</h1>
                <p class="text-muted small mb-0">Institutional data for UDISE+, SARAS/OASIS and SQAAF compliance reporting.</p>
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

        <form action="{{ route('compliance.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Identity & Recognition -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title font-weight-bold m-0 text-dark">
                        <i class="fas fa-id-card-alt mr-2 text-primary"></i> 1. Identity & Statutory Recognition
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">UDISE+ Code (11 Digits)</label>
                            <input type="text" name="udise_code" value="{{ old('udise_code', $profile->udise_code) }}" class="form-control form-control-sm" placeholder="e.g. 07010200345">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Recognition Number</label>
                            <input type="text" name="recognition_no" value="{{ old('recognition_no', $profile->recognition_no) }}" class="form-control form-control-sm" placeholder="e.g. EDN/REC/2018/8921">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Recognition Order Date</label>
                            <input type="date" name="recognition_date" value="{{ old('recognition_date', $profile->recognition_date ? $profile->recognition_date->format('Y-m-d') : '') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Management Type</label>
                            <select name="management_type" class="form-control form-control-sm">
                                <option value="">— Select Management —</option>
                                <option value="Private Unaided" {{ $profile->management_type == 'Private Unaided' ? 'selected' : '' }}>Private Unaided</option>
                                <option value="Government" {{ $profile->management_type == 'Government' ? 'selected' : '' }}>Government</option>
                                <option value="Government Aided" {{ $profile->management_type == 'Government Aided' ? 'selected' : '' }}>Government Aided</option>
                                <option value="Central Government" {{ $profile->management_type == 'Central Government' ? 'selected' : '' }}>Central Government (KVS / NVS)</option>
                                <option value="Local Body" {{ $profile->management_type == 'Local Body' ? 'selected' : '' }}>Local Body</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">School Category</label>
                            <select name="school_category" class="form-control form-control-sm">
                                <option value="">— Select Category —</option>
                                <option value="Higher Secondary (I–XII)" {{ $profile->school_category == 'Higher Secondary (I–XII)' ? 'selected' : '' }}>Higher Secondary (I–XII)</option>
                                <option value="Secondary (I–X)" {{ $profile->school_category == 'Secondary (I–X)' ? 'selected' : '' }}>Secondary (I–X)</option>
                                <option value="Upper Primary (I–VIII)" {{ $profile->school_category == 'Upper Primary (I–VIII)' ? 'selected' : '' }}>Upper Primary (I–VIII)</option>
                                <option value="Primary (I–V)" {{ $profile->school_category == 'Primary (I–V)' ? 'selected' : '' }}>Primary (I–V)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Affiliation Board</label>
                            <select name="board" class="form-control form-control-sm">
                                <option value="">— Select Board —</option>
                                <option value="CBSE" {{ $profile->board == 'CBSE' ? 'selected' : '' }}>Central Board of Secondary Education (CBSE)</option>
                                <option value="ICSE" {{ $profile->board == 'ICSE' ? 'selected' : '' }}>Council for the Indian School Certificate (ICSE)</option>
                                <option value="State Board" {{ $profile->board == 'State Board' ? 'selected' : '' }}>State Education Board</option>
                                <option value="Cambridge" {{ $profile->board == 'Cambridge' ? 'selected' : '' }}>Cambridge International (IGCSE)</option>
                                <option value="IB" {{ $profile->board == 'IB' ? 'selected' : '' }}>International Baccalaureate (IB)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Minority Community Status</label>
                            <input type="text" name="minority_group" value="{{ old('minority_group', $profile->minority_group) }}" class="form-control form-control-sm" placeholder="e.g. None / Linguistic / Religious">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Year Established</label>
                            <input type="number" name="established_year" value="{{ old('established_year', $profile->established_year) }}" class="form-control form-control-sm" placeholder="e.g. 2012">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Affiliation & Infrastructure -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title font-weight-bold m-0 text-dark">
                        <i class="fas fa-building mr-2 text-info"></i> 2. Affiliation & Infrastructure Approvals
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Affiliation Number</label>
                            <input type="text" name="affiliation_no" value="{{ old('affiliation_no', $profile->affiliation_no) }}" class="form-control form-control-sm" placeholder="e.g. CBSE/AFF/2130982">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Affiliation Valid Upto</label>
                            <input type="date" name="valid_upto" value="{{ old('valid_upto', $profile->valid_upto ? $profile->valid_upto->format('Y-m-d') : '') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Playground Available?</label>
                            <select name="playground_available" class="form-control form-control-sm">
                                <option value="1" {{ $profile->playground_available ? 'selected' : '' }}>Yes, Campus Playground Available</option>
                                <option value="0" {{ !$profile->playground_available ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Total Campus Area (sq. meters)</label>
                            <input type="number" step="0.01" name="campus_area_sqm" value="{{ old('campus_area_sqm', $profile->campus_area_sqm) }}" class="form-control form-control-sm" placeholder="e.g. 12500">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Built-Up Area (sq. meters)</label>
                            <input type="number" step="0.01" name="built_up_area_sqm" value="{{ old('built_up_area_sqm', $profile->built_up_area_sqm) }}" class="form-control form-control-sm" placeholder="e.g. 6400">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Fire Safety NOC Valid Upto</label>
                            <input type="date" name="fire_safety_valid_upto" value="{{ old('fire_safety_valid_upto', $profile->fire_safety_valid_upto ? $profile->fire_safety_valid_upto->format('Y-m-d') : '') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Building Safety Valid Upto</label>
                            <input type="date" name="building_safety_valid_upto" value="{{ old('building_safety_valid_upto', $profile->building_safety_valid_upto ? $profile->building_safety_valid_upto->format('Y-m-d') : '') }}" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection

