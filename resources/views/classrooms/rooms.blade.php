@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Classroom Management',
        'pageSubtitle' => 'Every room on campus — who is in it, right now.'
    ])

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <!-- 5 KPI Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Active Rooms</span>
                <span class="w-7 h-7 rounded-lg bg-orange-50 dark:bg-orange-950/50 text-orange-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-door-open"></i>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalActiveRooms }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Campus spaces</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Everyday Seats</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-chair"></i>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalEverydaySeats }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Regular capacity</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Exam Seats</span>
                <span class="w-7 h-7 rounded-lg bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clipboard"></i>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalExamSeats }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Spaced seating</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Under Repair</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-wrench"></i>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $underMaintenanceCount }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Maintenance jobs</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Unmapped Labels</span>
                <span class="w-7 h-7 rounded-lg bg-pink-50 dark:bg-pink-950/50 text-pink-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-tags"></i>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $unmatchedLabelsCount }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5"><a href="{{ route('classrooms.labels') }}" class="text-orange-600 hover:underline">Match now &rarr;</a></p>
        </div>
    </div>

    <!-- Filters & Action Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <form method="GET" action="{{ route('classrooms.rooms') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 flex-1 items-end">
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Building</label>
                    <select name="building_id" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-orange-500">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $b)
                            <option value="{{ $b->id }}" {{ request('building_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Room Type</label>
                    <select name="room_type" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-orange-500">
                        <option value="">All Types</option>
                        @foreach($roomTypes as $t)
                            <option value="{{ $t }}" {{ request('room_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Search Room</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Code or room name..." class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-2.5 focus:ring-orange-500">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-xs font-semibold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 rounded-lg transition">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('classrooms.rooms') }}" class="px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-100 rounded-lg transition">Reset</a>
                </div>
            </form>

            <div class="flex items-center gap-2 flex-wrap shrink-0">
                <button type="button" onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition shadow-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Add Room
                </button>
                <form method="POST" action="{{ route('classrooms.rooms.import-halls') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 rounded-lg transition">
                        <i class="fa-solid fa-file-import mr-1"></i> Import Halls
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Room</th>
                        <th class="px-4 py-3.5">Type & Place</th>
                        <th class="px-4 py-3.5">Home To</th>
                        <th class="px-4 py-3.5">Seats</th>
                        <th class="px-4 py-3.5">Facilities</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($rooms as $index => $room)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $rooms->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900 dark:text-white text-sm">
                                    {{ $room->room_number }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                    {{ $room->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300 block w-fit mb-0.5">
                                    {{ $room->room_type }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    {{ $room->building->name ?? 'Campus' }} &middot; Floor {{ $room->floor }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($room->homeSection)
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $room->homeSection->schoolClass->name ?? '' }} ({{ $room->homeSection->name }})
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $room->capacity }}</span>
                                <span class="text-[10px] text-slate-400 block">Exam: {{ $room->exam_capacity ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <div class="flex items-center gap-1 flex-wrap">
                                    @foreach(array_slice($room->facilities_json ?? [], 0, 3) as $fac)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                            {{ $fac }}
                                        </span>
                                    @endforeach
                                    @if(count($room->facilities_json ?? []) > 3)
                                        <span class="text-[9px] text-slate-400">+{{ count($room->facilities_json) - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($room->status === 'Available')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Available</span>
                                @elseif($room->status === 'Maintenance')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Maintenance</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">{{ $room->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('classrooms.timetable', ['room_id' => $room->id]) }}" class="px-2 py-1 text-[11px] text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md transition" title="Timetable">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </a>
                                    <form method="POST" action="{{ route('classrooms.rooms.destroy', $room->id) }}" onsubmit="return confirm('Delete classroom {{ $room->room_number }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                                No classrooms registered. Click "Add Room" or "Quick Setup" above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rooms->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal 1: Add Room -->
<div id="addRoomModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-lg w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Add New Classroom</h3>
        <p class="text-xs text-slate-500 mb-4">Register a physical room, hall or laboratory on campus.</p>

        <form method="POST" action="{{ route('classrooms.rooms.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Room Code / Number *</label>
                    <input type="text" name="room_number" required placeholder="e.g. R-101, LAB-PHY" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Friendly Name</label>
                    <input type="text" name="name" placeholder="e.g. Physics Lab" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Building *</label>
                    <select name="building_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                        @foreach($buildings as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Room Type *</label>
                    <select name="room_type" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                        @foreach($roomTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Floor</label>
                    <input type="number" name="floor" value="1" min="0" max="20" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Everyday Seats *</label>
                    <input type="number" name="capacity" value="40" min="1" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Exam Seats</label>
                    <input type="number" name="exam_capacity" value="25" min="1" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Home To Section (Optional)</label>
                <select name="home_section_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                    <option value="">None (Floating / Special room)</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->schoolClass->name ?? 'Class' }} - {{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Create Room</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Quick Setup Bulk Generator -->
<div id="quickSetupModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-lg w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Quick Setup — Bulk Generate Rooms</h3>
        <p class="text-xs text-slate-500 mb-4">Quickly initialize an entire building block with patterned room codes.</p>

        <form method="POST" action="{{ route('classrooms.rooms.quick-setup') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Building *</label>
                <select name="room_building_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                    @foreach($buildings as $b)
                        <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Room Code Prefix *</label>
                    <input type="text" name="prefix" value="R-" required placeholder="e.g. R-, BLK-" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Room Type</label>
                    <select name="room_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                        <option value="Classroom">Classroom</option>
                        <option value="Science lab">Science lab</option>
                        <option value="Computer lab">Computer lab</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Floor From</label>
                    <input type="number" name="floor_from" value="1" min="0" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Floor To</label>
                    <input type="number" name="floor_to" value="3" min="1" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rooms/Floor</label>
                    <input type="number" name="rooms_per_floor" value="4" min="1" max="20" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Seating Capacity per Room</label>
                <input type="number" name="seating_capacity" value="40" min="1" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('quickSetupModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Generate Rooms</button>
            </div>
        </form>
    </div>
</div>
@endsection

