@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-exclamation mr-2 text-rose-600"></i> {{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Issue Item Form -->
        <div class="inv-card overflow-hidden h-fit">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-share-square text-sky-600"></i>
                    Issue New Item
                </span>
            </div>

            <form action="{{ route('school.inventory.issue-item.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Item <span class="text-rose-500">*</span></label>
                    <select name="inventory_item_id" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500 font-medium">
                        <option value="">-- Select Item --</option>
                        @foreach($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} (Available: {{ $item->stock_quantity }} {{ $item->unit }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Issue To <span class="text-rose-500">*</span></label>
                    <select name="issued_to_type" id="issueType" onchange="toggleRecipientType(this.value)" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="staff">Staff Member</option>
                        <option value="student">Student</option>
                        <option value="department">School Department</option>
                    </select>
                </div>

                <!-- Staff Select -->
                <div id="staffSelectGroup">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Staff Member <span class="text-rose-500">*</span></label>
                    <select name="issued_to_id" id="staffId" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Select Staff --</option>
                        @foreach($staffMembers as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->department ?? 'Staff' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Student Select -->
                <div id="studentSelectGroup" class="hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Student <span class="text-rose-500">*</span></label>
                    <select name="issued_to_id" id="studentId" disabled class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $stud)
                        <option value="{{ $stud->id }}">{{ $stud->first_name }} {{ $stud->last_name }} ({{ $stud->admission_no ?? 'ID:'.$stud->id }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Text -->
                <div id="deptSelectGroup" class="hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Department Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="department_name" placeholder="e.g. Science Dept, Sports Dept, Admin Office" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Quantity <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" min="1" value="1" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Issue Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Remarks / Purpose</label>
                    <textarea name="remarks" rows="2" placeholder="e.g. For Chemistry lab practical exam..." class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Issue Item
                </button>
            </form>
        </div>

        <!-- Issued Items Table -->
        <div class="lg:col-span-2 inv-card overflow-hidden">
            <div class="inv-section-title">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                    Issuance & Return Register
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Issued To</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Issue Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issuedList as $issue)
                        <tr>
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $issue->item->name ?? 'Deleted Item' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $issue->item->sku ?? '' }}</div>
                            </td>
                            <td class="text-xs font-medium text-slate-800">{{ $issue->issued_to_name }}</td>
                            <td>
                                <span class="text-[11px] px-2 py-0.5 rounded font-medium bg-slate-100 text-slate-600 capitalize">
                                    {{ $issue->issued_to_type }}
                                </span>
                            </td>
                            <td class="text-xs font-bold font-mono">{{ $issue->quantity }}</td>
                            <td class="text-xs text-slate-600">{{ $issue->issue_date ? $issue->issue_date->format('d M Y') : '—' }}</td>
                            <td class="text-xs text-slate-500">{{ $issue->return_date ? $issue->return_date->format('d M Y') : '—' }}</td>
                            <td>
                                <span class="inv-badge {{ $issue->status === 'Returned' ? 'inv-badge-ok' : 'inv-badge-warn' }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                @if($issue->status !== 'Returned')
                                <form action="{{ route('school.inventory.return-item', $issue->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-xs font-bold hover:bg-emerald-100 transition">
                                        Return
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-slate-400"><i class="fa-solid fa-check text-emerald-500"></i> Done</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 text-xs">No items currently issued.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($issuedList->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $issuedList->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleRecipientType(type) {
    const staffGroup = document.getElementById('staffSelectGroup');
    const studentGroup = document.getElementById('studentSelectGroup');
    const deptGroup = document.getElementById('deptSelectGroup');
    const staffId = document.getElementById('staffId');
    const studentId = document.getElementById('studentId');

    staffGroup.classList.add('hidden');
    studentGroup.classList.add('hidden');
    deptGroup.classList.add('hidden');
    staffId.disabled = true;
    studentId.disabled = true;

    if (type === 'staff') {
        staffGroup.classList.remove('hidden');
        staffId.disabled = false;
    } else if (type === 'student') {
        studentGroup.classList.remove('hidden');
        studentId.disabled = false;
    } else {
        deptGroup.classList.remove('hidden');
    }
}
</script>
@endsection

