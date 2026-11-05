@extends('layouts.app')

@section('title', 'Salary Templates - Human Resource')

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
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Master Compensation & Salary Templates</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Predefined institutional pay scales, statutory EPF/ESI deduction rules, and allowance bands</p>
        </div>
        <div>
            <button onclick="document.getElementById('addTemplateModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Salary Template</span>
            </button>
        </div>
    </div>

    <!-- Salary Templates Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Configured Salary Structures ({{ $templates->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Template Name</th>
                        <th class="py-3 px-4">Basic Salary</th>
                        <th class="py-3 px-4">Allowances (HRA + DA + Conv)</th>
                        <th class="py-3 px-4">Deductions (EPF + ESI)</th>
                        <th class="py-3 px-4">Net Monthly Pay</th>
                        <th class="py-3 px-4">Active Staff</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($templates as $index => $t)
                    @php
                        $allwTotal = $t->hra + $t->da + $t->conveyance + $t->medical_allowance;
                        $dedTotal = $t->epf_deduction + $t->esi_deduction + $t->professional_tax;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $t->name }}</div>
                            <span class="text-[10px] text-slate-400">Institutional Grade Scale</span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800 font-mono">
                            ₹{{ number_format($t->basic_salary, 2) }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-emerald-700 font-mono">
                            +₹{{ number_format($allwTotal, 2) }}
                            <div class="text-[10px] text-slate-400 font-normal">HRA: {{ $t->hra }}, DA: {{ $t->da }}</div>
                        </td>
                        <td class="py-3 px-4 font-semibold text-rose-600 font-mono">
                            -₹{{ number_format($dedTotal, 2) }}
                            <div class="text-[10px] text-slate-400 font-normal">EPF: {{ $t->epf_deduction }}, ESI: {{ $t->esi_deduction }}</div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900 font-mono text-sm">
                            ₹{{ number_format($t->net_salary, 2) }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-700">
                                {{ $t->staff_salaries_count }} employees
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="{{ route('staff.salary-templates.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete this salary template?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Template">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">No salary templates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Salary Template -->
<div id="addTemplateModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="font-bold text-slate-800 text-sm">Add New Salary Structure Template</h3>
            <button onclick="document.getElementById('addTemplateModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('staff.salary-templates.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Template Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Primary Teacher Grade B Pay Scale" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Basic Salary (₹) <span class="text-rose-500">*</span></label>
                <input type="number" name="basic_salary" value="35000" min="1000" required class="w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">House Rent Allowance (HRA)</label>
                    <input type="number" name="hra" value="7000" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Dearness Allowance (DA)</label>
                    <input type="number" name="da" value="3500" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Conveyance Allowance</label>
                    <input type="number" name="conveyance" value="2000" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Medical Allowance</label>
                    <input type="number" name="medical_allowance" value="1500" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">EPF Deduction</label>
                    <input type="number" name="epf_deduction" value="1800" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ESI Deduction</label>
                    <input type="number" name="esi_deduction" value="600" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Prof. Tax</label>
                    <input type="number" name="professional_tax" value="200" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addTemplateModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Template</button>
            </div>
        </form>
    </div>
</div>
@endsection

