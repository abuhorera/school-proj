@extends('layouts.app')

@section('title', 'Student Allocation')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('hostel.nav')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-lg shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="hst-card p-3 mb-4">
        <form action="{{ route('school.hostel.allocations') }}" method="GET" class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <select name="hostel_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- All Hostels --</option>
                    @foreach($allHostels as $h)
                        <option value="{{ $h->id }}" {{ request('hostel_id') == $h->id ? 'selected' : '' }}>
                            {{ $h->name }} ({{ ucfirst($h->type) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                @if(request('hostel_id'))
                    <a href="{{ route('school.hostel.allocations') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Clear Filter
                    </a>
                @endif
            </div>
            <div class="col-md-4 text-md-right">
                <span class="text-xs text-muted font-weight-bold">
                    Showing {{ $hostels->count() }} Hostel(s)
                </span>
            </div>
        </form>
    </div>

    <!-- Hostels & Room Allocation Cards -->
    @forelse($hostels as $hostel)
        <div class="card shadow-sm border-0 mb-4 rounded-xl overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        <i class="fas fa-building"></i>
                    </span>
                    <h5 class="card-title font-weight-bold text-slate-900 mb-0">
                        {{ $hostel->name }} <span class="text-xs text-muted font-normal">({{ strtolower($hostel->type) }})</span>
                    </h5>
                </div>
                <div>
                    <span class="badge badge-light border text-xs px-2.5 py-1">
                        {{ $hostel->occupiedBeds() }} / {{ $hostel->totalCapacity() }} Beds Occupied
                    </span>
                </div>
            </div>

            <div class="card-body bg-slate-50/50 p-4">
                <div class="row">
                    @forelse($hostel->rooms as $room)
                        @php
                            $occ = $room->occupiedCount();
                            $isFull = $room->isFull();
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border shadow-xs rounded-xl overflow-hidden {{ $isFull ? 'border-danger-subtle' : '' }}">
                                <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                                    <h6 class="card-title font-weight-bold mb-0 text-slate-800">
                                        Room: {{ $room->room_number }}
                                    </h6>
                                    <span class="badge {{ strtolower($room->roomType->name ?? '') == 'ac' ? 'badge-info' : 'badge-secondary' }}">
                                        {{ $room->roomType->name ?? 'Standard' }}
                                    </span>
                                </div>

                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-xs text-muted font-weight-bold">Occupancy:</span>
                                        <span class="badge {{ $isFull ? 'badge-danger' : ($occ > 0 ? 'badge-warning text-dark' : 'badge-success') }}">
                                            {{ $occ }} / {{ $room->capacity }}
                                        </span>
                                    </div>

                                    <ul class="list-group list-group-flush border rounded-lg overflow-hidden text-xs">
                                        @forelse($room->allocations as $alloc)
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-2.5 bg-white">
                                                <div>
                                                    <span class="font-weight-bold text-dark">
                                                        {{ $alloc->student->first_name ?? '' }} {{ $alloc->student->last_name ?? '' }}
                                                    </span>
                                                    <span class="text-muted d-block text-[10px]">
                                                        Adm: {{ $alloc->student->admission_no ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <form action="{{ route('school.hostel.allocations.destroy', $alloc->id) }}" method="POST" onsubmit="return confirm('Remove this student from the room?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger px-1.5 py-0.5 rounded font-bold" title="Unallocate student">
                                                        &times;
                                                    </button>
                                                </form>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-muted py-2 text-center bg-white text-xs">
                                                No students allocated.
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>

                                <div class="card-footer bg-white border-top p-2.5 text-center">
                                    @if($isFull)
                                        <button class="btn btn-xs btn-light btn-block text-muted font-weight-bold disabled" disabled>
                                            <i class="fas fa-ban mr-1"></i> Room Full
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-block font-weight-bold" data-toggle="modal" data-target="#allocateModal{{ $room->id }}">
                                            <i class="fas fa-plus mr-1"></i> Allocate Student
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Modal for this Room -->
                        <div class="modal fade" id="allocateModal{{ $room->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content rounded-xl border-0 shadow-lg">
                                    <div class="modal-header border-bottom py-3">
                                        <h5 class="modal-title font-weight-bold text-slate-900 flex items-center gap-2">
                                            <i class="fas fa-user-plus text-amber-500"></i>
                                            Allocate Student to Room {{ $room->room_number }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ route('school.hostel.allocations.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="hostel_room_id" value="{{ $room->id }}">
                                        <div class="modal-body p-4">
                                            <div class="p-3 bg-slate-50 border rounded-lg mb-3">
                                                <div class="text-xs text-muted">Selected Room</div>
                                                <div class="font-weight-bold text-slate-800 text-sm">
                                                    {{ $hostel->name }} &bull; Room {{ $room->room_number }} ({{ $room->roomType->name ?? 'Standard' }})
                                                </div>
                                                <div class="text-xs text-emerald-600 font-bold mt-1">
                                                    {{ $room->availableBeds() }} Bed(s) Available
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                                    Select Student <span class="text-danger">*</span>
                                                </label>
                                                <select name="student_id" class="form-control select2" required style="width: 100%;">
                                                    <option value="">-- Choose Student --</option>
                                                    @foreach($students as $st)
                                                        <option value="{{ $st->id }}">
                                                            {{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_no }}) - Class {{ $st->classroom->name ?? 'Standard' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted mt-1 d-block">Search by student name or admission number.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-2.5 px-4 bg-slate-50">
                                            <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-hst-primary">Confirm Allocation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-4 text-center text-muted">
                            No rooms configured in this hostel yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @empty
        <div class="hst-card p-5 text-center text-muted">
            <i class="fas fa-hotel fa-3x text-slate-300 mb-3"></i>
            <h5>No Hostels Found</h5>
            <p class="text-sm">Please register a hostel first under Manage Hostels.</p>
            <a href="{{ route('school.hostel.hostels.create') }}" class="btn-hst-primary">
                <i class="fas fa-plus mr-1"></i> Add New Hostel
            </a>
        </div>
    @endforelse
</div>
@endsection

