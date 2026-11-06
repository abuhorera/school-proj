@extends('layouts.app')

@section('title', 'Manage Room Types')

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
            <h5 class="font-weight-bold text-slate-900 mb-0">Room Types Master</h5>
            <small class="text-muted">Define accommodation tiers, features, and monthly room fares</small>
        </div>
        <button type="button" class="btn-hst-primary" data-toggle="modal" data-target="#addRoomTypeModal">
            <i class="fas fa-plus mr-1"></i> Add Room Type
        </button>
    </div>

    <!-- Room Types Table Card -->
    <div class="hst-card overflow-hidden">
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th class="tpl-td-num" style="width: 60px;">#</th>
                        <th>Room Type Name</th>
                        <th>Fare</th>
                        <th>Description</th>
                        <th class="text-center">Total Rooms</th>
                        <th class="text-right" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $index => $type)
                        <tr>
                            <td class="tpl-td-num text-muted font-mono">{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-slate-800">
                                <span class="tpl-chip is-indigo mr-1">
                                    {{ $type->name }}
                                </span>
                            </td>
                            <td class="font-mono font-bold text-slate-900">
                                ₹{{ number_format($type->fare, 2) }}
                            </td>
                            <td class="text-muted text-sm">
                                {{ $type->description ?: '—' }}
                            </td>
                            <td class="text-center font-bold">
                                <span class="badge badge-light border">
                                    {{ $type->rooms_count }} room(s)
                                </span>
                            </td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-xs btn-outline-primary mr-1 edit-type-btn"
                                        data-id="{{ $type->id }}"
                                        data-name="{{ $type->name }}"
                                        data-fare="{{ $type->fare }}"
                                        data-description="{{ $type->description }}"
                                        data-toggle="modal" data-target="#editRoomTypeModal"
                                        title="Edit room type">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form action="{{ route('school.hostel.room-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete room type">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-tags fa-2x mb-2 text-slate-300 d-block"></i>
                                No room types configured yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add Room Type -->
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title font-weight-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-amber-500"></i>
                        Add Room Type
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('school.hostel.room-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Room Type Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. AC, Non-AC, Deluxe Dormitory" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Monthly Fare (₹) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="fare" min="0" value="2000.00" class="form-control font-mono font-bold" required>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Description
                            </label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Room amenities, bed type, ventilation, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-slate-50">
                        <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-hst-primary">Save Room Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Room Type -->
    <div class="modal fade" id="editRoomTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-xl border-0 shadow-lg">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title font-weight-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-edit text-amber-500"></i>
                        Edit Room Type
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editRoomTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Room Type Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit_type_name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Monthly Fare (₹) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="fare" id="edit_type_fare" min="0" class="form-control font-mono font-bold" required>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold text-xs uppercase text-slate-600">
                                Description
                            </label>
                            <textarea name="description" id="edit_type_description" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2.5 px-4 bg-slate-50">
                        <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-hst-primary">Update Room Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.edit-type-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var fare = $(this).data('fare');
            var desc = $(this).data('description');

            $('#editRoomTypeForm').attr('action', '/school/hostel/room-types/' + id);
            $('#edit_type_name').val(name);
            $('#edit_type_fare').val(fare);
            $('#edit_type_description').val(desc);
        });
    });
</script>
@endsection

