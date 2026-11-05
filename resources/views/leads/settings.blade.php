@extends('layouts.app')

@section('title', 'Lead Sources & Pipeline Stages Settings')

@section('content')
<div class="space-y-6">
    @include('leads.partials.subnav')

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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 1. Pipeline Stages Configuration (Left Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Pipeline Stages Configuration</h3>
                        <p class="text-[11px] text-slate-400">Order, visual stage colors, and pipeline steps</p>
                    </div>
                    <span class="text-[10px] font-bold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full">{{ $stages->count() }} STAGES</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($stages as $stage)
                    <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition text-xs">
                        <div class="flex items-center space-x-3">
                            <span class="w-4 h-4 rounded-full border border-slate-200 shadow-2xs shrink-0" style="background-color: {{ $stage->color }}"></span>
                            <div>
                                <span class="font-bold text-slate-800">{{ $stage->name }}</span>
                                <span class="text-slate-400 text-[10px] ml-1.5">(Order: {{ $stage->sort_order }})</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                {{ $stage->leads_count }} Leads
                            </span>
                            <button onclick="editStage({{ $stage->id }}, '{{ addslashes($stage->name) }}', '{{ $stage->color }}', {{ $stage->sort_order }})" class="text-cyan-600 hover:text-cyan-800 text-xs font-semibold">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Add Stage Form -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Add New Pipeline Stage</h4>
                <form action="{{ route('leads.settings.stages.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Stage Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Scholarship Assessment" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Stage Color Theme</label>
                            <input type="color" name="color" value="#06b6d4" class="w-full h-9 p-1 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Sort Order Position</label>
                            <input type="number" name="sort_order" value="{{ $stages->count() + 1 }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-bold transition shadow-xs">
                        Create Pipeline Stage
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. Lead Source Channels Configuration (Right Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Lead Acquisition Source Channels</h3>
                        <p class="text-[11px] text-slate-400">Marketing & inquiry capture origin sources</p>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $sources->count() }} CHANNELS</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($sources as $source)
                    <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition text-xs">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <span class="font-bold text-slate-800">{{ $source->name }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700">
                            {{ $source->leads_count }} Inquiries
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Add Source Form -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Add Acquisition Source Channel</h4>
                <form action="{{ route('leads.settings.sources.store') }}" method="POST" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Source Channel Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Instagram Sponsored Ads, Education Fair 2026..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    </div>
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-xs">
                        Add Acquisition Channel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Stage Modal -->
<div id="editStageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400">Edit Pipeline Stage</h3>
            <button onclick="document.getElementById('editStageModal').classList.add('hidden')" class="text-slate-400 hover:text-white transition p-1 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editStageForm" method="POST" class="p-5 space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Stage Name</label>
                <input type="text" id="editStageName" name="name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Color Theme</label>
                <input type="color" id="editStageColor" name="color" class="w-full h-9 p-1 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Sort Order</label>
                <input type="number" id="editStageSort" name="sort_order" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editStageModal').classList.add('hidden')" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-semibold transition">Update Stage</button>
            </div>
        </form>
    </div>
</div>

<script>
function editStage(id, name, color, order) {
    document.getElementById('editStageName').value = name;
    document.getElementById('editStageColor').value = color;
    document.getElementById('editStageSort').value = order;
    document.getElementById('editStageForm').action = "/school/leads/settings/stages/" + id;
    document.getElementById('editStageModal').classList.remove('hidden');
}
</script>
@endsection

