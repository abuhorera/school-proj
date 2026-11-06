@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('transport.nav')

    <!-- Top GPS Tracker Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Interactive Simulated Map Canvas -->
        <div class="lg:col-span-2 ts-card overflow-hidden bg-slate-900 border-slate-800 text-white rounded-2xl shadow-xl flex flex-col justify-between" style="min-height: 380px;">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-100 flex items-center gap-2">
                            <span>GPS Live Telemetry: {{ $selectedVehicle ? $selectedVehicle->vehicle_no : 'Fleet Tracker' }}</span>
                            <span class="text-xs bg-emerald-500/20 text-emerald-300 font-mono px-2 py-0.5 rounded border border-emerald-500/30">ONLINE</span>
                        </h4>
                        <div class="text-[11px] text-slate-400">
                            Route: {{ $selectedVehicle && $selectedVehicle->route ? ($selectedVehicle->route->name ?: $selectedVehicle->route->route_title) : 'Campus Transit' }}
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-xs font-mono font-bold text-amber-400">{{ $selectedVehicle ? $selectedVehicle->speed : '32' }} KM/H</div>
                    <div class="text-[10px] text-slate-400">Heading North-East</div>
                </div>
            </div>

            <!-- Stylized GPS Map Canvas Area -->
            <div class="relative flex-1 p-6 flex items-center justify-center bg-radial from-slate-800 to-slate-950 overflow-hidden" style="min-height: 240px;">
                <!-- Grid background lines -->
                <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 24px 24px;"></div>
                
                <!-- Simulated Highway Path -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-40">
                    <path d="M 50 180 Q 250 50, 450 120 T 750 90" fill="none" stroke="#38bdf8" stroke-width="4" stroke-dasharray="8 6"/>
                    <circle cx="50" cy="180" r="6" fill="#10b981"/>
                    <circle cx="250" cy="85" r="5" fill="#f59e0b"/>
                    <circle cx="450" cy="120" r="5" fill="#f59e0b"/>
                    <circle cx="750" cy="90" r="7" fill="#ef4444"/>
                </svg>

                <!-- Active Bus Marker with pulse -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-900 flex items-center justify-center text-xl shadow-2xl shadow-amber-500/50 border-2 border-white animate-bounce">
                        <i class="fa-solid fa-bus"></i>
                    </div>
                    <div class="mt-2 bg-slate-900/90 backdrop-blur-xs border border-slate-700 px-3 py-1 rounded-full text-center shadow-lg">
                        <span class="text-xs font-bold text-white font-mono">{{ $selectedVehicle ? $selectedVehicle->vehicle_no : 'CG04AB1234' }}</span>
                        <span class="text-[10px] text-emerald-400 ml-1 font-semibold">● Live Moving</span>
                    </div>
                </div>

                <!-- GPS Coordinates Chip -->
                <div class="absolute bottom-3 left-4 bg-slate-900/80 backdrop-blur-xs border border-slate-700 px-3 py-1.5 rounded-lg text-[10px] font-mono text-slate-300">
                    LAT: {{ $selectedVehicle ? $selectedVehicle->current_latitude : '21.2480' }} | LNG: {{ $selectedVehicle ? $selectedVehicle->current_longitude : '81.6320' }}
                </div>

                <!-- Speed Gauge Chip -->
                <div class="absolute bottom-3 right-4 bg-slate-900/80 backdrop-blur-xs border border-slate-700 px-3 py-1.5 rounded-lg text-[10px] font-mono text-emerald-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-satellite text-xs"></i> 12 Satellites Locked
                </div>
            </div>

            <!-- Route Progress Bar -->
            <div class="p-4 bg-slate-950/80 border-t border-slate-800">
                <div class="flex items-center justify-between text-xs text-slate-400 mb-1.5">
                    <span>Origin: School Campus</span>
                    <span class="text-emerald-400 font-bold">Next Stop: Shankar Nagar Main Chowk (3 mins away)</span>
                    <span>Destination: Sector 5</span>
                </div>
                <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-amber-500 h-full rounded-full" style="width: 65%;"></div>
                </div>
            </div>
        </div>

        <!-- Vehicle Quick Selection Card -->
        <div class="space-y-4">
            <div class="ts-card overflow-hidden">
                <div class="ts-section-title">
                    <span>Select Bus to Track</span>
                </div>
                <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                    @forelse($vehicles as $veh)
                    <a href="{{ route('school.transport.tracking') }}?vehicle_id={{ $veh->id }}" class="p-3.5 block transition hover:bg-slate-50 {{ $selectedVehicle && $selectedVehicle->id == $veh->id ? 'bg-amber-50/60 border-l-4 border-l-amber-500' : '' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-bold text-slate-900">{{ $veh->vehicle_no }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ in_array(strtolower($veh->status), ['in service', 'active']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $veh->status ?: 'Idle' }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-700 font-semibold mt-0.5">{{ $veh->vehicle_model }}</div>
                        <div class="text-[11px] text-slate-500 flex items-center justify-between mt-1">
                            <span>Driver: {{ $veh->driver_name ?: ($veh->driver->name ?? 'Unassigned') }}</span>
                            <span class="text-emerald-600 font-bold"><i class="fa-solid fa-location-arrow text-[10px]"></i> Track</span>
                        </div>
                    </a>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs">No buses with tracking enabled.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Telematics Fleet Register Table -->
    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-700"></i>
                Tracked Fleet Telemetry Log
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Bus & Model</th>
                        <th>Assigned Driver</th>
                        <th>Telemetry Reporting</th>
                        <th>Current Trip Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $idx => $veh)
                    <tr>
                        <td class="text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs font-mono">{{ $veh->vehicle_no }}</div>
                            <div class="text-[10px] text-slate-400">{{ $veh->vehicle_model }}</div>
                        </td>
                        <td>
                            <div class="font-bold text-slate-800 text-xs">{{ $veh->driver_name ?: ($veh->driver->name ?? 'Unassigned') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $veh->driver_phone ?: ($veh->driver->phone ?? '—') }}</div>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Just now (GPS OK)
                            </span>
                        </td>
                        <td>
                            <span class="badge-status {{ in_array(strtolower($veh->status), ['in service', 'active']) ? 'badge-on-trip' : 'badge-maintenance' }}">
                                {{ $veh->status === 'In Service' ? 'On a trip' : $veh->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('school.transport.tracking') }}?vehicle_id={{ $veh->id }}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs rounded-lg transition shadow-2xs">
                                Track Now &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

