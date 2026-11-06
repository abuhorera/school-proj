@extends('layouts.app')

@section('title', 'Compose Broadcast Message')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

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
                <span class="text-blue-600 font-semibold">Compose Message</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Compose Broadcast Message</h1>
                    <p class="text-xs text-slate-500">Send mass announcements via multi-channel SMS, WhatsApp, Email, and Push notification.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.communicate.templates') }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-file-code text-slate-500"></i>
                <span>Templates</span>
            </a>
            <a href="{{ route('school.communicate.broadcast') }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Broadcast History</span>
            </a>
        </div>
    </div>

    <!-- Comms Wallet Quick Bar -->
    <div class="p-4 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-2xl text-white shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-blue-300">
                <i class="fa-solid fa-wallet text-base"></i>
            </div>
            <div>
                <div class="text-xs text-slate-300">Comms Wallet Balance</div>
                <div class="text-lg font-bold text-white tracking-wide">₹{{ number_format($wallet->balance, 2) }}</div>
            </div>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="text-slate-300">Rates: SMS ₹0.25 &bull; WhatsApp ₹0.85 &bull; Email ₹0.05</span>
            <a href="{{ route('school.comms') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-3 py-1.5 rounded-lg transition">
                Top-up Wallet
            </a>
        </div>
    </div>

    <!-- Main Composer Form Card -->
    <form action="{{ route('school.communicate.broadcast.send') }}" method="POST" id="broadcastForm" class="space-y-6">
        @csrf

        <!-- 1. Channels Selection Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">1</span>
                    <span>Target Delivery Channels <span class="text-rose-500">*</span></span>
                </h3>
                <span class="text-xs text-slate-500">Select one or more delivery channels</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <!-- SMS -->
                <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3.5 flex items-center justify-between transition hover:border-blue-400 has-checked:border-blue-600 has-checked:bg-blue-50/50">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="channels[]" value="sms" checked onchange="recalculateCost()" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 channel-check">
                        <div>
                            <div class="text-xs font-bold text-slate-900">SMS Channel</div>
                            <div class="text-[11px] text-slate-500">DLT Verified Gateway</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-comment-sms text-blue-600 text-lg"></i>
                </label>

                <!-- WhatsApp -->
                <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3.5 flex items-center justify-between transition hover:border-emerald-400 has-checked:border-emerald-600 has-checked:bg-emerald-50/50">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="channels[]" value="whatsapp" checked onchange="recalculateCost()" class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 channel-check">
                        <div>
                            <div class="text-xs font-bold text-slate-900">WhatsApp</div>
                            <div class="text-[11px] text-slate-500">Meta Cloud API</div>
                        </div>
                    </div>
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-lg"></i>
                </label>

                <!-- Email -->
                <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3.5 flex items-center justify-between transition hover:border-indigo-400 has-checked:border-indigo-600 has-checked:bg-indigo-50/50">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="channels[]" value="email" onchange="recalculateCost()" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 channel-check">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Email Broadcast</div>
                            <div class="text-[11px] text-slate-500">Formatted Newsletter</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-envelope text-indigo-600 text-lg"></i>
                </label>

                <!-- Push Notification -->
                <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3.5 flex items-center justify-between transition hover:border-purple-400 has-checked:border-purple-600 has-checked:bg-purple-50/50">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="channels[]" value="push" onchange="recalculateCost()" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4 channel-check">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Mobile Push</div>
                            <div class="text-[11px] text-slate-500">Instant Parent App Alert</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-bell text-purple-600 text-lg"></i>
                </label>
            </div>
        </div>

        <!-- 2. Message Composition Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">2</span>
                    <span>Compose Message</span>
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-slate-500">Insert Template:</span>
                    <select id="templatePicker" onchange="applyTemplate(this.value)" class="text-xs border border-slate-300 rounded-lg px-2.5 py-1 focus:outline-none">
                        <option value="">-- Choose a pre-approved template --</option>
                        <optgroup label="SMS Templates">
                            @foreach($smsTemplates as $t)
                                <option value="{{ $t->body }}" data-subj="{{ $t->name }}">{{ $t->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="WhatsApp Templates">
                            @foreach($waTemplates as $t)
                                <option value="{{ $t->body }}" data-subj="{{ $t->name }}">{{ $t->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Message Subject / Header <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="subject" id="broadcastSubject" required value="{{ old('subject') }}" placeholder="Enter message subject / heading" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none font-semibold text-slate-800">
            </div>

            <!-- Body Textarea -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Message Body <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-slate-500 font-mono" id="charCounter">0 characters &bull; 0 SMS segments</span>
                </div>
                <textarea name="body" id="broadcastBody" rows="5" required oninput="updateCharCount()" placeholder="Type your announcement or alert message here. Use variables like @{{student_name}}, @{{parent_name}}, @{{due_amount}}, @{{school_name}}..." class="w-full p-3.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed font-mono">{{ old('body') }}</textarea>

                <!-- Variable Chips -->
                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                    <span class="text-[10px] text-slate-400 font-semibold mr-1">Insert variable:</span>
                    <button type="button" onclick="insertVariable('@{{student_name}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{student_name}}</button>
                    <button type="button" onclick="insertVariable('@{{parent_name}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{parent_name}}</button>
                    <button type="button" onclick="insertVariable('@{{class_name}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{class_name}}</button>
                    <button type="button" onclick="insertVariable('@{{due_amount}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{due_amount}}</button>
                    <button type="button" onclick="insertVariable('@{{due_date}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{due_date}}</button>
                    <button type="button" onclick="insertVariable('@{{school_name}}')" class="px-2 py-0.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[10px] font-mono">@{{school_name}}</button>
                </div>
            </div>
        </div>

        <!-- 3. Target Recipients Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">3</span>
                    <span>Select Target Recipients <span class="text-rose-500">*</span></span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Recipient Group <span class="text-rose-500">*</span>
                    </label>
                    <select name="recipient_group" id="recipientGroupSelect" required onchange="handleRecipientGroupChange(this.value)" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="All Parents">All Parents (Entire School)</option>
                        <option value="All Staff">All Staff &amp; Faculty</option>
                        <option value="Parents with Due Fees">Parents with Pending Dues</option>
                        <option value="Parents with No Dues">Parents with No Dues (Cleared)</option>
                        <option value="Parents of Specific Class(es)">Parents of Specific Class(es)</option>
                    </select>
                </div>

                <!-- Classes Multiselect (conditional) -->
                <div id="classesSelectionWrap" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Select Specific Class(es)
                    </label>
                    <select name="class_ids[]" multiple onchange="recalculateCost()" class="w-full px-3.5 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none h-24">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-slate-400">Hold Ctrl / Cmd to select multiple classes</span>
                </div>
            </div>
        </div>

        <!-- 4. Estimated Dispatch Summary Card -->
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <div class="text-xs font-bold text-slate-900 uppercase tracking-wider">Dispatch Cost Summary</div>
                <div class="flex items-center space-x-3 text-xs text-slate-600">
                    <span>Est. Recipients: <strong class="text-slate-900 font-bold" id="estRecipients">120</strong></span>
                    <span>&bull;</span>
                    <span>Selected Channels: <strong class="text-slate-900 font-bold" id="estChannels">2</strong></span>
                    <span>&bull;</span>
                    <span>Estimated Deduction: <strong class="text-rose-600 font-bold text-sm" id="estTotalCost">₹132.00</strong></span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="recalculateCost()" class="px-4 py-2 text-xs font-semibold bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg transition">
                    <i class="fa-solid fa-calculator mr-1"></i> Check Cost
                </button>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Dispatch Broadcast
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updateCharCount() {
    const text = document.getElementById('broadcastBody').value;
    const len = text.length;
    const smsCount = len === 0 ? 0 : (len <= 160 ? 1 : Math.ceil(len / 153));
    document.getElementById('charCounter').textContent = `${len} characters • ${smsCount} SMS segment${smsCount > 1 ? 's' : ''}`;
}

function insertVariable(v) {
    const textarea = document.getElementById('broadcastBody');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + v + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + v.length;
    updateCharCount();
}

function applyTemplate(body) {
    if (!body) return;
    const picker = document.getElementById('templatePicker');
    const selectedOpt = picker.options[picker.selectedIndex];
    const subj = selectedOpt.getAttribute('data-subj') || '';
    
    document.getElementById('broadcastBody').value = body;
    if (subj) {
        document.getElementById('broadcastSubject').value = subj;
    }
    updateCharCount();
}

function handleRecipientGroupChange(val) {
    const wrap = document.getElementById('classesSelectionWrap');
    if (val === 'Parents of Specific Class(es)') {
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }
    recalculateCost();
}

function recalculateCost() {
    const checks = document.querySelectorAll('.channel-check:checked');
    const channelCount = checks.length;
    let rate = 0;
    checks.forEach(c => {
        if (c.value === 'sms') rate += 0.25;
        if (c.value === 'whatsapp') rate += 0.85;
        if (c.value === 'email') rate += 0.05;
    });

    const group = document.getElementById('recipientGroupSelect').value;
    let recipients = 120;
    if (group === 'All Staff') recipients = 35;
    if (group === 'Parents with Due Fees') recipients = 42;
    if (group === 'Parents with No Dues') recipients = 78;
    if (group === 'Parents of Specific Class(es)') recipients = 60;

    const total = (recipients * rate).toFixed(2);

    document.getElementById('estRecipients').textContent = recipients;
    document.getElementById('estChannels').textContent = channelCount;
    document.getElementById('estTotalCost').textContent = '₹' + total;
}

// Initial calculation
recalculateCost();
updateCharCount();
</script>
@endsection
