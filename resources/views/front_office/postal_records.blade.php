@extends('layouts.app')

@section('title', 'Postal Records - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center space-x-1">
            <a href="{{ route('front-office.postal-records') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($typeFilter) ? 'bg-cyan-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Records
            </a>
            <a href="{{ route('front-office.postal-records', ['type' => 'Dispatch']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $typeFilter == 'Dispatch' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-paper-plane mr-1 text-[10px]"></i> Dispatch (Outward)
            </a>
            <a href="{{ route('front-office.postal-records', ['type' => 'Receive']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $typeFilter == 'Receive' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <i class="fa-solid fa-inbox mr-1 text-[10px]"></i> Receive (Inward)
            </a>
        </div>

        <button onclick="document.getElementById('addPostalModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Postal Record</span>
        </button>
    </div>

    <!-- Postal Records Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-envelope-open-text text-purple-600"></i>
                <span class="text-xs font-bold text-slate-800">Inward & Outward Dispatch Registry ({{ $records->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Postal tracking, consignment receipts & speed post numbers</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Type</th>
                        <th class="p-3.5">From</th>
                        <th class="p-3.5">To</th>
                        <th class="p-3.5">Reference / Consignment #</th>
                        <th class="p-3.5">Date</th>
                        <th class="p-3.5">Notes & Context</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $index => $rec)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-bold">
                                @if($rec->type == 'Dispatch')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px] mr-1"></i> Dispatch
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-arrow-down-left-and-up-right-to-center text-[9px] mr-1"></i> Receive
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                {{ $rec->from_name }}
                            </td>
                            <td class="p-3.5 font-semibold text-slate-700">
                                <div>{{ $rec->to_name }}</div>
                                @if($rec->to_address)
                                    <div class="text-[10px] text-slate-400 font-normal truncate max-w-xs">{{ $rec->to_address }}</div>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span class="font-mono text-[10px] font-semibold bg-slate-100 px-2.5 py-1 rounded border border-slate-200 text-slate-800">
                                    {{ $rec->reference_number }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ date('d M Y', strtotime($rec->date)) }}
                            </td>
                            <td class="p-3.5 text-slate-600 max-w-xs truncate">
                                {{ $rec->notes ?? '—' }}
                            </td>
                            <td class="p-3.5 text-right">
                                <form method="POST" action="{{ route('front-office.postal-records.delete', $rec->id) }}" onsubmit="return confirm('Delete postal record?');" class="inline">
                                    @csrf
                                    <button type="submit" title="Delete Record" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-400">
                                No postal records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Postal Record -->
<div id="addPostalModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-envelope-open-text text-purple-400"></i>
                <span>Add Postal Record</span>
            </h4>
            <button onclick="document.getElementById('addPostalModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.postal-records.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Record Type <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="Dispatch">Dispatch (Outward)</option>
                        <option value="Receive">Receive (Inward)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">From (Sender) <span class="text-rose-500">*</span></label>
                    <input type="text" name="from_name" required placeholder="Sender Name / Entity" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">To (Recipient) <span class="text-rose-500">*</span></label>
                    <input type="text" name="to_name" required placeholder="Recipient Name / Office" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Destination Address / Office</label>
                <textarea name="to_address" rows="2" placeholder="Full postal address..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Reference / Consignment / Tracking #</label>
                <input type="text" name="reference_number" placeholder="e.g. SP-IN-9821039" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Internal Notes</label>
                <input type="text" name="notes" placeholder="e.g. CBSE registration packets, trophies" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addPostalModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition">
                    Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

