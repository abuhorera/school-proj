@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-tags text-emerald-600"></i>
                <span>Income Heads Master</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Classification catalog for non-tuition revenues, rentals, grants, and miscellaneous receipts</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('accounts.income-heads.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Income Head</span>
            </a>
            <a href="{{ route('accounts.incomes.index') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition">
                <span>View Income Ledger &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Configured Income Heads ({{ $heads->count() }})</span>
            <span class="text-[11px] text-slate-400">Heads are used across revenue categorization & audit summaries</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">#</th>
                        <th class="py-3 px-4">Head Name</th>
                        <th class="py-3 px-4">Description / Scope</th>
                        <th class="py-3 px-4 text-center">Inflow Entries</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($heads as $idx => $h)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $h->name }}
                        </td>
                        <td class="py-3 px-4 text-slate-500">
                            {{ $h->description ?? 'Standard institutional income category' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $h->incomes_count ?? 0 }} Records
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <form action="{{ route('accounts.income-heads.destroy', $h->id) }}" method="POST" onsubmit="return confirm('Delete this income head?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            No income heads configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

