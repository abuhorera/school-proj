@extends('layouts.app')

@section('title', 'Add New Hostel')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('hostel.nav')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="hst-card shadow-sm border-0">
                <div class="hst-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-building text-amber-500"></i>
                        Register New Hostel Block
                    </span>
                    <a href="{{ route('school.hostel.hostels') }}" class="btn btn-xs btn-outline-secondary font-weight-bold">
                        &larr; Back to Hostels
                    </a>
                </div>

                <div class="p-4">
                    <form action="{{ route('school.hostel.hostels.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Hostel Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. ABC Hostel, Tagore Hall, Gargi Bhawan" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Hostel Type <span class="text-danger">*</span>
                                </label>
                                <select name="type" class="form-control" required>
                                    <option value="Boys" selected>Boys</option>
                                    <option value="Girls">Girls</option>
                                    <option value="Mixed">Mixed</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Campus Location / Address
                                </label>
                                <input type="text" name="address" class="form-control" placeholder="e.g. West Campus, Gate No. 3, Near Sports Ground">
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Notes & Information
                                </label>
                                <textarea name="note" rows="3" class="form-control" placeholder="Warden contacts, rules, emergency numbers, etc."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('school.hostel.hostels') }}" class="btn btn-secondary font-weight-bold px-4">Cancel</a>
                            <button type="submit" class="btn-hst-primary px-4">
                                <i class="fas fa-save mr-1.5"></i> Save Hostel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

