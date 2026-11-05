@extends('layouts.app')

@section('title', 'Fee Types Catalog')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-list-check"></i>
                <span>Master Billing Heads</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Types Catalog</h1>
            <p class="text-xs text-slate-500 mt-1">Catalog of specific fee heads (Tuition, Lab, Transport, Library, Sports, Examination).</p>
        </div>
        <button onclick="alert('Add Fee Type modal')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Fee Type</span>
        </button>
    </div>

    <!-- Fee Types Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">All Configured Fee Heads (Total: {{ $types->count() }})</h3>
            <span class="text-xs text-slate-500">Linked to General Accounting Ledger</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Fee Head Name</th>
                        <th class="py-3 px-4">Fee Code</th>
                        <th class="py-3 px-4">Parent Fee Group</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($types as $idx => $ft)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $ft->name }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-blue-700">
                            <span class="bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $ft->code }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded text-[10px]">
                                {{ $ft->feeGroup->name ?? 'Standard Academic' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500">Standard monthly / term-wise curriculum charge</td>
                        <td class="py-3 px-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold mr-2">Edit</button>
                            <button class="text-slate-400 hover:text-slate-600 font-medium">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

