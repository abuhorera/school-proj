@extends('layouts.app')

@section('title', 'CBSE Mandatory Public Disclosure')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">CBSE Appendix IX Compliance</div>
            <h1 class="text-2xl font-bold text-slate-800">Mandatory Public Disclosure Vault</h1>
            <p class="text-xs text-slate-500 mt-1">Official certificates required under CBSE circular for public domain transparency.</p>
        </div>
        <button onclick="alert('Upload certificate')" class="bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Upload New Document</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Document / Certificate Name</th>
                    <th class="py-3 px-4">Issuing Authority</th>
                    <th class="py-3 px-4">Validity / Expiry</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">View / PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($disclosures as $d)
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-bold text-slate-800 flex items-center space-x-2.5">
                        <i class="fa-solid fa-file-pdf text-red-500 text-sm"></i>
                        <span>{{ $d['doc'] }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-700 font-medium">{{ $d['authority'] }}</td>
                    <td class="py-3 px-4 text-slate-600 font-mono">{{ $d['valid_till'] }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">{{ $d['status'] }}</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <button onclick="alert('Viewing verified PDF document')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-2.5 py-1 rounded text-xs">
                            View PDF
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

