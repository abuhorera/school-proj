@extends('layouts.app')

@section('title', 'Broadcast Templates')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Communicate</span>
                <span>/</span>
                <a href="{{ route('school.communicate.broadcast') }}" class="hover:text-blue-600 transition">Broadcast</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Templates</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-file-code"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Broadcast Templates</h1>
                    <p class="text-xs text-slate-500">Pre-approved DLT SMS and Meta WhatsApp templates with dynamic variable tags.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.communicate.broadcast') }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Broadcast</span>
            </a>
            <button type="button" onclick="openCreateTemplateModal()" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Add Template</span>
            </button>
        </div>
    </div>

    <!-- Search & Channel Tabs Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Channel Filter Tabs -->
        <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
            <button type="button" onclick="filterTemplateChannel('all')" id="tabBtnAll" class="px-3.5 py-1.5 rounded-md transition bg-white text-blue-600 shadow-xs">
                All ({{ $templates->count() }})
            </button>
            <button type="button" onclick="filterTemplateChannel('sms')" id="tabBtnSms" class="px-3.5 py-1.5 rounded-md transition text-slate-600 hover:text-slate-900">
                <i class="fa-solid fa-comment-sms mr-1"></i> SMS ({{ $smsTemplates->count() }})
            </button>
            <button type="button" onclick="filterTemplateChannel('whatsapp')" id="tabBtnWa" class="px-3.5 py-1.5 rounded-md transition text-slate-600 hover:text-slate-900">
                <i class="fa-brands fa-whatsapp mr-1"></i> WhatsApp ({{ $waTemplates->count() }})
            </button>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('school.communicate.templates') }}" class="w-full sm:w-72">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, wording or DLT ID…" class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
            </div>
        </form>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="templatesContainer">
        @forelse($templates as $tpl)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3 template-card transition hover:border-blue-300" data-channel="{{ $tpl->channel }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    @if($tpl->channel === 'sms')
                        <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-comment-sms"></i>
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-900">{{ $tpl->name }}</div>
                            <span class="text-[10px] text-blue-600 font-semibold bg-blue-50 px-1.5 py-0.2 rounded uppercase">SMS Template</span>
                        </div>
                    @else
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                            <i class="fa-brands fa-whatsapp"></i>
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-900">{{ $tpl->name }}</div>
                            <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-1.5 py-0.2 rounded uppercase">WhatsApp Pro</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                        {{ $tpl->category }}
                    </span>
                    <button type="button" onclick="editTemplateModal({{ json_encode($tpl) }})" class="p-1.5 text-slate-400 hover:text-blue-600 rounded">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </button>
                    <form action="{{ route('school.communicate.templates.destroy', $tpl->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this template?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Approved Body -->
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-mono leading-relaxed select-all">
                {{ $tpl->body }}
            </div>

            <!-- Meta info (DLT id, status) -->
            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                @if($tpl->dlt_template_id)
                    <div class="font-mono">
                        <span class="text-slate-400">DLT ID:</span> <strong class="text-slate-700">{{ $tpl->dlt_template_id }}</strong>
                    </div>
                @else
                    <span class="text-slate-400 italic">No DLT ID needed</span>
                @endif

                <span class="flex items-center text-emerald-600 font-semibold">
                    <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Active in composer
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-2 py-12 text-center text-slate-400 bg-white rounded-xl border border-slate-200">
            <i class="fa-solid fa-file-code text-4xl mb-3 block text-slate-300"></i>
            <p class="font-semibold text-slate-600 text-sm">No templates found.</p>
            <p class="text-xs text-slate-400 mt-1">Click "Add Template" above to register approved SMS &amp; WhatsApp message formats.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Add / Edit Template (Matches live demo #btModal) -->
<div id="btModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider" id="btModalTitle">Add template</h3>
            <button onclick="closeModal('btModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('school.communicate.templates.store') }}" method="POST" id="templateModalForm" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="btMethod" value="POST">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Channel <span class="text-rose-500">*</span></label>
                    <select name="channel" id="btChannel" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                        <option value="sms">SMS</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Template Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="btName" required placeholder="e.g. Holiday notice, Fee reminder, PTM invite" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Approved Wording <span class="text-rose-500">*</span></label>
                <textarea name="body" id="btBody" rows="4" required placeholder="Dear Parent, school will remain closed on @{{date_text}} due to @{{reason}}. - @{{school_name}}" class="w-full p-3 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none font-mono"></textarea>
                <small class="text-[10px] text-slate-400 block mt-1">Write variable blanks like <code>@{{student_name}}</code> or <code>@{{due_amount}}</code>.</small>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">DLT Template ID (SMS)</label>
                    <input type="text" name="dlt_template_id" id="btDlt" placeholder="e.g. 1107161234567890123" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                    <select name="category" id="btCategory" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none">
                        <option value="General Notice">General Notice</option>
                        <option value="Fee Reminder">Fee Reminder</option>
                        <option value="Attendance Alert">Attendance Alert</option>
                        <option value="Exam Schedule">Exam Schedule</option>
                        <option value="Holiday Notice">Holiday Notice</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_active" id="btActive" value="1" checked class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                <label for="btActive" class="text-xs font-bold text-slate-800 cursor-pointer">
                    Active &mdash; offer it in the composer
                </label>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('btModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Save template
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterTemplateChannel(ch) {
    const cards = document.querySelectorAll('.template-card');
    cards.forEach(card => {
        if (ch === 'all' || card.getAttribute('data-channel') === ch) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });

    document.getElementById('tabBtnAll').className = (ch === 'all') ? 'px-3.5 py-1.5 rounded-md transition bg-white text-blue-600 shadow-xs' : 'px-3.5 py-1.5 rounded-md transition text-slate-600 hover:text-slate-900';
    document.getElementById('tabBtnSms').className = (ch === 'sms') ? 'px-3.5 py-1.5 rounded-md transition bg-white text-blue-600 shadow-xs' : 'px-3.5 py-1.5 rounded-md transition text-slate-600 hover:text-slate-900';
    document.getElementById('tabBtnWa').className = (ch === 'whatsapp') ? 'px-3.5 py-1.5 rounded-md transition bg-white text-blue-600 shadow-xs' : 'px-3.5 py-1.5 rounded-md transition text-slate-600 hover:text-slate-900';
}

function openCreateTemplateModal() {
    const form = document.getElementById('templateModalForm');
    form.action = "{{ route('school.communicate.templates.store') }}";
    document.getElementById('btMethod').value = "POST";
    document.getElementById('btModalTitle').textContent = "Add template";
    document.getElementById('btName').value = "";
    document.getElementById('btBody').value = "";
    document.getElementById('btDlt').value = "";
    document.getElementById('btActive').checked = true;
    openModal('btModal');
}

function editTemplateModal(tpl) {
    const form = document.getElementById('templateModalForm');
    form.action = "/school/communicate/broadcast/templates/" + tpl.id;
    document.getElementById('btMethod').value = "PUT";
    document.getElementById('btModalTitle').textContent = "Edit template";
    document.getElementById('btChannel').value = tpl.channel;
    document.getElementById('btName').value = tpl.name;
    document.getElementById('btBody').value = tpl.body;
    document.getElementById('btDlt').value = tpl.dlt_template_id || "";
    document.getElementById('btCategory').value = tpl.category || "General Notice";
    document.getElementById('btActive').checked = Boolean(tpl.is_active);
    openModal('btModal');
}
</script>
@endsection
