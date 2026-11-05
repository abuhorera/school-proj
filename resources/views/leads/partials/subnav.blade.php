<!-- Lead Management Top Sub-Navigation (Matching Demo MultiSchool ERP lead-tabs) -->
<div class="bg-white border-b border-slate-200 -mt-6 -mx-6 px-6 pt-4 mb-6 sticky top-0 z-10 shadow-xs">
    <div class="flex items-center justify-between pb-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-base">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Lead & Admissions CRM</h2>
                <p class="text-xs text-slate-500">Admissions Pipeline • Lead Capture, Kanban Board, Follow-ups, Tasks & Conversions</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('leads.board') }}" class="px-3 py-1.5 text-xs font-semibold text-cyan-700 bg-cyan-50 hover:bg-cyan-100 rounded-lg border border-cyan-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-chart-simple text-cyan-600"></i>
                <span>Pipeline Board</span>
            </a>
            <button onclick="document.getElementById('newLeadModal').classList.remove('hidden')" class="px-3 py-1.5 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Lead</span>
            </button>
        </div>
    </div>

    <!-- Scrollable Tab Bar -->
    <div class="flex items-center space-x-1 overflow-x-auto custom-scroll -mb-px text-xs font-medium border-t border-slate-100 pt-1">
        <a href="{{ route('leads.dashboard') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ (request()->routeIs('leads.dashboard') || request()->routeIs('leads.index')) ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Lead Dashboard</span>
        </a>
        <a href="{{ route('leads.board') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('leads.board') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>Pipeline Board (Kanban)</span>
            <span class="bg-cyan-100 text-cyan-800 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">PIPELINE</span>
        </a>
        <a href="{{ route('leads.list') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('leads.list') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-address-book"></i>
            <span>All Leads Directory</span>
        </a>
        <a href="{{ route('leads.tasks') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('leads.tasks') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-phone-volume"></i>
            <span>Follow-up Tasks</span>
            <span class="bg-amber-100 text-amber-800 text-[9px] px-1.5 py-0.2 rounded-full font-bold ml-1">ACTIVE</span>
        </a>
        <a href="{{ route('leads.settings') }}" class="px-3 py-2.5 border-b-2 whitespace-nowrap transition flex items-center space-x-1.5 {{ request()->routeIs('leads.settings') ? 'border-cyan-600 text-cyan-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
            <i class="fa-solid fa-sliders"></i>
            <span>Sources & Stages</span>
        </a>
    </div>
</div>

<!-- Global Add Lead Modal -->
<div id="newLeadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold">New Admission Lead / Enquiry</h3>
                    <p class="text-[11px] text-slate-400">Capture candidate inquiry for admission pipeline</p>
                </div>
            </div>
            <button onclick="document.getElementById('newLeadModal').classList.add('hidden')" class="text-slate-400 hover:text-white transition p-1 text-sm cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('leads.store') }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Student / Candidate Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_name" required placeholder="e.g. Rahul Sharma" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Parent / Guardian Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="parent_name" required placeholder="e.g. Vikram Sharma" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Contact Phone <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="e.g. 9876543210" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. parent@example.com" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Target Class / Standard</label>
                    <select name="school_class_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        <option value="">-- Select Class --</option>
                        @if(isset($schoolClasses))
                            @foreach($schoolClasses as $sc)
                                <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Lead Source Channel</label>
                    <select name="lead_source_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        <option value="">-- Select Source --</option>
                        @if(isset($sources))
                            @foreach($sources as $src)
                                <option value="{{ $src->id }}">{{ $src->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Initial Pipeline Stage</label>
                    <select name="lead_stage_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        @if(isset($stages))
                            @foreach($stages as $stg)
                                <option value="{{ $stg->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $stg->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Next Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ date('Y-m-d', strtotime('+2 days')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Enquiry Notes / Candidate Interests</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Inquiring for standard 9 admission, previously in ICSE school..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-cyan-500 focus:bg-white transition"></textarea>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('newLeadModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition font-medium">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-semibold shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Save & Log Lead</span>
                </button>
            </div>
        </form>
    </div>
</div>

