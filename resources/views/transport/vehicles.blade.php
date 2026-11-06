@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('transport.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-bus text-amber-500"></i>
                School Fleet Vehicles ({{ $vehicles->count() }})
            </span>
            <a href="{{ route('school.transport.vehicles.create') }}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Add New Vehicle
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Vehicle Number</th>
                        <th>Model</th>
                        <th>Capacity</th>
                        <th>Driver Details</th>
                        <th>Tracking / GPS</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $idx => $veh)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs font-mono">{{ $veh->vehicle_no }}</div>
                            @if($veh->note)
                            <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $veh->note }}</div>
                            @endif
                        </td>
                        <td class="text-xs text-slate-700">{{ $veh->vehicle_model ?: 'Bus' }}</td>
                        <td class="text-xs font-mono font-bold text-slate-800">{{ $veh->seating_capacity }} seats</td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $veh->driver_name ?: ($veh->driver->name ?? 'Unassigned') }}</div>
                            <div class="text-[10px] text-slate-500">{{ $veh->driver_phone ?: ($veh->driver->phone ?? '—') }}</div>
                        </td>
                        <td>
                            @if($veh->tracking_enabled)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Enabled
                            </span>
                            @if($veh->gps_device_imei)
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">IMEI: {{ $veh->gps_device_imei }}</div>
                            @endif
                            @else
                            <span class="text-xs text-slate-400">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ in_array(strtolower($veh->status), ['in service', 'active']) ? 'badge-in-service' : 'badge-maintenance' }}">
                                {{ $veh->status ?: 'In Service' }}
                            </span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('school.transport.tracking') }}?vehicle_id={{ $veh->id }}" title="Track Bus" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition">
                                    <i class="fa-solid fa-location-crosshairs"></i>
                                </a>
                                <a href="{{ route('school.transport.vehicles.edit', $veh->id) }}" title="Edit Vehicle" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('school.transport.vehicles.destroy', $veh->id) }}" method="POST" onsubmit="return confirm('Remove vehicle {{ $veh->vehicle_no }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Vehicle" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 text-xs">No vehicles registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

