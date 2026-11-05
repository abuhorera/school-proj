@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-shield-halved text-rose-600"></i>
                <span>Student Houses Master</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Group students into competitive houses for sports tournaments, cultural fests & leadership</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('modal-add-house').classList.remove('hidden')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New House</span>
            </button>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Configured Houses ({{ $houses->count() }})</span>
            <span class="text-[11px] text-slate-400">Houses foster healthy competition and athletic esprit de corps</span>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">#</th>
                        <th class="py-3 px-4">House Color</th>
                        <th class="py-3 px-4">House Name</th>
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3 px-4">House Captain / Mentor</th>
                        <th class="py-3 px-4 text-center">Allocated Students</th>
                        <th class="py-3 px-4">Motto / Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($houses as $idx => $h)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-4">
                            <span class="w-6 h-6 rounded-lg inline-block shadow-xs border border-slate-200" style="background-color: {{ $h->color ?? '#3b82f6' }}"></span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 text-sm">
                            {{ $h->name }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-600">
                            {{ $h->code ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-700">
                            {{ $h->captain ?? 'Nominated Leader' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $h->students_count }} Students
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                            {{ $h->description ?? 'Inter-house tournament division' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            No student houses configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add House -->
<div id="modal-add-house" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-shield-halved text-rose-600"></i>
                <span>Add New Student House</span>
            </h3>
            <button onclick="document.getElementById('modal-add-house').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('students.houses.store') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">House Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Red Tigers, Blue Falcons" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-rose-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">House Color Code</label>
                    <input type="color" name="color" value="#ef4444" class="w-full h-9 bg-slate-50 border border-slate-200 rounded-lg p-1 focus:outline-rose-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Short Code</label>
                    <input type="text" name="code" placeholder="e.g. RED, BLU" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-rose-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Captain / House Mentor</label>
                <input type="text" name="captain" placeholder="e.g. Arjun Verma" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-rose-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Motto / Description</label>
                <textarea name="description" rows="2" placeholder="e.g. Courage, Valour and Sportsmanship..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-rose-500"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-add-house').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Save House</button>
            </div>
        </form>
    </div>
</div>
@endsection

