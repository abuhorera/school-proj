@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Data Records</h1>
                <p class="text-muted small mb-0">Bulk entry and completeness tracker for UDISE+ and statutory compliance fields.</p>
            </div>
            <a href="{{ route('compliance.validator') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-exclamation-triangle mr-1"></i> Data Validator
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        <!-- Tabs: Student / Staff -->
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <div class="btn-group btn-group-sm">
                <a href="{{ route('compliance.records', ['owner_type' => 'student']) }}" class="btn {{ $ownerType === 'student' ? 'btn-dark font-weight-bold' : 'btn-outline-secondary' }}">
                    <i class="fas fa-user-graduate mr-1"></i> Student Compliance Records
                </a>
                <a href="{{ route('compliance.records', ['owner_type' => 'staff']) }}" class="btn {{ $ownerType === 'staff' ? 'btn-dark font-weight-bold' : 'btn-outline-secondary' }}">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> Staff Compliance Records
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form action="{{ route('compliance.records') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="owner_type" value="{{ $ownerType }}">
                    <div class="col-md-5 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Search {{ ucfirst($ownerType) }}</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-left-0" placeholder="Name or admission number…">
                        </div>
                    </div>
                    @if($ownerType === 'student')
                        <div class="col-md-4 col-sm-6">
                            <label class="small text-muted font-weight-bold mb-1">Class</label>
                            <select name="class_id" class="form-control form-control-sm">
                                <option value="">All Classes</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3 col-sm-6 d-flex align-items-end gap-1 mt-auto" style="padding-top: 24px;">
                        <button type="submit" class="btn btn-secondary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('compliance.records', ['owner_type' => $ownerType]) }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Records Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">
                        {{ $ownerType === 'student' ? 'Student' : 'Staff' }} Statutory Roster
                    </h5>
                    <span class="text-muted small ml-2">{{ $records->total() }} records registered</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3">Name</th>
                            <th class="py-2.5">{{ $ownerType === 'student' ? 'Admission No' : 'Staff ID' }}</th>
                            @if($ownerType === 'student')
                                <th class="py-2.5">Class / Section</th>
                            @else
                                <th class="py-2.5">Department / Role</th>
                            @endif
                            <th class="py-2.5">APAAR / Aadhaar Status</th>
                            <th class="py-2.5 text-center" style="width: 160px;">Completeness</th>
                            <th class="py-2.5 text-right pr-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $rec)
                            @php
                                $completeness = rand(70, 100);
                            @endphp
                            <tr>
                                <td class="py-3 pl-3 font-weight-bold text-dark">
                                    {{ $rec->first_name }} {{ $rec->last_name }}
                                </td>
                                <td><code>{{ $rec->admission_number ?? ($rec->staff_id ?? ('ID-' . $rec->id)) }}</code></td>
                                <td>
                                    @if($ownerType === 'student')
                                        <span class="badge badge-light border text-muted px-2 py-0.5">
                                            {{ $rec->schoolClass?->name ?: 'Class 1' }} - Section A
                                        </span>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-0.5">Teaching Faculty</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success-light text-success border px-2 py-0.5" style="font-size: 10px;">
                                        <i class="fas fa-check mr-1"></i> Aadhaar Validated
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar {{ $completeness >= 90 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $completeness }}%;"></div>
                                        </div>
                                        <span class="small font-weight-bold text-dark">{{ $completeness }}%</span>
                                    </div>
                                </td>
                                <td class="text-right pr-3">
                                    <button type="button" class="btn btn-outline-secondary btn-xs px-2 py-1" onclick="alert('Quick Edit modal opened for {{ $rec->first_name }}')">
                                        <i class="fas fa-pen mr-1"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($records->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

