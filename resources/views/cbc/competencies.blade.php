@extends('layouts.app')

@section('title', 'Core Competencies Master - CBC Academics')

@section('content')
<div class="space-y-6">
    @include('cbc.partials.subnav')

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

    <!-- Header & Action Row -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-lightbulb"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Core Competencies Master</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">21st-century foundational skills and observable behavioral indicators based on NEP 2020 & CBC benchmarks</p>
        </div>
        <div class="flex items-center space-x-2">
            <form action="{{ route('cbc.competencies.seed') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                    <i class="fa-solid fa-rotate text-xs text-slate-500"></i>
                    <span>Reload 7 Defaults</span>
                </button>
            </form>
            <button onclick="document.getElementById('addCompetencyModal').classList.remove('hidden')" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Competency</span>
            </button>
        </div>
    </div>

    <!-- Competencies Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($competencies as $comp)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between gap-2 mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-violet-700 bg-violet-50 px-2.5 py-0.5 rounded-full border border-violet-100">
                        {{ $comp->category ?? 'Core Competency' }}
                    </span>
                    <form action="{{ route('cbc.competencies.destroy', $comp->id) }}" method="POST" onsubmit="return confirm('Remove this competency?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 transition" title="Delete Competency">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>

                <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center space-x-2">
                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                    <span>{{ $comp->name }}</span>
                </h3>

                @if($comp->description)
                <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $comp->description }}</p>
                @endif

                <!-- Observable Indicators -->
                <div class="space-y-1.5 pt-3 border-t border-slate-100">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Observable Behavioral Indicators</h4>
                    @if(is_array($comp->indicators) && count($comp->indicators) > 0)
                    <ul class="space-y-1.5 mt-2">
                        @foreach($comp->indicators as $indicator)
                        <li class="flex items-start space-x-2 text-xs text-slate-700">
                            <i class="fa-solid fa-check text-emerald-500 text-[10px] mt-1 shrink-0"></i>
                            <span class="text-[11px] leading-snug">{{ $indicator }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-[11px] text-slate-400 italic">No specific indicators logged.</p>
                    @endif
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span class="flex items-center space-x-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>NEP 2020 Aligned</span>
                </span>
                <span class="font-mono text-[10px]">ID: #{{ $comp->id }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-violet-50 text-violet-500 flex items-center justify-center text-2xl mx-auto mb-3">
                <i class="fa-solid fa-lightbulb"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">No Competencies Defined</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 mb-4">Click below to automatically load the 7 NEP 2020 21st-century core competencies.</p>
            <form action="{{ route('cbc.competencies.seed') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-xs">
                    <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Load Default 7 Competencies
                </button>
            </form>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Add Core Competency -->
<div id="addCompetencyModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Core Competency</h3>
            <button onclick="document.getElementById('addCompetencyModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.competencies.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Competency Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Critical Thinking & Problem Solving" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Category / Domain</label>
                <input type="text" name="category" placeholder="e.g. Cognitive Skills, Socio-Emotional, Digital Fluency" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief definition of the competency..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-violet-500"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Observable Indicators (One per line)</label>
                <textarea name="indicators" rows="4" placeholder="Analyzes situations logically&#10;Identifies root causes&#10;Proposes innovative alternatives" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-violet-500"></textarea>
                <span class="text-[10px] text-slate-400">Enter each indicator on a new line.</span>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addCompetencyModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Competency</button>
            </div>
        </form>
    </div>
</div>
@endsection

