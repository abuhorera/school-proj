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
                <i class="fa-solid fa-route text-amber-500"></i>
                Transport Routes ({{ $routes->count() }})
            </span>
            <a href="{{ route('school.transport.routes.create') }}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Add New Route
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Route Name</th>
                        <th>Assigned Vehicle</th>
                        <th>Monthly Fare</th>
                        <th>Stops & Students</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $idx => $rt)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $idx + 1 }}</td>
                        <td>
                            <a href="{{ route('school.transport.routes.show', $rt->id) }}" class="font-bold text-slate-900 text-xs hover:text-sky-600 transition">
                                {{ $rt->name ?: $rt->route_title }}
                            </a>
                            @if($rt->description)
                            <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $rt->description }}</div>
                            @endif
                        </td>
                        <td>
                            @if($rt->vehicle)
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $rt->vehicle->vehicle_no }}
                                </span>
                                <span class="text-[11px] text-slate-500">({{ $rt->vehicle->seating_capacity }} seats)</span>
                            </div>
                            @else
                            <span class="text-xs text-rose-500 font-semibold bg-rose-50 px-2 py-0.5 rounded">Not Assigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-bold font-mono text-xs text-emerald-700">₹{{ number_format($rt->fare, 2) }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2 text-xs">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                    {{ $rt->stops->count() }} Stops
                                </span>
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-medium">
                                    {{ $rt->studentAssignments->count() }} Students
                                </span>
                            </div>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('school.transport.routes.show', $rt->id) }}" class="px-3 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-map-pin text-[10px]"></i> Students & Stops
                                </a>
                                <a href="{{ route('school.transport.routes.edit', $rt->id) }}" title="Edit Route" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('school.transport.routes.destroy', $rt->id) }}" method="POST" onsubmit="return confirm('Delete route {{ $rt->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Route" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400 text-xs">No transit routes configured yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

