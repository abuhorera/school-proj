@extends('layouts.app')

@section('title', 'Visitor Book - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('front-office.visitor-logs') }}" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search visitor, phone, purpose..." class="text-xs rounded-lg border border-slate-300 pl-8 pr-3 py-1.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            @if($search)
                <a href="{{ route('front-office.visitor-logs') }}" class="text-xs text-rose-600 hover:text-rose-800 font-medium px-2 py-1">
                    Clear Filter
                </a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addVisitorModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>New Visitor</span>
            </button>
            <a href="{{ route('front-office.gate-terminal') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
                <i class="fa-solid fa-barcode text-emerald-600"></i>
                <span>Gate Control</span>
            </a>
        </div>
    </div>

    <!-- Visitors Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-address-book text-emerald-600"></i>
                <span class="text-xs font-bold text-slate-800">Campus Visitor Register ({{ $visitors->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Real-time gate check-in & check-out logs</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Visitor Name</th>
                        <th class="p-3.5">Phone Number</th>
                        <th class="p-3.5">Purpose of Visit</th>
                        <th class="p-3.5">Person to Meet</th>
                        <th class="p-3.5">ID Card #</th>
                        <th class="p-3.5">In-Time</th>
                        <th class="p-3.5">Out-Time</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($visitors as $index => $vis)
                        <tr class="hover:bg-slate-50/80 transition {{ !$vis->out_time ? 'bg-emerald-50/30' : '' }}">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr($vis->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs">{{ $vis->name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 font-mono text-slate-700">
                                {{ $vis->phone }}
                            </td>
                            <td class="p-3.5 font-medium text-slate-800">
                                {{ $vis->purpose }}
                            </td>
                            <td class="p-3.5 text-slate-700">
                                {{ $vis->person_to_meet }}
                            </td>
                            <td class="p-3.5">
                                <span class="font-mono text-[10px] bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $vis->id_card_number ?? 'V-PASS' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">
                                {{ date('h:i A, d M', strtotime($vis->in_time)) }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $vis->out_time ? date('h:i A, d M', strtotime($vis->out_time)) : '—' }}
                            </td>
                            <td class="p-3.5 text-center">
                                @if(!$vis->out_time)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>
                                        ON CAMPUS
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                        Checked Out
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    @if(!$vis->out_time)
                                        <form method="POST" action="{{ route('front-office.visitor-logs.out', $vis->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded border border-rose-200 transition">
                                                Mark Out
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('front-office.visitor-logs.delete', $vis->id) }}" onsubmit="return confirm('Delete visitor entry?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Entry" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-6 text-center text-slate-400">
                                No visitor records logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Visitor Entry -->
<div id="addVisitorModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-address-book text-emerald-400"></i>
                <span>New Visitor Check-In</span>
            </h4>
            <button onclick="document.getElementById('addVisitorModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.visitor-logs.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Visitor Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="visitor_name" required placeholder="e.g. Ramesh Patel" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="10-digit phone" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Card / Badge #</label>
                    <input type="text" name="id_card_number" placeholder="e.g. V-904" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Purpose of Visit <span class="text-rose-500">*</span></label>
                <input type="text" name="purpose" required placeholder="e.g. Parent Teacher Meeting, Book Delivery, Maintenance" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Person / Department to Meet <span class="text-rose-500">*</span></label>
                <input type="text" name="person_to_meet" required placeholder="e.g. Principal Office, Class 10 Coordinator, Accounts" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addVisitorModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition">
                    Authorize & Check In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

