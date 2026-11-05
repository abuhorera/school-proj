@extends('layouts.app')

@section('title', 'Campus Workers Registry - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Filter Strip -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('front-office.campus-workers') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($filter) ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Workers ({{ $workers->count() }})
            </a>
            <a href="{{ route('front-office.campus-workers', ['filter' => 'on_campus']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filter == 'on_campus' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block mr-1"></span>
                On Campus Now
            </a>
            <a href="{{ route('front-office.campus-workers', ['filter' => 'unverified']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filter == 'unverified' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⚠️ Pending Police Check
            </a>
        </div>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addWorkerModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Register Worker</span>
            </button>
        </div>
    </div>

    <!-- Campus Workers Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-hard-hat text-amber-600"></i>
                <span class="text-xs font-bold text-slate-800">Campus Workers & Contractors ({{ $workers->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Child-safety security verification & daily campus presence</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Badge No</th>
                        <th class="p-3.5">Worker Name</th>
                        <th class="p-3.5">Trade / Category</th>
                        <th class="p-3.5">Contractor / Agency</th>
                        <th class="p-3.5">Badge Validity</th>
                        <th class="p-3.5 text-center">Police Verification</th>
                        <th class="p-3.5 text-center">Campus Presence</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workers as $index => $w)
                        <tr class="hover:bg-slate-50/80 transition {{ $w->is_on_campus ? 'bg-amber-50/20' : '' }}">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-mono font-bold text-indigo-600">
                                {{ $w->badge_number }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-50 text-amber-700 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr($w->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div>{{ $w->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono font-normal">{{ $w->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 font-semibold text-slate-700">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $w->category }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $w->agency_name ?? 'Independent' }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                <div class="text-[11px] font-medium">{{ date('d M Y', strtotime($w->valid_from)) }} &rarr; {{ date('d M Y', strtotime($w->valid_to)) }}</div>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($w->police_verified)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        ⚠️ Pending Check
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <form method="POST" action="{{ route('front-office.campus-workers.toggle-state', $w->id) }}" class="inline">
                                    @csrf
                                    @if($w->is_on_campus)
                                        <button type="submit" title="Click to Check Out" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-xs hover:bg-emerald-600 transition">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>
                                            ON CAMPUS
                                        </button>
                                    @else
                                        <button type="submit" title="Click to Check In" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 hover:bg-slate-300 transition">
                                            CHECKED OUT
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="p-3.5 text-right">
                                <form method="POST" action="{{ route('front-office.campus-workers.delete', $w->id) }}" onsubmit="return confirm('Delete worker record {{ $w->name }}?');" class="inline">
                                    @csrf
                                    <button type="submit" title="Delete Worker" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-400">
                                No campus workers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Register Campus Worker -->
<div id="addWorkerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-hard-hat text-amber-400"></i>
                <span>Register Campus Worker</span>
            </h4>
            <button onclick="document.getElementById('addWorkerModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.campus-workers.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Worker Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Worker Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number</label>
                    <input type="text" name="phone" placeholder="10-digit number" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Trade / Category <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" required placeholder="e.g. Electrician, Security, Plumber, Painter" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Agency / Contractor</label>
                    <input type="text" name="agency_name" placeholder="Agency Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Valid From <span class="text-rose-500">*</span></label>
                    <input type="date" name="valid_from" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Valid Until <span class="text-rose-500">*</span></label>
                    <input type="date" name="valid_to" required value="{{ date('Y-m-d', strtotime('+6 months')) }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <input type="checkbox" id="police_verified" name="police_verified" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="police_verified" class="text-xs text-slate-700 font-medium">Police Background Verification Cleared on Record</label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Internal Notes</label>
                <input type="text" name="notes" placeholder="RFID gate access pass issued..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addWorkerModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Register Worker
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

