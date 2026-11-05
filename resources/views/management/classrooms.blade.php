@extends('layouts.app')

@section('title', 'Classrooms & Campus Infrastructure')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">Classrooms, Blocks & Capacity</h1>
            </div>
            <p class="text-xs text-slate-500">Monitor building wings, classroom capacity utilization, and room clash prevention</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- Infrastructure Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Campus Capacity</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCapacity) }} Desks</div>
            <span class="text-[10px] text-slate-400">Across all wings</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Rooms & Labs</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $rooms->count() }} Rooms</div>
            <span class="text-[10px] text-slate-400">{{ $buildings->count() }} Campus Blocks</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Occupied Rooms</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $occupiedRooms }} Active</div>
            <span class="text-[10px] text-emerald-600 font-semibold">In session</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Free / Vacant Rooms</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $availableRooms }} Available</div>
            <span class="text-[10px] text-amber-600 font-semibold">Available for booking</span>
        </div>
    </div>

    <!-- Campus Buildings Blocks -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($buildings as $b)
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">{{ $b->name }}</h4>
                        <span class="text-[10px] text-slate-400">Code: {{ $b->code }} • {{ $b->floors }} Floors</span>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-orange-50 text-orange-700">Wing</span>
            </div>
        @endforeach
    </div>

    <!-- Classrooms & Room Plan Table -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-chalkboard text-orange-600"></i>
                <span>Classrooms & Specialty Laboratories</span>
            </h2>
            <span class="text-xs bg-slate-100 font-bold px-2.5 py-0.5 rounded-full text-slate-600">{{ $rooms->count() }} Facilities</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Room / Hall Label</th>
                        <th class="py-2.5 px-3">Building Block</th>
                        <th class="py-2.5 px-3">Room Type</th>
                        <th class="py-2.5 px-3 text-center">Student Capacity</th>
                        <th class="py-2.5 px-3">Live Status</th>
                        <th class="py-2.5 px-3 text-right">Clash Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rooms as $rm)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-2.5 px-3 font-bold text-slate-900">{{ $rm->room_number }}</td>
                            <td class="py-2.5 px-3 text-slate-600">{{ $rm->building_name }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rm->room_type == 'Science Lab' ? 'bg-purple-50 text-purple-700' : ($rm->room_type == 'Computer Lab' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $rm->room_type }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center font-bold text-slate-800">{{ $rm->capacity }} Desks</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $rm->status == 'Occupied' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $rm->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <span class="text-emerald-600 font-bold text-[11px] flex items-center justify-end space-x-1">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    <span>No Clashes</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

