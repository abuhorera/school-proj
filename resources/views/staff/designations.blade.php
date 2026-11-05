@extends('layouts.app')

@section('title', 'Designations - Human Resource')

@section('content')
<div class="space-y-6">
    @include('staff.partials.subnav')

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-id-badge"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Designations Catalog</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Manage employment titles, official job roles, and organizational hierarchy</p>
        </div>
        <div>
            <button onclick="document.getElementById('addDesigModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Designation</span>
            </button>
        </div>
    </div>

    <!-- Designations Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Active Job Designations ({{ $designations->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Designation Title</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4">Staff Count</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($designations as $index => $d)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            {{ $d->name }}
                        </td>
                        <td class="py-3 px-4 text-[11px] text-slate-500 max-w-sm">
                            {{ $d->description ?? 'Institutional Position' }}
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('staff.index', ['designation' => $d->name]) }}" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-700 hover:bg-pink-100 transition inline-flex items-center space-x-1">
                                <i class="fa-solid fa-user-tie text-[9px]"></i>
                                <span>{{ $d->staff_count }} Staff</span>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('staff.designations.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Remove designation?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Designation">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400">No designations configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Designation -->
<div id="addDesigModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add New Designation</h3>
            <button onclick="document.getElementById('addDesigModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('staff.designations.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Designation Title <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Senior Laboratory Technician" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description / Grade</label>
                <textarea name="description" rows="3" placeholder="Job grade, role specifics, key responsibilities..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addDesigModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Designation</button>
            </div>
        </form>
    </div>
</div>
@endsection

