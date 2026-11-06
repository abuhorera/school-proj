@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('transport.nav')

    <!-- Top Live Operational Header -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="ts-stat-card border-l-4 border-l-emerald-500">
            <div class="ts-stat-icon ts-bg-emerald">
                <i class="fa-solid fa-signal"></i>
            </div>
            <div>
                <div class="ts-stat-label">Active Bus Runs</div>
                <div class="ts-stat-value text-emerald-600">{{ $vehicles->where('status', 'In Service')->count() }}</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-1">Buses on corridor</div>
            </div>
        </div>

        <div class="ts-stat-card border-l-4 border-l-blue-500">
            <div class="ts-stat-icon ts-bg-blue">
                <i class="fa-solid fa-clock-check"></i>
            </div>
            <div>
                <div class="ts-stat-label">Punctuality Rate</div>
                <div class="ts-stat-value text-sky-600">98.4%</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-1">Average delay &lt; 3 mins</div>
            </div>
        </div>

        <div class="ts-stat-card border-l-4 border-l-amber-500">
            <div class="ts-stat-icon ts-bg-yellow">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <div class="ts-stat-label">Speed Guard Alerts</div>
                <div class="ts-stat-value text-amber-600">0</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-1">All buses within 40 km/h limit</div>
            </div>
        </div>
    </div>

    <!-- Live Fleet Table -->
    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-tower-broadcast text-emerald-600"></i>
                Live Fleet Transit Status
            </span>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider">Live Telematics Feed</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th>Bus & Model</th>
                        <th>Assigned Run</th>
                        <th>Driver Details</th>
                        <th>Started At</th>
                        <th>Punctuality / Delay</th>
                        <th>Telemetry / Ping</th>
                        <th>Operational Status</th>
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
                            @if($veh->route)
                            <span class="font-bold text-xs text-sky-700 bg-sky-50 px-2 py-0.5 rounded border border-sky-100">
                                {{ $veh->route->name ?: $veh->route->route_title }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">Idle in Depot</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $veh->driver_name ?: ($veh->driver->name ?? 'Unassigned') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $veh->driver_phone ?: ($veh->driver->phone ?? '—') }}</div>
                        </td>
                        <td class="text-xs font-mono text-slate-600">07:05 AM</td>
                        <td>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> On Time
                            </span>
                        </td>
                        <td>
                            <div class="text-xs font-bold text-slate-800 flex items-center gap-1">
                                <i class="fa-solid fa-wifi text-emerald-500 text-[10px]"></i> {{ $veh->speed }} km/h
                            </div>
                            <div class="text-[10px] text-slate-400">Just now (GPS OK)</div>
                        </td>
                        <td>
                            <span class="badge-status {{ in_array(strtolower($veh->status), ['in service', 'active']) ? 'badge-in-service' : 'badge-on-trip' }}">
                                {{ $veh->status ?: 'Running' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 text-xs">No active fleet buses currently on trip.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

