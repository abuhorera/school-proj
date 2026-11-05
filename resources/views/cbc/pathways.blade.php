@extends('layouts.app')

@section('title', 'Pathways & Tracks - Senior Secondary CBC')

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
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-diagram-project"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Senior Secondary Pathways & Tracks</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure NEP 2020 multi-disciplinary learning pathways and specialized subject tracks for senior secondary</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addPathwayModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Add Pathway</span>
            </button>
            <button onclick="document.getElementById('addTrackModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
                <span>Add Specialized Track</span>
            </button>
        </div>
    </div>

    <!-- Pathways List -->
    <div class="space-y-6">
        @forelse($pathways as $pathway)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <!-- Pathway Header Banner -->
            <div class="p-5 bg-gradient-to-r from-slate-50 to-pink-50/30 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-700 flex items-center justify-center text-lg font-bold shadow-xs">
                        <i class="{{ $pathway->icon ?? 'fa-solid fa-graduation-cap' }}"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h2 class="text-base font-black text-slate-800">{{ $pathway->name }}</h2>
                            <span class="text-[10px] font-bold bg-pink-100 text-pink-800 px-2 py-0.5 rounded-full">{{ $pathway->tracks->count() }} Tracks</span>
                        </div>
                        @if($pathway->description)
                        <p class="text-xs text-slate-500 mt-0.5">{{ $pathway->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="openTrackModalForPathway('{{ $pathway->id }}')" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-indigo-600 border border-indigo-200 rounded-lg text-xs font-bold transition flex items-center space-x-1">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Add Track</span>
                    </button>
                    <form action="{{ route('cbc.pathways.destroy', $pathway->id) }}" method="POST" onsubmit="return confirm('Delete pathway and all associated tracks?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-500 transition" title="Delete Pathway">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tracks inside Pathway -->
            <div class="p-5">
                @if($pathway->tracks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pathway->tracks as $track)
                    <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/40 hover:bg-slate-50 transition space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-xs flex items-center space-x-2">
                                    <i class="fa-solid fa-angles-right text-indigo-500 text-[10px]"></i>
                                    <span>{{ $track->name }}</span>
                                </h3>
                                @if($track->description)
                                <p class="text-[11px] text-slate-500 mt-1">{{ $track->description }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Compulsory Subjects -->
                        @if(!empty($track->compulsory_subjects))
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Compulsory Core Subjects</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($track->compulsory_subjects as $subj)
                                <span class="text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/60 px-2 py-0.5 rounded-md">
                                    {{ $subj }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Elective Subjects -->
                        @if(!empty($track->elective_subjects))
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Elective Specializations</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($track->elective_subjects as $subj)
                                <span class="text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 px-2 py-0.5 rounded-md">
                                    {{ $subj }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-6 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-layer-group text-2xl text-slate-300 mb-1"></i>
                    <p>No tracks added under this pathway yet.</p>
                    <button onclick="openTrackModalForPathway('{{ $pathway->id }}')" class="mt-2 text-indigo-600 hover:underline font-bold text-xs inline-flex items-center space-x-1">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Add first track</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-2xl mx-auto mb-3">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">No Senior Secondary Pathways</h3>
            <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 mb-4">Define higher secondary career and academic pathways (STEM, Arts & Sports Science, Social Sciences & Humanities).</p>
            <button onclick="document.getElementById('addPathwayModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">
                <i class="fa-solid fa-plus mr-1"></i> Add Pathway
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Add Pathway -->
<div id="addPathwayModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Senior Secondary Pathway</h3>
            <button onclick="document.getElementById('addPathwayModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.pathways.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pathway Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Science, Technology, Engineering & Mathematics (STEM)" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Icon Class</label>
                <input type="text" name="icon" value="fa-solid fa-flask" placeholder="fa-solid fa-flask" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                <span class="text-[10px] text-slate-400">FontAwesome class (e.g. fa-solid fa-flask, fa-solid fa-palette)</span>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Brief overview of careers, domains, and learner outcomes in this pathway..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addPathwayModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Pathway</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Add Track -->
<div id="addTrackModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Add Specialized Track</h3>
            <button onclick="document.getElementById('addTrackModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.tracks.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Senior Pathway <span class="text-rose-500">*</span></label>
                <select id="modal_pathway_id" name="cbc_pathway_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    @foreach($pathways as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Track Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Pure Sciences, Engineering Sciences, Visual Arts" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Course outline and target higher-ed career fields..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Compulsory Subjects</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-32 overflow-y-auto p-2 border border-slate-200 rounded-xl">
                    @foreach($subjects as $sub)
                    <label class="flex items-center space-x-1.5 text-xs text-slate-700 cursor-pointer">
                        <input type="checkbox" name="compulsory_subjects[]" value="{{ $sub->name }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="truncate">{{ $sub->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Elective / Optional Subjects</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-32 overflow-y-auto p-2 border border-slate-200 rounded-xl">
                    @foreach($subjects as $sub)
                    <label class="flex items-center space-x-1.5 text-xs text-slate-700 cursor-pointer">
                        <input type="checkbox" name="elective_subjects[]" value="{{ $sub->name }}" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="truncate">{{ $sub->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addTrackModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs">Save Track</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTrackModalForPathway(pathwayId) {
    const select = document.getElementById('modal_pathway_id');
    if (select) {
        select.value = pathwayId;
    }
    document.getElementById('addTrackModal').classList.remove('hidden');
}
</script>
@endsection

