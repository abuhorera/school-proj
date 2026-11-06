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

    <!-- Route Overview Header -->
    <div class="ts-card p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-slate-900">{{ $route->name ?: $route->route_title }}</h3>
                    <span class="badge-status badge-in-service">ACTIVE ROUTE</span>
                </div>
                <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                    <span><strong>Default Fare:</strong> ₹{{ number_format($route->fare, 2) }}/month</span>
                    <span>·</span>
                    <span><strong>Assigned Vehicle:</strong> {{ $route->vehicle ? $route->vehicle->vehicle_no.' ('.$route->vehicle->vehicle_model.')' : 'None' }}</span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('school.transport.routes') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                    &larr; Back to Routes
                </a>
                <a href="{{ route('school.transport.routes.edit', $route->id) }}" class="px-3 py-1.5 bg-sky-50 text-sky-700 border border-sky-200 rounded-lg text-xs font-bold hover:bg-sky-100 transition">
                    <i class="fa-solid fa-pen mr-1"></i> Edit Route
                </a>
            </div>
        </div>
    </div>

    <!-- Section 1: Route Stops & Pickups -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Add Stop Form -->
        <div class="ts-card overflow-hidden h-fit">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-amber-500"></i>
                    Add Route Stop
                </span>
            </div>
            <form action="{{ route('school.transport.routes.stops.store', $route->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Stop Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Shankar Nagar Chowk, Overbridge" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pickup Time (Morning)</label>
                        <input type="text" name="pickup_time" placeholder="07:15 AM" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Drop Time (Afternoon)</label>
                        <input type="text" name="drop_time" placeholder="02:45 PM" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Stop Monthly Fare (₹)</label>
                    <input type="number" step="0.01" name="fare" value="{{ $route->fare }}" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 font-mono">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add Stop to Route
                </button>
            </form>
        </div>

        <!-- Stops Table -->
        <div class="lg:col-span-2 ts-card overflow-hidden">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-slate-700"></i>
                    Pick-up & Drop Stops ({{ $route->stops->count() }})
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Stop Name</th>
                            <th>Pickup</th>
                            <th>Drop</th>
                            <th>Fare</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($route->stops as $idx => $stop)
                        <tr>
                            <td class="text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                            <td>
                                <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    {{ $stop->name }}
                                </div>
                            </td>
                            <td class="text-xs text-slate-700 font-semibold">{{ $stop->pickup_time ?: '—' }}</td>
                            <td class="text-xs text-slate-700 font-semibold">{{ $stop->drop_time ?: '—' }}</td>
                            <td class="font-mono text-xs font-bold text-emerald-700">₹{{ number_format($stop->fare ?: $route->fare, 2) }}</td>
                            <td class="text-right">
                                <form action="{{ route('school.transport.routes.stops.destroy', $stop->id) }}" method="POST" onsubmit="return confirm('Remove stop {{ $stop->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400 text-xs">No stops added yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section 2: Schedules & Runs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Add Run Schedule Form -->
        <div class="ts-card overflow-hidden h-fit">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-amber-500"></i>
                    Add Run Schedule
                </span>
            </div>
            <form action="{{ route('school.transport.routes.schedules.store', $route->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Run Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="label" required placeholder="e.g. Morning Inbound Run, Senior Drop" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Direction <span class="text-rose-500">*</span></label>
                    <select name="direction" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                        <option value="Inbound">Inbound (To School)</option>
                        <option value="Outbound">Outbound (From School)</option>
                        <option value="Both">Both Directions</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Starts At <span class="text-rose-500">*</span></label>
                        <input type="text" name="start_time" required placeholder="07:00 AM" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ends At</label>
                        <input type="text" name="end_time" placeholder="08:15 AM" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Operating Days</label>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" name="days_of_week[]" value="{{ $day }}" checked class="text-amber-600 rounded">
                            <span>{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i> Add Schedule Run
                </button>
            </form>
        </div>

        <!-- Schedules Table -->
        <div class="lg:col-span-2 ts-card overflow-hidden">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-slate-700"></i>
                    Configured Runs ({{ $route->schedules->count() }})
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Run Name</th>
                            <th>Direction</th>
                            <th>Starts</th>
                            <th>Ends</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($route->schedules as $sch)
                        <tr>
                            <td class="font-bold text-slate-900 text-xs">{{ $sch->label }}</td>
                            <td>
                                <span class="text-xs px-2 py-0.5 rounded font-medium {{ $sch->direction === 'Inbound' ? 'bg-sky-50 text-sky-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $sch->direction }}
                                </span>
                            </td>
                            <td class="text-xs font-semibold text-slate-700">{{ $sch->start_time }}</td>
                            <td class="text-xs text-slate-600">{{ $sch->end_time ?: '—' }}</td>
                            <td>
                                <span class="badge-status badge-in-service">{{ $sch->status }}</span>
                            </td>
                            <td class="text-right">
                                <form action="{{ route('school.transport.routes.schedules.destroy', $sch->id) }}" method="POST" onsubmit="return confirm('Delete run {{ $sch->label }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400 text-xs">No runs configured for this route yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section 3: Assigned Students & Bus Passes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Assign Student Form -->
        <div class="ts-card overflow-hidden h-fit">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-amber-500"></i>
                    Assign Student to Route
                </span>
            </div>
            <form action="{{ route('school.transport.routes.assign', $route->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Student <span class="text-rose-500">*</span></label>
                    <select name="student_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                        <option value="">-- Choose Student --</option>
                        @foreach($students as $stud)
                        <option value="{{ $stud->id }}">{{ $stud->first_name }} {{ $stud->last_name }} ({{ $stud->admission_no ?? 'ID:'.$stud->id }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assign to Stop <span class="text-rose-500">*</span></label>
                    <select name="route_stop_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                        <option value="">-- Choose Stop --</option>
                        @foreach($route->stops as $stop)
                        <option value="{{ $stop->id }}">{{ $stop->name }} (Pickup: {{ $stop->pickup_time ?: '—' }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Assign Student & Issue Bus Pass
                </button>
            </form>
        </div>

        <!-- Assigned Students Table -->
        <div class="lg:col-span-2 ts-card overflow-hidden">
            <div class="ts-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-slate-700"></i>
                    Students Using This Route ({{ $route->studentAssignments->count() }})
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Admission #</th>
                            <th>Student Name</th>
                            <th>Designated Stop</th>
                            <th>Fee Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($route->studentAssignments as $assign)
                        <tr>
                            <td class="font-mono text-xs text-slate-500">{{ $assign->student->admission_no ?? ('ID-'.$assign->student_id) }}</td>
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $assign->student->first_name ?? 'Student' }} {{ $assign->student->last_name ?? '' }}</div>
                            </td>
                            <td class="text-xs text-slate-700 font-medium">{{ $assign->stop->name ?? 'General' }}</td>
                            <td>
                                <span class="badge-status {{ $assign->fee_status === 'Paid' ? 'badge-in-service' : 'badge-maintenance' }}">
                                    {{ $assign->fee_status }}
                                </span>
                            </td>
                            <td class="text-right">
                                <form action="{{ route('school.transport.routes.remove-student', $assign->id) }}" method="POST" onsubmit="return confirm('Remove student from route?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-xs">No students currently assigned to this route.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

