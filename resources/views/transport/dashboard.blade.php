@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('transport.nav')

    <!-- KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('school.transport.vehicles') }}" class="ts-stat-card">
            <div class="ts-stat-icon ts-bg-yellow">
                <i class="fa-solid fa-van-shuttle"></i>
            </div>
            <div>
                <div class="ts-stat-label">Total Vehicles</div>
                <div class="ts-stat-value text-amber-600">{{ $totalVehicles }}</div>
                <div class="text-[11px] text-slate-500 mt-1 font-medium">Buses & Vans registered</div>
            </div>
        </a>

        <a href="{{ route('school.transport.routes') }}" class="ts-stat-card">
            <div class="ts-stat-icon ts-bg-blue">
                <i class="fa-solid fa-route"></i>
            </div>
            <div>
                <div class="ts-stat-label">Active Routes</div>
                <div class="ts-stat-value text-sky-600">{{ $activeRoutes }}</div>
                <div class="text-[11px] text-slate-500 mt-1 font-medium">Scheduled transit corridors</div>
            </div>
        </a>

        <div class="ts-stat-card">
            <div class="ts-stat-icon ts-bg-indigo">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="ts-stat-label">Students Using Transport</div>
                <div class="ts-stat-value text-indigo-600">{{ $totalStudents }}</div>
                <div class="text-[11px] text-slate-500 mt-1 font-medium">Active bus passes</div>
            </div>
        </div>

        <a href="{{ route('school.transport.live') }}" class="ts-stat-card">
            <div class="ts-stat-icon ts-bg-emerald">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <div class="ts-stat-label">Vehicles On Duty</div>
                <div class="ts-stat-value text-emerald-600">{{ $vehiclesOnDuty }}</div>
                <div class="text-[11px] text-emerald-700 font-semibold mt-1">Currently in service</div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Vehicle Inventory & Status -->
        <div class="lg:col-span-2 space-y-6">
            <div class="ts-card overflow-hidden">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-bus text-amber-500"></i>
                        Vehicle Inventory & Status
                    </span>
                    <a href="{{ route('school.transport.vehicles.create') }}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add New Vehicle
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Vehicle No</th>
                                <th>Driver Name</th>
                                <th>Route Name</th>
                                <th>Capacity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $veh)
                            <tr>
                                <td>
                                    <div class="font-bold text-slate-900 text-xs font-mono">{{ $veh->vehicle_no }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $veh->vehicle_model }}</div>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800 text-xs">{{ $veh->driver_name ?: ($veh->driver->name ?? 'Unassigned') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $veh->driver_phone ?: ($veh->driver->phone ?? '') }}</div>
                                </td>
                                <td>
                                    @if($veh->route)
                                    <a href="{{ route('school.transport.routes.show', $veh->route->id) }}" class="text-xs font-bold text-sky-600 hover:underline">
                                        {{ $veh->route->name ?: $veh->route->route_title }}
                                    </a>
                                    @else
                                    <span class="text-xs text-slate-400">Spare Vehicle / No Route</span>
                                    @endif
                                </td>
                                <td class="text-xs font-mono text-slate-700">
                                    {{ $veh->seating_capacity }} Seater
                                </td>
                                <td>
                                    <span class="badge-status {{ in_array(strtolower($veh->status), ['in service', 'active']) ? 'badge-in-service' : (strtolower($veh->status) === 'on a trip' ? 'badge-on-trip' : 'badge-maintenance') }}">
                                        {{ strtoupper($veh->status ?: 'IN SERVICE') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400 text-xs">No vehicles registered yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Active Routes & Tracking Launcher -->
        <div class="space-y-6">
            <!-- Live Tracking Card -->
            <div class="ts-card p-5 bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-satellite-dish"></i> GPS Tracking
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        LIVE PULSE
                    </span>
                </div>
                <h4 class="text-base font-bold mb-1">Fleet Telematics & Live Map</h4>
                <p class="text-xs text-slate-300 mb-4">View real-time bus locations, speed alerts, delay predictions, and driver communication.</p>
                <a href="{{ route('school.transport.tracking') }}" class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-slate-900 font-extrabold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-map-location-dot"></i> Open Live Vehicle Tracking
                </a>
            </div>

            <!-- Routes Summary -->
            <div class="ts-card overflow-hidden">
                <div class="ts-section-title">
                    <span>Route Network</span>
                    <a href="{{ route('school.transport.routes') }}" class="text-xs text-sky-600 font-semibold hover:underline">View All &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($routes->take(5) as $rt)
                    <div class="p-3.5 flex items-center justify-between">
                        <div>
                            <a href="{{ route('school.transport.routes.show', $rt->id) }}" class="font-bold text-slate-900 text-xs hover:text-sky-600 transition block">
                                {{ $rt->name ?: $rt->route_title }}
                            </a>
                            <div class="text-[10px] text-slate-400 flex items-center gap-2 mt-0.5">
                                <span><i class="fa-solid fa-map-pin text-[9px]"></i> {{ $rt->stops->count() }} Stops</span>
                                <span>·</span>
                                <span><i class="fa-solid fa-users text-[9px]"></i> {{ $rt->studentAssignments->count() }} Students</span>
                            </div>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 px-2 py-1 rounded">
                            ₹{{ number_format($rt->fare, 0) }}/mo
                        </span>
                    </div>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs">No routes configured.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

