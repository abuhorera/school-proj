@extends('layouts.app')

@section('title', 'Hostel Dashboard')

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

    <!-- Row 1: 4 Stat Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="hst-card hst-stat-card h-100 mb-0">
                <div class="hst-stat-icon primary">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <div class="hst-stat-label">Total Hostels</div>
                    <div class="hst-stat-value">{{ $totalHostels }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="hst-card hst-stat-card h-100 mb-0">
                <div class="hst-stat-icon secondary">
                    <i class="fas fa-door-open"></i>
                </div>
                <div>
                    <div class="hst-stat-label">Total Rooms</div>
                    <div class="hst-stat-value">{{ $totalRooms }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="hst-card hst-stat-card h-100 mb-0">
                <div class="hst-stat-icon danger">
                    <i class="fas fa-bed"></i>
                </div>
                <div>
                    <div class="hst-stat-label">Occupied Beds</div>
                    <div class="hst-stat-value text-rose-600">{{ $occupiedBeds }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="hst-card hst-stat-card h-100 mb-0">
                <div class="hst-stat-icon tertiary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="hst-stat-label">Available Beds</div>
                    <div class="hst-stat-value text-emerald-600">{{ $availableBeds }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Room Status Table & Sidebar -->
    <div class="row">
        <!-- Room Status Table -->
        <div class="col-lg-8 mb-4">
            <div class="hst-card h-100 mb-0 d-flex flex-column">
                <div class="hst-section-title">
                    <div>
                        Room Status
                        <span class="hst-section-subtitle">Live overview of all hostel accommodation inventory</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('school.hostel.rooms') }}" class="btn-hst-primary px-3 py-1.5 text-xs">
                            <i class="fas fa-plus mr-1"></i> Add Room
                        </a>
                    </div>
                </div>
                <div class="table-responsive flex-grow-1">
                    <table class="hst-table table-hover">
                        <thead>
                            <tr>
                                <th>Hostel Name</th>
                                <th>Room No.</th>
                                <th>Room Type</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $rm)
                                @php
                                    $occ = $rm->occupiedCount();
                                    $statusLabel = $rm->statusLabel();
                                    $statusClass = $rm->statusClass();
                                @endphp
                                <tr>
                                    <td class="font-weight-bold">{{ $rm->hostel->name ?? 'N/A' }}</td>
                                    <td class="font-mono font-bold">{{ $rm->room_number }}</td>
                                    <td>
                                        <span class="tpl-chip is-indigo">
                                            {{ $rm->roomType->name ?? 'Standard' }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $occ }} / {{ $rm->capacity }}
                                    </td>
                                    <td>
                                        <span class="hst-status-pill {{ $statusClass }}">
                                            <span class="dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('school.hostel.allocations') }}" class="btn btn-xs btn-outline-primary font-weight-bold">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No rooms configured yet. Click 'Add Room' to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Occupancy by Hostel & Recent Allocations -->
        <div class="col-lg-4 mb-4">
            <!-- Occupancy Chart Card -->
            <div class="hst-card mb-4">
                <div class="hst-section-title" style="border-bottom: none; padding-bottom: 0;">
                    Occupancy by Hostel
                </div>
                <div class="p-4">
                    @forelse($hostels as $h)
                        @php
                            $pct = $h->occupancyPercent();
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size: 14px;">
                                <span class="font-weight-bold">{{ $h->name }}</span>
                                <span class="font-mono text-muted">{{ $pct }}%</span>
                            </div>
                            <div class="hst-progress-bar">
                                <div class="hst-progress-fill gradient" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="text-xs text-muted mt-1">
                                {{ $h->occupiedBeds() }} occupied / {{ $h->totalCapacity() }} total beds
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-sm mb-0">No hostels added yet.</p>
                    @endforelse

                    <a href="{{ route('school.hostel.hostels') }}" class="btn btn-block btn-outline-primary mt-3 font-weight-bold" style="border-radius: 8px;">
                        View Detailed Report
                    </a>
                </div>
            </div>

            <!-- Recent Allocations -->
            <div class="hst-card mb-0">
                <div class="hst-section-title" style="border-bottom: none; padding-bottom: 0;">
                    Recent Allocations
                </div>
                <div class="p-4">
                    <div class="d-flex flex-column" style="gap: 16px;">
                        @forelse($recentAllocations as $alloc)
                            <div class="hst-allocation-item">
                                <div class="hst-allocation-avatar text-amber-600">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold" style="color: var(--hst-primary); font-size: 14px;">
                                        {{ $alloc->student->first_name ?? '' }} {{ $alloc->student->last_name ?? '' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 12px; margin-top: 1px;">
                                        Room {{ $alloc->room->room_number ?? '?' }} &bull; {{ $alloc->room->roomType->name ?? 'Standard' }} ({{ $alloc->room->hostel->name ?? '' }})
                                    </div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ $alloc->allocated_at ? $alloc->allocated_at->diffForHumans() : 'Recently' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-sm mb-0">No recent allocations.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

