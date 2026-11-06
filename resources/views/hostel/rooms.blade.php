@extends('layouts.app')

@section('title', 'Manage Hostel Rooms')

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

    <!-- Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="font-weight-bold text-slate-900 mb-0">Rooms Inventory Directory</h5>
            <small class="text-muted">Manage dorm rooms, bed capacities and types across all hostels</small>
        </div>
        <button type="button" class="btn-hst-primary" data-toggle="modal" data-target="#addRoomModal">
            <i class="fas fa-plus mr-1"></i> Add Room
        </button>
    </div>

    <!-- Rooms Table Card -->
    <div class="hst-card overflow-hidden">
        <div class="table-responsive">
            <table class="table tpl-table mb-0" id="rooms-table">
                <thead>
                    <tr>
                        <th class="tpl-td-num" style="width: 60px;">#</th>
                        <th>Hostel</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th class="text-center">Capacity</th>
                        <th class="text-center">Occupied</th>
                        <th class="text-right" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $index => $room)
                        @php
                            $occ = $room->occupiedCount();
                        @endphp
                        <tr>
                            <td class="tpl-td-num text-muted font-mono">{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-slate-800">{{ $room->hostel->name ?? 'N/A' }}</td>
                            <td class="font-mono font-bold">{{ $room->room_number }}</td>
                            <td>
                                <span class="tpl-chip is-indigo">
                                    {{ $room->roomType->name ?? 'Standard' }}
                                </span>
                            </td>
                            <td class="text-center font-weight-bold">{{ $room->capacity }}</td>
                            <td class="text-center">
                                <span class="tpl-chip {{ $occ >= $room->capacity ? 'is-danger' : ($occ > 0 ? 'is-amber' : 'is-emerald') }}">
                                    {{ $occ }} / {{ $room->capacity }}
                                </span>
                            </td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-xs btn-outline-primary mr-1 edit-room-btn" 
                                        data-id="{{ $room->id }}"
                                        data-hostel-id="{{ $room->hostel_id }}"
                                        data-room-type-id="{{ $room->room_type_id }}"
                                        data-room-number="{{ $room->room_number }}"
                                        data-capacity="{{ $room->capacity }}"
                                        data-toggle="modal" data-target="#editRoomModal"
                                        title="Edit room">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form action="{{ route('school.hostel.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room and its allocations?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete room">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-door-open fa-2x mb-2 text-slate-300 d-block"></i>
                                No rooms added yet. Click 'Add Room' to configure your first room.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add Room -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title font-weight-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-amber-500"></i>
                        Add New Room
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('school.hostel.rooms.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Hostel <span class="text-danger">*</span>
                            </label>
                            <select name="hostel_id" class="form-control" required>
                                <option value="">-- Select Hostel --</option>
                                @foreach($hostels as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }} ({{ ucfirst($h->type) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Room Type <span class="text-danger">*</span>
                            </label>
                            <select name="room_type_id" class="form-control" required>
                                <option value="">-- Select Room Type --</option>
                                @foreach($roomTypes as $rt)
                                    <option value="{{ $rt->id }}">{{ $rt->name }} (₹{{ number_format($rt->fare, 2) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group mb-0">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Room Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="room_number" class="form-control font-mono font-bold" placeholder="e.g. 101, 202" required>
                            </div>
                            <div class="col-6 form-group mb-0">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Bed Capacity <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="capacity" min="1" value="4" class="form-control font-mono" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-slate-50">
                        <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-hst-primary">Save Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Room -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title font-weight-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-edit text-amber-500"></i>
                        Edit Room
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editRoomForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Hostel <span class="text-danger">*</span>
                            </label>
                            <select name="hostel_id" id="edit_hostel_id" class="form-control" required>
                                @foreach($hostels as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }} ({{ ucfirst($h->type) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Room Type <span class="text-danger">*</span>
                            </label>
                            <select name="room_type_id" id="edit_room_type_id" class="form-control" required>
                                @foreach($roomTypes as $rt)
                                    <option value="{{ $rt->id }}">{{ $rt->name }} (₹{{ number_format($rt->fare, 2) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group mb-0">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Room Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="room_number" id="edit_room_number" class="form-control font-mono font-bold" required>
                            </div>
                            <div class="col-6 form-group mb-0">
                                <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                    Bed Capacity <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="capacity" id="edit_capacity" min="1" class="form-control font-mono" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-slate-50">
                        <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-hst-primary">Update Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.edit-room-btn').on('click', function() {
            var id = $(this).data('id');
            var hostelId = $(this).data('hostel-id');
            var roomTypeId = $(this).data('room-type-id');
            var roomNumber = $(this).data('room-number');
            var capacity = $(this).data('capacity');

            $('#editRoomForm').attr('action', '/school/hostel/rooms/' + id);
            $('#edit_hostel_id').val(hostelId);
            $('#edit_room_type_id').val(roomTypeId);
            $('#edit_room_number').val(roomNumber);
            $('#edit_capacity').val(capacity);
        });
    });
</script>
@endsection

