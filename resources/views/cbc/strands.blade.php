@extends('layouts.app')

@section('title', 'Curriculum Strands & Learning Outcomes - CBC Academics')

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

    <!-- Header bar with Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Curriculum Strands & Learning Outcomes</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Define learning areas, syllabus strands, and demonstrable learning outcome criteria (NEP 2020 / CBC)</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addStrandModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Curriculum Strand</span>
            </button>
            <button onclick="document.getElementById('addOutcomeModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-bullseye"></i>
                <span>Add Learning Outcome</span>
            </button>
        </div>
    </div>

    <!-- Main Grid: Subject Selector & Strands List -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left: Subject Selector List -->
        <div class="lg:col-span-1 space-y-3">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center justify-between">
                    <span>Subjects / Domains</span>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-[10px]">{{ $subjects->count() }}</span>
                </h3>
                <div class="space-y-1.5 max-h-[600px] overflow-y-auto pr-1">
                    @forelse($subjects as $sub)
                    <a href="{{ route('cbc.strands', ['subject_id' => $sub->id]) }}" 
                       class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-between {{ $selectedSubjectId == $sub->id ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                        <div class="flex items-center space-x-2 truncate">
                            <i class="fa-solid fa-book {{ $selectedSubjectId == $sub->id ? 'text-indigo-200' : 'text-slate-400' }}"></i>
                            <span class="truncate">{{ $sub->name }}</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded {{ $selectedSubjectId == $sub->id ? 'bg-indigo-700 text-indigo-100' : 'bg-white text-slate-400 border border-slate-200' }}">
                            {{ $sub->code ?? 'SUB' }}
                        </span>
                    </a>
                    @empty
                    <p class="text-xs text-slate-400 italic p-2">No subjects found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-2xl border border-indigo-100/60 text-xs">
                <div class="flex items-center space-x-2 text-indigo-900 font-bold mb-1">
                    <i class="fa-solid fa-circle-info text-indigo-600"></i>
                    <span>CBC Curriculum Structure</span>
                </div>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    A <strong>Strand</strong> represents a broad learning domain or theme (e.g. <em>Number & Operations</em>). Under each strand, specify targeted <strong>Learning Outcomes</strong> that are assessed using rubrics.
                </p>
            </div>
        </div>

        <!-- Right: Strands & Outcomes for Selected Subject -->
        <div class="lg:col-span-3 space-y-4">
            @if($selectedSubject)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Selected Subject</span>
                    <h2 class="text-base font-black text-slate-800 mt-1">{{ $selectedSubject->name }} ({{ $selectedSubject->code ?? 'N/A' }})</h2>
                </div>
                <div class="flex items-center space-x-3 text-xs text-slate-500">
                    <div><span class="font-bold text-slate-800">{{ $strands->count() }}</span> Strands</div>
                    <div>•</div>
                    <div><span class="font-bold text-slate-800">{{ $strands->sum(fn($s) => $s->outcomes->count()) }}</span> Total Outcomes</div>
                </div>
            </div>

            <!-- Strands List Accordions -->
            <div class="space-y-4">
                @forelse($strands as $index => $strand)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                    <!-- Strand Header -->
                    <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-black text-xs flex items-center justify-center">
                                {{ $strand->sort_order ?? ($index + 1) }}
                            </span>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-bold text-slate-800 text-sm">{{ $strand->name }}</h3>
                                    <span class="text-[10px] bg-slate-200 text-slate-700 font-mono px-2 py-0.5 rounded font-semibold">{{ $strand->code }}</span>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full">{{ $strand->outcomes->count() }} Outcomes</span>
                                </div>
                                @if($strand->description)
                                <p class="text-xs text-slate-500 mt-0.5">{{ $strand->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="openOutcomeModalForStrand('{{ $strand->id }}', '{{ addslashes($strand->name) }}')" class="px-2.5 py-1.5 bg-white hover:bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center space-x-1">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add Outcome</span>
                            </button>
                            <form action="{{ route('cbc.strands.destroy', $strand->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this strand and its outcomes?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Delete Strand">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Outcomes List inside Strand -->
                    <div class="p-4">
                        @if($strand->outcomes->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($strand->outcomes as $outcome)
                            <div class="py-3 flex items-start justify-between hover:bg-slate-50/60 px-2 rounded-xl transition">
                                <div class="flex items-start space-x-3">
                                    <div class="mt-1">
                                        <i class="fa-solid fa-check-circle text-emerald-500 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded">{{ $outcome->code }}</span>
                                            <h4 class="font-semibold text-slate-800 text-xs">{{ $outcome->name }}</h4>
                                        </div>
                                        @if($outcome->description)
                                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $outcome->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('cbc.outcomes.destroy', $outcome->id) }}" method="POST" onsubmit="return confirm('Remove this learning outcome?')" class="inline ml-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 transition" title="Delete Outcome">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="py-6 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-clipboard-list text-2xl text-slate-300 mb-2"></i>
                            <p>No learning outcomes defined for this strand yet.</p>
                            <button onclick="openOutcomeModalForStrand('{{ $strand->id }}', '{{ addslashes($strand->name) }}')" class="mt-2 text-indigo-600 hover:underline font-bold text-xs inline-flex items-center space-x-1">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Add first learning outcome</span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">No Curriculum Strands Created</h3>
                    <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 mb-4">Start by adding a curriculum strand for {{ $selectedSubject->name }} to structure competencies and demonstrable learning outcomes.</p>
                    <button onclick="document.getElementById('addStrandModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                        <i class="fa-solid fa-plus mr-1"></i> Add Strand for {{ $selectedSubject->name }}
                    </button>
                </div>
                @endforelse
            </div>
            @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center">
                <p class="text-slate-500 text-sm">Select a subject from the left panel to manage strands & learning outcomes.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Add Curriculum Strand -->
<div id="addStrandModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Curriculum Strand</h3>
            <button onclick="document.getElementById('addStrandModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.strands.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Subject / Domain <span class="text-rose-500">*</span></label>
                <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ $selectedSubjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code ?? 'SUB' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Strand Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Numbers & Operations, Living Things" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Strand Code</label>
                    <input type="text" name="code" placeholder="e.g. MATH-NUM" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="1" min="1" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description / Focus Area</label>
                <textarea name="description" rows="3" placeholder="Brief outline of concepts covered under this strand..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addStrandModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Strand</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Add Learning Outcome -->
<div id="addOutcomeModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Learning Outcome</h3>
            <button onclick="document.getElementById('addOutcomeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.outcomes.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Curriculum Strand <span class="text-rose-500">*</span></label>
                <select id="modal_strand_id" name="cbc_strand_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden">
                    @foreach($strands as $str)
                    <option value="{{ $str->id }}">{{ $str->name }} ({{ $str->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Outcome Code</label>
                <input type="text" name="code" placeholder="e.g. LO-MTH-01" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Demonstrable Learning Outcome <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Solves multi-step real-world problems with fractions" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Rubric Criteria & Observable Evidence</label>
                <textarea name="description" rows="3" placeholder="What specific evidence indicates mastery? (e.g. Demonstrates correct step-by-step logic, verifies answer...)" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-hidden"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addOutcomeModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Outcome</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOutcomeModalForStrand(strandId, strandName) {
    const select = document.getElementById('modal_strand_id');
    if (select) {
        select.value = strandId;
    }
    document.getElementById('addOutcomeModal').classList.remove('hidden');
}
</script>
@endsection

