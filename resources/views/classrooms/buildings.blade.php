@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Buildings & Blocks',
        'pageSubtitle' => 'Configure campus infrastructure, wings, floor topologies and architectural blocks'
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

    <div class="flex items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Campus Blocks & Wings ({{ $buildings->count() }})</h2>
            <p class="text-xs text-slate-500">Every classroom belongs to one building or block.</p>
        </div>
        <button type="button" onclick="document.getElementById('addBuildingModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition shadow-sm">
            <i class="fa-solid fa-plus mr-1"></i> Add Building
        </button>
    </div>

    <!-- Buildings Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Building</th>
                        <th class="px-4 py-3.5">Code</th>
                        <th class="px-4 py-3.5">Floors</th>
                        <th class="px-4 py-3.5">Rooms</th>
                        <th class="px-4 py-3.5">Seats</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($buildings as $index => $b)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3.5">
                                <strong class="text-slate-900 dark:text-white text-sm block">{{ $b->name }}</strong>
                                <span class="text-[11px] text-slate-400">{{ $b->notes ?? 'Campus facility' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ $b->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-medium">
                                {{ $b->floors }} {{ Str::plural('Floor', $b->floors) }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">
                                {{ $b->rooms_count }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">
                                {{ $b->rooms->sum('capacity') ?: '—' }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if($b->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick="editBuilding({{ $b->id }}, '{{ addslashes($b->name) }}', '{{ $b->code }}', {{ $b->floors }}, '{{ addslashes($b->notes ?? '') }}', {{ $b->is_active ? 1 : 0 }})" class="p-1 text-slate-400 hover:text-orange-600 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form method="POST" action="{{ route('classrooms.buildings.destroy', $b->id) }}" onsubmit="return confirm('Delete building {{ $b->name }}? All rooms inside will be removed.');" class="inline">
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
                                No buildings configured yet. Click "Add Building" to initialize a block.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Building -->
<div id="addBuildingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Add Building / Block</h3>
        <p class="text-xs text-slate-500 mb-4">Register a distinct physical block or wing on campus.</p>

        <form method="POST" action="{{ route('classrooms.buildings.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Building Name *</label>
                <input type="text" name="name" required placeholder="e.g. Science & Technology Wing, Block A" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Short Code *</label>
                    <input type="text" name="code" required placeholder="e.g. A, SCI, ADM" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Number of Floors *</label>
                    <input type="number" name="floors" value="3" min="1" max="20" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Notes / Description</label>
                <textarea name="notes" rows="2" placeholder="Primary classrooms, laboratories, etc..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-orange-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addBuildingModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Save Building</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Building -->
<div id="editBuildingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Edit Building / Block</h3>
        <p class="text-xs text-slate-500 mb-4">Update block parameters.</p>

        <form id="editBuildingForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Building Name *</label>
                <input type="text" id="edit_name" name="name" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Short Code *</label>
                    <input type="text" id="edit_code" name="code" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Floors *</label>
                    <input type="number" id="edit_floors" name="floors" min="1" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Notes</label>
                <textarea id="edit_notes" name="notes" rows="2" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5"></textarea>
            </div>

            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="rounded text-orange-600">
                <span>Active Building</span>
            </label>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editBuildingModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Update Building</button>
            </div>
        </form>
    </div>
</div>

<script>
function editBuilding(id, name, code, floors, notes, isActive) {
    document.getElementById('editBuildingForm').action = '/school/classrooms/buildings/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_code').value = code;
    document.getElementById('edit_floors').value = floors;
    document.getElementById('edit_notes').value = notes;
    document.getElementById('edit_is_active').checked = (isActive === 1);
    document.getElementById('editBuildingModal').classList.remove('hidden');
}
</script>
@endsection

