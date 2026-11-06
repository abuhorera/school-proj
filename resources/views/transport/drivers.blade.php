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

    <!-- Status Filters & Action Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-5">
        <div class="flex items-center space-x-1.5 overflow-x-auto w-full sm:w-auto">
            <a href="{{ route('school.transport.drivers', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('school.transport.drivers', ['status' => 'Active']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'Active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Active ({{ $counts['active'] }})
            </a>
            <a href="{{ route('school.transport.drivers', ['status' => 'Suspended']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'Suspended' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Suspended ({{ $counts['suspended'] }})
            </a>
            <a href="{{ route('school.transport.drivers', ['status' => 'Left']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'Left' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Left ({{ $counts['left'] }})
            </a>
        </div>

        <div class="flex items-center space-x-2 w-full sm:w-auto">
            <form action="{{ route('school.transport.drivers') }}" method="GET" class="relative flex-1 sm:w-64">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver, phone, license..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
            </form>
            <button onclick="document.getElementById('addDriverModal').classList.remove('hidden')" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-plus text-[10px]"></i> Add Driver
            </button>
        </div>
    </div>

    <!-- Drivers Table -->
    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-id-card text-slate-700"></i>
                Registered Drivers & Operators ({{ $drivers->total() }})
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Driver</th>
                        <th>Driver ID</th>
                        <th>Mobile Number</th>
                        <th>Driving Licence</th>
                        <th>Assigned Vehicle</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $idx => $drv)
                    <tr>
                        <td class="text-slate-400 font-mono text-xs">{{ $drivers->firstItem() + $idx }}</td>
                        <td>
                            <div class="font-bold text-slate-900 text-xs">{{ $drv->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $drv->address ?: 'Raipur Campus' }}</div>
                        </td>
                        <td>
                            <span class="font-mono text-xs font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded border border-sky-100">
                                {{ $drv->driver_id_code }}
                            </span>
                        </td>
                        <td class="font-mono text-xs text-slate-800 font-semibold">{{ $drv->phone }}</td>
                        <td>
                            <div class="font-mono text-xs font-bold text-slate-700">{{ $drv->license_number ?: '—' }}</div>
                            @if($drv->license_expiry)
                            <div class="text-[10px] text-slate-400">Valid till {{ $drv->license_expiry->format('d M Y') }}</div>
                            @endif
                        </td>
                        <td>
                            @if($drv->vehicles->isNotEmpty())
                            <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">
                                {{ $drv->vehicles->first()->vehicle_no }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">Roster Pool</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ $drv->status === 'Active' ? 'badge-in-service' : 'badge-maintenance' }}">
                                {{ $drv->status }}
                            </span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="openEditDriverModal({{ $drv->id }}, '{{ addslashes($drv->name) }}', '{{ $drv->phone }}', '{{ $drv->license_number ?? '' }}', '{{ $drv->license_expiry ? $drv->license_expiry->format('Y-m-d') : '' }}', '{{ addslashes($drv->address ?? '') }}', '{{ $drv->status }}', '{{ addslashes($drv->device_info ?? '') }}')" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('school.transport.drivers.destroy', $drv->id) }}" method="POST" onsubmit="return confirm('Delete driver {{ $drv->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 text-xs">No drivers found matching criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($drivers->hasPages())
        <div class="p-3 border-t border-slate-100">
            {{ $drivers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: Add Driver -->
<div id="addDriverModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Add New Fleet Driver</h4>
            <button onclick="document.getElementById('addDriverModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('school.transport.drivers.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Ramesh Kumar" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Phone <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" required placeholder="e.g. 9826011001" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Driving Licence #</label>
                    <input type="text" name="license_number" placeholder="Licence Number" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Licence Expiry</label>
                    <input type="date" name="license_expiry" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Left">Left</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Residential Address</label>
                <textarea name="address" rows="2" placeholder="Local address..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addDriverModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-amber-500 text-slate-900 rounded-lg hover:bg-amber-600">Register Driver</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Driver -->
<div id="editDriverModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Edit Driver Details</h4>
            <button onclick="document.getElementById('editDriverModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="editDriverForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Name <span class="text-rose-500">*</span></label>
                <input type="text" id="editDrvName" name="name" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Phone <span class="text-rose-500">*</span></label>
                <input type="text" id="editDrvPhone" name="phone" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Licence #</label>
                    <input type="text" id="editDrvLic" name="license_number" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Licence Expiry</label>
                    <input type="date" id="editDrvExp" name="license_expiry" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select id="editDrvStatus" name="status" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300">
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Left">Left</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Address</label>
                <textarea id="editDrvAddr" name="address" rows="2" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editDriverModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-sky-600 text-white rounded-lg hover:bg-sky-700">Update Driver</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditDriverModal(id, name, phone, lic, exp, addr, status, dev) {
    document.getElementById('editDriverForm').action = "{{ url('school/transport/drivers') }}/" + id;
    document.getElementById('editDrvName').value = name;
    document.getElementById('editDrvPhone').value = phone;
    document.getElementById('editDrvLic').value = lic;
    document.getElementById('editDrvExp').value = exp;
    document.getElementById('editDrvAddr').value = addr;
    document.getElementById('editDrvStatus').value = status;
    document.getElementById('editDriverModal').classList.remove('hidden');
}
</script>
@endsection

