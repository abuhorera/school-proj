@extends('layouts.app')

@section('title', 'ID Cards')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-id-card text-primary mr-2"></i> ID Cards Center</h3>
            <p class="text-muted mb-0 small">Generate, customize, and bulk-print smart RFID &amp; QR barcode ID cards for students and faculty.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Bulk Print Sheet
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'students' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.id-cards', ['tab' => 'students']) }}">
                <i class="fas fa-user-graduate mr-1"></i> Student ID Cards
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'staff' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.id-cards', ['tab' => 'staff']) }}">
                <i class="fas fa-chalkboard-teacher mr-1"></i> Staff ID Cards
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'designs' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.id-cards', ['tab' => 'designs']) }}">
                <i class="fas fa-palette mr-1"></i> Card Designs &amp; Templates
            </a>
        </li>
    </ul>

    @if($tab === 'designs')
        {{-- Card Designs View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-palette text-primary mr-2"></i> Smart Card Printing Layout &amp; Theme</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded text-center bg-white shadow-sm">
                            <div class="p-3 rounded text-white mb-3" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                <h6 class="font-weight-bold mb-1">YUG INTERNATIONAL SCHOOL</h6>
                                <small class="text-light">Standard Student Card (Portrait)</small>
                            </div>
                            <p class="small text-muted mb-2">RFID 13.56 MHz + Code 128 Barcode + Student QR</p>
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> Default Active</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded text-center bg-white shadow-sm">
                            <div class="p-3 rounded text-white mb-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <h6 class="font-weight-bold mb-1">YUG INTERNATIONAL SCHOOL</h6>
                                <small class="text-light">Faculty &amp; Staff Smart Card</small>
                            </div>
                            <p class="small text-muted mb-2">Access Control Gate Entry + Department Header</p>
                            <span class="badge badge-info px-2 py-1">Staff Standard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tab === 'staff')
        {{-- Staff ID Cards View --}}
        <div class="row">
            @forelse($staff as $stf)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="p-3 text-white text-center" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <h6 class="font-weight-bold mb-0">YUG INTERNATIONAL SCHOOL</h6>
                            <small class="text-light" style="letter-spacing: 0.5px;">STAFF IDENTITY CARD</small>
                        </div>
                        <div class="card-body text-center p-3">
                            <div class="rounded-circle mx-auto mb-2 bg-light border d-flex align-items-center justify-content-center text-secondary" style="width: 75px; height: 75px;">
                                <i class="fas fa-user-tie fa-2x"></i>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-0">{{ $stf->name ?? ($stf->first_name.' '.$stf->last_name) }}</h6>
                            <p class="text-muted small mb-2">{{ $stf->designation ?? 'Faculty / Teacher' }}</p>
                            <div class="text-left small bg-light p-2 rounded mb-2">
                                <div><strong>Emp Code:</strong> EMP-{{ $stf->id }}</div>
                                <div><strong>Dept:</strong> {{ $stf->department ?? 'Academic Staff' }}</div>
                                <div><strong>Emergency:</strong> {{ $stf->phone ?? '+91 98765 00000' }}</div>
                            </div>
                            <div class="text-center font-monospace small bg-white border py-1 rounded">
                                ||| | |||| | ||| || |||
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4 text-muted">No staff found.</div>
            @endforelse
        </div>
    @else
        {{-- Student ID Cards View --}}
        <div class="row">
            @forelse($students as $st)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="p-3 text-white text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <h6 class="font-weight-bold mb-0">YUG INTERNATIONAL SCHOOL</h6>
                            <small class="text-warning font-weight-bold" style="letter-spacing: 0.5px;">STUDENT SMART CARD</small>
                        </div>
                        <div class="card-body text-center p-3">
                            <div class="rounded-circle mx-auto mb-2 bg-light border d-flex align-items-center justify-content-center text-primary" style="width: 75px; height: 75px;">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-0">{{ $st->first_name }} {{ $st->last_name }}</h6>
                            <p class="text-muted small mb-2">{{ $st->schoolClass->name ?? 'Class 10' }} &middot; Roll #{{ $st->roll_number ?? '12' }}</p>
                            <div class="text-left small bg-light p-2 rounded mb-2">
                                <div><strong>Adm No:</strong> {{ $st->admission_number ?? 'ADM-'.$st->id }}</div>
                                <div><strong>Father:</strong> {{ $st->father_name ?? 'Parent Name' }}</div>
                                <div><strong>Blood Group:</strong> <span class="badge badge-danger">B+</span></div>
                            </div>
                            <div class="text-center font-monospace small bg-white border py-1 rounded">
                                ||| | |||| | ||| || |||
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4 text-muted">No students found.</div>
            @endforelse
        </div>
    @endif

</div>
@endsection
