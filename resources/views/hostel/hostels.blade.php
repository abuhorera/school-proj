@extends('layouts.app')

@section('title', 'Manage Hostels')

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
            <h5 class="font-weight-bold text-slate-900 mb-0">Hostels & Residential Halls</h5>
            <small class="text-muted">Master campus buildings, boarding blocks and wardens directory</small>
        </div>
        <a href="{{ route('school.hostel.hostels.create') }}" class="btn-hst-primary">
            <i class="fas fa-plus mr-1"></i> Add New Hostel
        </a>
    </div>

    <!-- Hostels Table Card -->
    <div class="hst-card overflow-hidden">
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th class="tpl-td-num" style="width: 60px;">#</th>
                        <th>Hostel Name</th>
                        <th>Type</th>
                        <th class="text-center">Rooms</th>
                        <th>Address</th>
                        <th>Notes / Description</th>
                        <th class="text-right" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hostels as $index => $hostel)
                        @php
                            $typeLower = strtolower($hostel->type);
                            $chipClass = $typeLower == 'boys' ? 'is-blue' : ($typeLower == 'girls' ? 'is-indigo' : 'is-amber');
                        @endphp
                        <tr>
                            <td class="tpl-td-num text-muted font-mono">{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-slate-900">
                                <i class="fas fa-building text-slate-400 mr-1.5"></i>
                                {{ $hostel->name }}
                            </td>
                            <td>
                                <span class="tpl-chip {{ $chipClass }}">
                                    {{ ucfirst($hostel->type) }}
                                </span>
                            </td>
                            <td class="text-center font-bold">
                                <span class="badge badge-light border">
                                    {{ $hostel->rooms_count }} room(s)
                                </span>
                            </td>
                            <td class="text-slate-600 text-sm">
                                {{ $hostel->address ?: '—' }}
                            </td>
                            <td class="text-muted text-xs">
                                {{ $hostel->note ?: '—' }}
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('school.hostel.hostels.edit', $hostel->id) }}" class="btn btn-xs btn-outline-primary mr-1" title="Edit hostel">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('school.hostel.hostels.destroy', $hostel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this hostel? All associated rooms and allocations will also be deleted.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete hostel">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-building fa-2x mb-2 text-slate-300 d-block"></i>
                                No hostels registered yet. Click 'Add New Hostel' to create your first hostel block.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

