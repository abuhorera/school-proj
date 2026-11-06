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

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-truck text-slate-700"></i>
                Suppliers & Vendors ({{ $suppliers->count() }})
            </span>
            <button onclick="document.getElementById('addSupplierModal').classList.remove('hidden')" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Add Supplier
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Supplier / Company</th>
                        <th>Type</th>
                        <th>Contact Person</th>
                        <th>Phone & Email</th>
                        <th>GSTIN</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $idx => $sup)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold text-slate-900">{{ $sup->name }}</div>
                            @if($sup->address)
                            <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $sup->address }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium">
                                {{ $sup->supplier_type }}
                            </span>
                        </td>
                        <td class="text-xs font-medium text-slate-700">{{ $sup->contact_person ?: '—' }}</td>
                        <td>
                            <div class="text-xs font-semibold text-slate-800">{{ $sup->phone ?: '—' }}</div>
                            @if($sup->email)
                            <div class="text-[11px] text-slate-400">{{ $sup->email }}</div>
                            @endif
                        </td>
                        <td class="font-mono text-xs text-slate-600">{{ $sup->gstin ?: '—' }}</td>
                        <td>
                            <span class="inv-badge {{ $sup->status === 'Active' ? 'inv-badge-ok' : 'inv-badge-danger' }}">
                                {{ $sup->status }}
                            </span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="openEditSupModal({{ $sup->id }}, '{{ addslashes($sup->name) }}', '{{ $sup->supplier_type }}', '{{ addslashes($sup->contact_person ?? '') }}', '{{ $sup->phone ?? '' }}', '{{ $sup->email ?? '' }}', '{{ $sup->gstin ?? '' }}', '{{ addslashes($sup->address ?? '') }}', '{{ $sup->status }}')" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('school.inventory.suppliers.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Remove supplier {{ $sup->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">No suppliers registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Supplier -->
<div id="addSupplierModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Add New Supplier / Vendor</h4>
            <button onclick="document.getElementById('addSupplierModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('school.inventory.suppliers.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Company / Supplier Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Sharma Stationers, Crisp Uniforms" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier Type</label>
                    <select name="supplier_type" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="General">General / Stationery</option>
                        <option value="Uniform Vendor">Uniform Vendor</option>
                        <option value="Book Vendor">Book Publisher / Vendor</option>
                        <option value="Medical">Medical / First Aid</option>
                        <option value="Lab Equipment">Lab Scientific Supplies</option>
                        <option value="Sports">Sports Equipment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Contact Person</label>
                    <input type="text" name="contact_person" placeholder="Manager / Rep Name" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 9810011223" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="vendor@domain.com" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">GSTIN Number</label>
                    <input type="text" name="gstin" placeholder="e.g. 07ABCDE1234F1Z5" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Address</label>
                    <textarea name="address" rows="2" placeholder="Full office address..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addSupplierModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-slate-900 text-white rounded-lg hover:bg-slate-800">Register Supplier</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Supplier -->
<div id="editSupplierModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Edit Supplier</h4>
            <button onclick="document.getElementById('editSupplierModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="editSupForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Company / Supplier Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="editSupName" name="name" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier Type</label>
                    <select id="editSupType" name="supplier_type" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="General">General</option>
                        <option value="Uniform Vendor">Uniform Vendor</option>
                        <option value="Book Vendor">Book Vendor</option>
                        <option value="Medical">Medical</option>
                        <option value="Lab Equipment">Lab Equipment</option>
                        <option value="Sports">Sports</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Contact Person</label>
                    <input type="text" id="editSupContact" name="contact_person" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone</label>
                    <input type="text" id="editSupPhone" name="phone" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" id="editSupEmail" name="email" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">GSTIN</label>
                    <input type="text" id="editSupGstin" name="gstin" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select id="editSupStatus" name="status" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Address</label>
                    <textarea id="editSupAddress" name="address" rows="2" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editSupplierModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-sky-600 text-white rounded-lg hover:bg-sky-700">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditSupModal(id, name, type, contact, phone, email, gstin, address, status) {
    document.getElementById('editSupForm').action = "{{ url('school/inventory/suppliers') }}/" + id;
    document.getElementById('editSupName').value = name;
    document.getElementById('editSupType').value = type;
    document.getElementById('editSupContact').value = contact;
    document.getElementById('editSupPhone').value = phone;
    document.getElementById('editSupEmail').value = email;
    document.getElementById('editSupGstin').value = gstin;
    document.getElementById('editSupAddress').value = address;
    document.getElementById('editSupStatus').value = status;
    document.getElementById('editSupplierModal').classList.remove('hidden');
}
</script>
@endsection

