@extends('layouts.app')

@section('title', 'Fees Discount & Concessions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-tag"></i>
                <span>Waivers, Concessions & Scholarships</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fees Discount Master</h1>
            <p class="text-xs text-slate-500 mt-1">Configure merit scholarships, sibling concessions, staff ward waivers, and early-bird discounts.</p>
        </div>
        <button onclick="alert('Add Discount modal')" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Discount Rule</span>
        </button>
    </div>

    <!-- Discounts Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Active Concession Schemes (Total: {{ $discounts->count() }})</h3>
            <span class="text-xs text-purple-600 font-semibold">Auto-Applied at POS</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Discount Scheme Name</th>
                        <th class="py-3 px-4">Discount Code</th>
                        <th class="py-3 px-4">Calculation Type</th>
                        <th class="py-3 px-4 text-right">Amount / Percentage</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($discounts as $idx => $d)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $d->name }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-purple-700">
                            <span class="bg-purple-50 px-2 py-0.5 rounded border border-purple-200">{{ $d->code }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded font-semibold text-[10px] {{ $d->type == 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ ucfirst($d->type) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-extrabold text-slate-800">
                            {{ $d->type == 'percentage' ? $d->amount . '%' : '₹' . number_format($d->amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-500">{{ $d->description }}</td>
                        <td class="py-3 px-4 text-right">
                            <button class="text-purple-600 hover:text-purple-800 font-semibold mr-2">Edit</button>
                            <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

