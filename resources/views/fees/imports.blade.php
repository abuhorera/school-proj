@extends('layouts.app')

@section('title', 'Fee Import Center')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-file-import"></i>
                <span>Data Migration & Opening Balances</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Import Center <span class="bg-indigo-900/40 text-indigo-700 text-xs px-2 py-0.5 rounded font-bold">NEW</span></h1>
            <p class="text-xs text-slate-500 mt-1">Bulk upload student opening arrears, advance fee deposits, and historical ledger carry forwards from Excel / CSV.</p>
        </div>
        <button onclick="alert('Downloading sample Fee Import CSV template')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-download"></i>
            <span>Download CSV Template</span>
        </button>
    </div>

    <!-- Upload Box -->
    <div class="bg-white p-6 rounded-2xl border-2 border-dashed border-slate-300 shadow-sm text-center space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mx-auto">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm text-slate-800">Upload Opening Balances & Arrears Spreadsheet</h3>
            <p class="text-xs text-slate-500 mt-0.5">Supports CSV, XLSX up to 10MB (max 5,000 student records per batch)</p>
        </div>
        <div class="max-w-xs mx-auto">
            <input type="file" class="text-xs file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>
        <button onclick="alert('Upload simulated: 10 student records validated with 0 errors.')" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-5 py-2 rounded-xl shadow transition">
            Validate & Import Records
        </button>
    </div>

    <!-- CSV Format Specification Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Expected CSV File Column Specification</h3>
            <span class="text-xs text-slate-500">Headers must match exact naming</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-sans">admission_no *</th>
                        <th class="py-3 px-4 font-sans text-right">arrears_amount</th>
                        <th class="py-3 px-4 font-sans text-right">advance_amount</th>
                        <th class="py-3 px-4 font-sans">note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-bold text-indigo-700">ADM-2026-001</td>
                        <td class="py-3 px-4 text-right text-red-600 font-bold">4500.00</td>
                        <td class="py-3 px-4 text-right text-slate-400">0.00</td>
                        <td class="py-3 px-4 text-slate-600 font-sans">Previous session bus fee arrears</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-bold text-indigo-700">ADM-2026-002</td>
                        <td class="py-3 px-4 text-right text-slate-400">0.00</td>
                        <td class="py-3 px-4 text-right text-emerald-600 font-bold">2500.00</td>
                        <td class="py-3 px-4 text-slate-600 font-sans">Advance deposit for Term 4</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

